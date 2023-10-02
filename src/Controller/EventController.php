<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Entity\EventAttendee;
use App\Form\EventFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EventController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $eventsRepository = $entityManager->getRepository(Event::class);

        // Créez un formulaire de filtre d'intervalle de temps
        $filterForm = $this->createForm(EventFilterType::class);
        $filterForm->handleRequest($request);

        // Par défaut, affichez tous les événements
        $events = $eventsRepository->findBy([], ['beginAt' => 'ASC']);

        // Si le formulaire est soumis, filtrez les événements en fonction des dates sélectionnées
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            $startDate = $data['startDate'];
            $endDate = $data['endDate'];

            // Utilisez une requête DQL pour filtrer les événements
            $query = $entityManager->createQuery('
                SELECT e
                FROM App\Entity\Event e
                WHERE (
                    (e.beginAt >= :startDate AND e.beginAt <= :endDate) OR
                    (e.endAt >= :startDate AND e.endAt <= :endDate) OR
                    (e.beginAt <= :startDate AND e.endAt >= :endDate)
                )
                ORDER BY e.beginAt ASC
            ');


            $query->setParameter('startDate', $startDate);
            $query->setParameter('endDate', $endDate);

            $events = $query->getResult();
        }

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'filterForm' => $filterForm->createView(),
            'controller_name' => 'EventController',
        ]);
    }


    #[Route('/my-events', name: 'myevents')]
    public function myevents(EntityManagerInterface $entityManager): Response
    {
        $eventAttendeeRepository = $entityManager->getRepository(EventAttendee::class);

        // Récupérez l'utilisateur actuel
        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $eventAttendees = $eventAttendeeRepository->findAll();
        

        $userEvents = new ArrayCollection();

        foreach ($eventAttendees as $eventAttendee) {
        
            if ($eventAttendee->getUser()[0] == $currentUser){
                
                $userEvents->add($eventAttendee->getEvent()[0]);

            } 
        }

        return $this->render('event/index.html.twig', [
            'myevents' => false,
            'events' => $userEvents,
            'controller_name' => 'EventController',
        ]);
    }





    #[Route('/new-event', name: 'newevent')]
    public function add(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $event = new Event();

        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

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

        $user = $this->getUser();
        if ($event->getCreator() !== $user) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à modifier cet événement.');
        }

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event->setCreator($user);

            $entityManager->flush();

            $this->addFlash('info', 'Événement modifié avec succès');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('form/newevent.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    #[Route('/delete-event/{id}', name: 'delete_event')]
    public function deleteEvent(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            //throw $this->createNotFoundException('L\'événement avec l\'ID '.$id.' n\'existe pas.');
            return $this->redirectToRoute('myevents');
        }

        $user = $this->getUser();
        if ($event->getCreator() !== $user) {
            // throw new AccessDeniedException('Vous n\'êtes pas autorisé à modifier cet événement.');
            return $this->redirectToRoute('myevents');
        }

        $entityManager->remove($event);
        $entityManager->flush();

        $this->addFlash('success', 'Événement supprimé');

        // Récupérer l'URL précédente à partir de la session
        $referer = $request->headers->get('referer');
        
        // Rediriger l'utilisateur vers l'URL précédente
        return $this->redirect($referer);
    }
}
