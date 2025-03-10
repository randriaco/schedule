<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(
        Request $request, 
        MailerInterface $mailer,
        EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            // Enregistrement des données du formulaire dans la base de données
            $entityManager->persist($contact);
            $entityManager->flush();
            
            // Envoi de l'email
            $email = (new Email())
                ->from('contact.schedule@restolico.fr') // Adresse email de l'expéditeur
                ->to('ralaydev@gmail.com')
                ->subject($contact->getSubject())
                ->text('Sender : '.$contact->getEmail().\PHP_EOL.$contact->getMessage());

            $mailer->send($email);

            // Message flash pour informer l'utilisateur
            $this->addFlash('success', 'Votre message a bien été envoyé avec succès');

            // Redirection après soumission
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
