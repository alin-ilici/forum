<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function showAction($page, $like) {
        $maxMembersPerPage = $this->container->getParameter('maxMembersPerPage');
        if ($page == null) {
            $page = 1;
        }

        /** @var \Forum\CoreBundle\Repository\UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository("CoreBundle:User");

        $query = $userRepository->createQueryBuilder('u')
            ->orderBy('u.username', 'ASC')
            ->setFirstResult(($page - 1) * $maxMembersPerPage)
            ->setMaxResults($maxMembersPerPage);

        if ($like != null) {
            $query->where('u.username LIKE :like')
                ->setParameter('like', '%' . $like . '%');
        }

        /** @var \Forum\CoreBundle\Entity\User[] $members*/
        $members = $query->getQuery()->getResult();

        $query = $userRepository->createQueryBuilder('u')
            ->select('COUNT(u.id)');

        if ($like != null) {
            $query->where('u.username LIKE :like')
                ->setParameter('like', '%' . $like . '%');
        }

        $countMembers = $query->getQuery()
            ->getSingleResult();

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a> > Members List';

        $totalPages = ((int)$countMembers[1] % $maxMembersPerPage == 0) ?
            (int)((int)$countMembers[1] / $maxMembersPerPage) : (int)((int)$countMembers[1] / $maxMembersPerPage + 1);

        $totalPages = ($countMembers[1] == 0) ? 1 : $totalPages;

        return $this->render('CoreBundle:User:members.html.twig', array(
            'members' => $members,
            'whereAmI' => $whereAmI,
            'like' => $like,
            'totalPages' => $totalPages,
            'currentPage' => (int)$page,
        ));
    }
}