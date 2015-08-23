<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function searchResultsAction(Request $request, $page) {
        $maxResultsPerPage = $this->container->getParameter('maxResultsPerPage');
        if ($page == null) {
            $page = 1;
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $searchFor = $request->query->get('searchFor');
        $searchIn = $request->query->get('searchIn');

        /** @var \Forum\CoreBundle\Repository\TopicRepository $topicRepository */
        $topicRepository = $this->getDoctrine()->getRepository("CoreBundle:Topic");

        /** @var \Forum\CoreBundle\Repository\MessageRepository $messageRepository */
        $messageRepository = $this->getDoctrine()->getRepository("CoreBundle:Message");

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a> > Search results';

        if ($searchIn === 'inTopics') {
            /** @var \Forum\CoreBundle\Entity\Topic[] $topics */
            $topics = $topicRepository->createQueryBuilder('t')
                ->where('t.name LIKE :search_for')
                ->setParameter('search_for', '%' . $searchFor . '%')
                ->setFirstResult(($page - 1) * $maxResultsPerPage)
                ->setMaxResults($maxResultsPerPage)
                ->getQuery()
                ->getResult();

            $countTopics = $topicRepository->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->where('t.name LIKE :search_for')
                ->setParameter('search_for', '%' . $searchFor . '%')
                ->getQuery()
                ->getSingleResult();

            $totalPages = ((int)$countTopics[1] % $maxResultsPerPage == 0) ?
                (int)((int)$countTopics[1] / $maxResultsPerPage) : (int)((int)$countTopics[1] / $maxResultsPerPage + 1);

            $totalPages = ($countTopics[1] == 0) ? 0 : $totalPages;

            return $this->render('CoreBundle:Search:searchResults.html.twig', array(
                'whatToRender' => 'topics',
                'results' => $topics,
                'whereAmI' => $whereAmI,
                'searchFor' => $searchFor,
                'searchIn' => $searchIn,
                'totalPages' => $totalPages,
                'currentPage' => (int)$page,
            ));
        } elseif ($searchIn === 'inMessages') {
            /** @var \Forum\CoreBundle\Entity\Messages[] $messagesResults */
            $messagesResults = $messageRepository->createQueryBuilder('m')
                ->where('m.name LIKE :search_for')
                ->setParameter('search_for', '%' . $searchFor . '%')
                ->setFirstResult(($page - 1) * $maxResultsPerPage)
                ->setMaxResults($maxResultsPerPage)
                ->getQuery()
                ->getResult();

            foreach ($messagesResults as $message) {
                $query = 'SELECT m.id, @Rank:= @Rank + 1 AS rank
                    FROM message m
                    JOIN (SELECT @Rank:= 0) r
                    WHERE m.id_topic = ' . $message->getTopic()->getId() . '
                    ORDER BY m.date_created ASC';

                $stmt = $em->getConnection()->prepare($query);
                $stmt->execute();
                $results = $stmt->fetchAll();

                $data = array();
                foreach ($results as $result) {
                    $data[$result['id']] = $result['rank'];
                }

                $pageForMessage = ((int)$data[$message->getId()] % $maxResultsPerPage == 0) ?
                    (int)((int)$data[$message->getId()] / $maxResultsPerPage) : (int)((int)$data[$message->getId()] / $maxResultsPerPage + 1);

                $messages[$message->getId()]['message'] = $message;
                $messages[$message->getId()]['page'] = $pageForMessage;
            }

            $countMessages = $messageRepository->createQueryBuilder('m')
                ->select('COUNT(m.id)')
                ->where('m.name LIKE :search_for')
                ->setParameter('search_for', '%' . $searchFor . '%')
                ->getQuery()
                ->getSingleResult();

            $totalPages = ((int)$countMessages[1] % $maxResultsPerPage == 0) ?
                (int)((int)$countMessages[1] / $maxResultsPerPage) : (int)((int)$countMessages[1] / $maxResultsPerPage + 1);

            $totalPages = ($countMessages[1] == 0) ? 0 : $totalPages;

            return $this->render('CoreBundle:Search:searchResults.html.twig', array(
                'whatToRender' => 'messages',
                'results' => $messages,
                'whereAmI' => $whereAmI,
                'searchFor' => $searchFor,
                'searchIn' => $searchIn,
                'totalPages' => $totalPages,
                'currentPage' => (int)$page,
            ));
        } elseif ($searchIn === 'inMembers') {
            return $this->redirect($this->generateUrl('forum_core_user_show', array('like' => $searchFor)));
        }
    }
}
