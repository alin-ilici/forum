<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function searchResultsAction(Request $request) {
        $maxResultsPerPage = $this->container->getParameter('maxResultsPerPage');

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $searchFor = $request->request->get('searchInput');
        $searchIn = $request->request->get('searchInBlock');

        /** @var \Forum\CoreBundle\Repository\TopicRepository $topicRepository */
        $topicRepository = $this->getDoctrine()->getRepository("CoreBundle:Topic");

        /** @var \Forum\CoreBundle\Repository\MessageRepository $messageRepository */
        $messageRepository = $this->getDoctrine()->getRepository("CoreBundle:Message");

        /** @var \Forum\CoreBundle\Repository\UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository("CoreBundle:User");

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a> > Search results';

        if ($searchIn === 'inTopics') {
            /** @var \Forum\CoreBundle\Entity\Topic[] $topics */
            $topics = $topicRepository->createQueryBuilder('t')
                ->where('t.name LIKE :search_for')
                ->setParameter('search_for', '%' . $searchFor . '%')
                ->getQuery()
                ->getResult();

            return $this->render('CoreBundle:Search:searchResults.html.twig', array(
                'whatToRender' => 'topics',
                'results' => $topics,
                'whereAmI' => $whereAmI
            ));
        } elseif ($searchIn === 'inMessages') {
            /** @var \Forum\CoreBundle\Entity\Messages[] $messagesResults */
            $messagesResults = $messageRepository->createQueryBuilder('m')
                ->where('m.name LIKE :search_for')
                ->setParameter('search_for', '%' . $searchFor . '%')
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

                $page = ((int)$data[$message->getId()] % $maxResultsPerPage == 0) ?
                    (int)((int)$data[$message->getId()] / $maxResultsPerPage) : (int)((int)$data[$message->getId()] / $maxResultsPerPage + 1);

                $messages[$message->getId()]['message'] = $message;
                $messages[$message->getId()]['page'] = $page;
            }

            return $this->render('CoreBundle:Search:searchResults.html.twig', array(
                'whatToRender' => 'messages',
                'results' => $messages,
                'whereAmI' => $whereAmI
            ));
        } elseif ($searchIn === 'inMembers') {
            ;
        }
    }
}
