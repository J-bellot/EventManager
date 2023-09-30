<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class EventController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }


    #[Route('/new-event', name: 'newevent')]
    public function add(Request $request, EntityManagerInterface $entityManagerInterface, UserInterface $userInterface): Response
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $this->getUser();
            $event->setCreator($user);

            $entityManagerInterface->persist($event);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');

            return $this->redirectToRoute('homepage');


        }
        

        return $this->render('form/newevent.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/edit-event/{id}', name: 'editevent')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserInterface $userInterface, int $id): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement avec l\'ID '.$id.' n\'existe pas.');
        }

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $event->setCreator($user);

            $entityManager->flush();

            $this->addFlash('success', 'Événement modifié avec succès');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('form/newevent.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }


}
