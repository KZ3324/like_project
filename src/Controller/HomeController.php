<?php

namespace App\Controller;

use App\Entity\LikeRelation;
use App\Entity\User;
use App\Repository\LikeRelationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class HomeController extends AbstractController
{
    private $userRepository;
    private $likeRepository;

    public function __construct(
        UserRepository $userRepository,
        LikeRelationRepository $likeRepository
    ) {
        $this->userRepository = $userRepository;
        $this->likeRepository = $likeRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        new User();
        $users = $this->userRepository->findAll();

        // Remplacement de la données des like
        foreach ($users as $key => $value) {
            $nbrLike = $this->likeRepository->getAllLikeById($value->getId());
            foreach ($nbrLike as $cle => $valeur) {
                $nbrLike = $valeur['COUNT(id_liker)'];
            }
            $value->setLikePlus($nbrLike);
        }

        return $this->render('home/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/like/{id}', name: 'app_like')]
    public function likeUser($id, Request $request)
    {
        $user = $this->getUser();

        // 1


        $like = new LikeRelation();


        $result = $this->likeRepository->likeOrUnlike($user->getUserIdentifier(), $id);

        if (!$result) {
            $like->setIdLiker($id);
            $like->setUser($user);
            $this->likeRepository->add($like, true);
        } else {
            $this->likeRepository->remove($this->likeRepository->findOneBy(array('id_liker'=>$id, 'user'=>$this->getUser())), true);
        }


        return $this->redirectToRoute('app_home');
    }
}
