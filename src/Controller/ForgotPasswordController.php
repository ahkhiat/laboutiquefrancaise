<?php

namespace App\Controller;

use DateTime;
use App\Class\Mail;
use App\Form\ResetPasswordType;
use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgotPasswordController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/mot-de-passe-oublie', name: 'app_password')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        // 1 - Formualaire
        $form = $this->createForm(ForgotPasswordType::class);        
        $form->handleRequest($request);

        // 2 - Traitement du form
        if ($form->isSubmitted() && $form->isValid()) {

        // 3 - Verifier si email en BDD
        $email = $form->get('email')->getData();

        $user = $userRepository->findOneByEmail($email);

        // 4 - Envoyer une notification
        $this->addFlash('success', "Si votre email est enregistré, vous recevrez un email pour réinitialiser votre mot de passe");

        // 5 - Si User existe, on reset le pswd et on envoie un email
        if($user) {

            // - 5 . a - Créer un token qu'on va stocker en bdd
            $token = bin2hex(random_bytes(15));
            $user->setToken($token);

            // - 5 . b - stocker la date d'expiration de notre token 
            $date = new DateTime();
            $date->modify('+10minutes');
            $user->setTokenExpiresAt($date);

            $this->em->flush();

            $mail = new Mail();
            $vars = [
                'link' => $this->generateUrl('app_password_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL) ,
            ];
            $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(), 'Modification de votre mot de passe', 'forgotPassword.html', $vars );
            
        }
        // 4 - if yes, reset du pswd et envoi du novueau pswd par email

        }
        return $this->render('password/index.html.twig', [
            'forgotPasswordForm' => $form->createView()
        ]);
    }

    #[Route('/mot-de-passe/reset/{token}', name: 'app_password_reset')]
    public function update(Request $request, UserRepository $userRepository, $token): Response
    {
        if(!$token) {
            return $this->redirectToRoute('app_password');
        }
        
        $user = $userRepository->findOneByToken($token);
        $now = new DateTime();

        if(!$user || $now > $user->getTokenExpiresAt()) {
            return $this->redirectToRoute('app_password');
        }
       
        $form = $this->createForm(ResetPasswordType::class, $user);   

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);
            $user->setTokenExpiresAt(null);
            
            $this->em->flush();
            $this->addFlash(
            'success',
            'Votre mot de passe est correctement mis à jour');

        }
        
        return $this->render('password/reset.html.twig', [
            'forgotPasswordForm' => $form->createView()
        ]);
    }
}
