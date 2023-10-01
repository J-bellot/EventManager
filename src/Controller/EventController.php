<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $eventsRepository = $entityManager->getRepository(Event::class);
        $events = $eventsRepository->findBy([], ['beginAt' => 'ASC']);

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'controller_name' => 'EventController',
        ]);
    }


    #[Route('/my-events', name: 'myevents')]
    public function myevents(EntityManagerInterface $entityManager): Response
    {
        $eventsRepository = $entityManager->getRepository(Event::class);
    
        $currentUser = $this->getUser();
        
        $events = $eventsRepository->findBy(['creator' => $currentUser]);

        return $this->render('event/index.html.twig', [
            'myevents' => true,
            'events' => $events, // Transmettez les événements au template.
            'controller_name' => 'EventController',
        ]);
    }


    #[Route('/new-event', name: 'newevent')]
    public function add(Request $request, EntityManagerInterface $entityManagerInterface): Response
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
    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
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
