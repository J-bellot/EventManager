<?php

use App\Entity\Eventattendee;
use App\Entity\User;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/eventattendee')]
class EventattendeeController extends AbstractController
{
    #[Route('/register/{eventId}', name: 'register_event')]
    public function register(Request $request, EntityManagerInterface $entityManager, int $eventId): Response
    {
        // Récupérez l'utilisateur connecté (vous devrez peut-être ajuster la logique d'authentification si nécessaire)
        $user = $this->getUser();

        // Récupérez l'événement en fonction de l'ID passé en paramètre
        $event = $entityManager->getRepository(Event::class)->find($eventId);

        // Vérifiez que l'utilisateur et l'événement existent
        if (!$user || !$event) {
            throw $this->createNotFoundException('Utilisateur ou événement non trouvé.');
        }

        // Créez une nouvelle instance de Eventattendee et associez l'utilisateur et l'événement
        $eventAttendee = new Eventattendee();
        $eventAttendee->setUser($user);
        $eventAttendee->setEvent($event);

        // Enregistrez l'inscription dans la base de données
        $entityManager->persist($eventAttendee);
        $entityManager->flush();

        // Vous pouvez rediriger l'utilisateur vers une page de confirmation ou une autre page
        // après avoir effectué l'inscription.
        // Par exemple, redirigez l'utilisateur vers la page d'accueil.
        return $this->redirectToRoute('homepage');
    }
}
