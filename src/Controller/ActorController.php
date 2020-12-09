<?php


namespace App\Controller;

use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Actor;

class ActorController extends AbstractController
{
    /**
     * @Route("/actor", name="actors_index")
     * @param ActorRepository $actorRepository
     * @return Response
     */
    public function index(ActorRepository $actorRepository): Response
    {
        $actors = $actorRepository->findAll();
        return $this->render('actor/index.html.twig', [
            'actors' => $actors
            ]);
    }

    /**
     * @Route("/actor/{id}", name="actor_show")
     * @param Actor $actor
     * @return Response
     */
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor
        ]);


    }
}





