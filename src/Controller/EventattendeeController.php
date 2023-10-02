<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventAttendee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class EventAttendeeController extends AbstractController
{
    #[Route('/register/{event_id}', name: 'register_for_event')]
    public function registerForEvent($event_id, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupérer l'événement à partir de l'ID
        $event = $entityManager->getRepository(Event::class)->find($event_id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement avec l\'ID ' . $event_id . ' n\'existe pas.');
        }

        // Récupérer l'utilisateur actuellement connecté
        /** @var UserInterface $user */
        $user = $this->getUser();

        if ($user != null){
            // Rechercher l'inscription de l'utilisateur à cet événement
            $eventAttendee = null;

            foreach ($user->getEventAttendees() as $attendee) {
                if ($attendee->getEvent()->contains($event)) {
                    $eventAttendee = $attendee;
                    break;
                }
            }

            if ($eventAttendee) {
                $this->addFlash('warning', 'Vous êtes déjà inscrit à cet événement.');
            } else {
                // Créer un nouvel objet EventAttendee
                $eventAttendee = new EventAttendee();

                // Associer l'utilisateur et l'événement à EventAttendee
                $eventAttendee->addEvent($event);
                $eventAttendee->addUser($user);

                // Enregistrer l'objet EventAttendee dans la base de données
                $entityManager->persist($eventAttendee);
                $entityManager->flush();

                $this->addFlash('success', 'Inscription réussie');
            }

            // Récupérer l'URL précédente à partir de la session
            $referer = $request->headers->get('referer');
            // Rediriger l'utilisateur vers l'URL précédente
            return $this->redirect($referer);
        }
        
        return $this->redirectToRoute('homepage');
    }


    #[Route('/unregister/{event_id}', name: 'unregister_for_event')]
    public function unregisterForEvent($event_id, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupérer l'événement à partir de l'ID
        $event = $entityManager->getRepository(Event::class)->find($event_id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement avec l\'ID ' . $event_id . ' n\'existe pas.');
        }

        // Récupérer l'utilisateur actuellement connecté
        /** @var UserInterface $user */
        $user = $this->getUser();

        // Rechercher l'inscription de l'utilisateur à cet événement
        $eventAttendee = null;

        foreach ($user->getEventAttendees() as $attendee) {
            if ($attendee->getEvent()[0] === $event) {
                $eventAttendee = $attendee;
                break;
            }
        }

        if (!$eventAttendee) {
            $this->addFlash('error', 'Vous n\'êtes pas inscrit à cet événement.');
        } else {
            // Supprimer l'inscription de l'utilisateur à cet événement
            $entityManager->remove($eventAttendee);
            $entityManager->flush();

            $this->addFlash('success', 'Désinscription réussie');
        }

        // Récupérer l'URL précédente à partir de la session
        $referer = $request->headers->get('referer');
        
        // Rediriger l'utilisateur vers l'URL précédente
        return $this->redirect($referer);
    }

}
