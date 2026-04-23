<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\SignUpType;
use App\Form\ImageType;
use App\Form\ChangePasswordType;
use App\Entity\Admin;
use App\Entity\Image;
use App\Entity\ChangePaswwordType;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\ImageRepository;
use App\Repository\KeyWordRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{   
    private Admin $admin;

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute("app_admin_panel");
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute("app_login");
    }

    #[Route(path: '/check-password-code', name: 'app_check_password_code')]
    public function checkPasswordCode(CsrfTokenManagerInterface $csrfTokenManager, SessionInterface $session, MailerInterface $mailer, AdminRepository $adminRepository, Request $request, EntityManagerInterface $manager, ImageRepository $imgRepository): Response
    {   
        
        if(!$session->isStarted()) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomCode = '';
            for ($i = 0; $i < 4; $i++) {
                $randomCode .= $characters[rand(0, strlen($characters) - 1)];
            }


            $email = new Email()
                ->from('stage@sandbox8231820d12ae446d8606a873463dc59e.mailgun.org')
                ->to($adminRepository->findOneBy([])->getEmail())
                ->subject('Changement de mot de passe')
                ->text('')
                ->html("<p>Votre code de changement de mot de passe</p> <h1>$randomCode</h1>");
            
            $mailer->send($email);

            $csrfToken = $csrfTokenManager->getToken('password_change')->getValue();
            $session->set('csrf_token', $csrfToken);
            $session->set('csrf_token_timestamp', time());
            $session->set("change_password_code", $randomCode);
            

            return $this->render("security/changePassword/check-code.html.twig", [
                "crsfToken" => $csrfToken,
            ]);

        }

        return $this->render("errors/error-404.html.twig");
    }

    #[Route(path: '/changePassword', name: 'app_change_password')]
    public function changePaswword(SessionInterface $session, MailerInterface $mailer, AdminRepository $adminRepository, Request $request, EntityManagerInterface $manager, ImageRepository $imgRepository): Response
    {
        if($session->isStarted()) {
            if( (time() - $session->get('csrf_token_timestamp')) <= 6000) {
                if($request->get('change_password_code') == $session->get("change_password_code")) {

                        $admin = $adminRepository->findBy([]);

                        $form = $this->createForm(ChangePaswwordType::class, $admin);
                        $form->handleRequest($request);

                        if ($form->isSubmitted() && $form->isValid() && $request->get('csrf_token') == $session->get("csrf_token")) {
                            
                            $manager->persist($admin);
                            $manager->flush();

                            $this->addFlash('success', "Le mot de passe a été changé avec succes");
                            return $this->redirectToRoute('app_admin_panel');
                        }

                        return $this->render("security/changePassword/change-password.html.twig", [
                            "crsfToken" => $csrfToken,
                            "form" => $form,
                        ]);
                }

                return $this->render("errors/error-403.html.twig");
            }
            $session->clear();
        }
    

        return $this->render("errors/error-403.html.twig");
    }


    #[Route(path: '/admin-panel', name: 'app_admin_panel')]
    public function showAdminPanel(Request $request, EntityManagerInterface $manager, ImageRepository $imgRepository): Response
    {

        if ($this->getUser()) {
            $image = new Image();

            $form = $this->createForm(ImageType::class, $image);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $manager->persist($image);
                $manager->flush();

                $this->addFlash('success', "L'image a été chargée avec succes");
                return $this->redirectToRoute('app_admin_panel');
            }

            $imgs = $imgRepository->findAll();
            return $this->render("admin-panel/admin-panel.html.twig", [
                "imgs" => $imgs,
                'form' => $form,
            ]);
        }

        return $this->render("errors/error-403.html.twig");
    }

    #[Route(path: '/signup', name: 'app_signup')]
    public function signup(CsrfTokenManagerInterface $csrfTokenManager, SessionInterface $session, UrlGeneratorInterface $urlGenerator, MailerInterface $mailer, AdminRepository $adminRepository, Request $request, EntityManagerInterface $manager, ImageRepository $imgRepository): Response
    {   
            $confirmationCsrfToken = $csrfTokenManager->getToken('confirm_email_button')->getValue();

            $confirmationUrl = $urlGenerator->generate(
                'app_confirm_email',         
                ['token' => $confirmationCsrfToken],          
                UrlGeneratorInterface::ABSOLUTE_URL  
            );

            $this->admin = new Admin();

            $form = $this->createForm(SignUpType::class, $this->admin);
            $form->handleRequest($request);

            $csrfToken = $csrfTokenManager->getToken('csrf_token')->getValue();

            $session->set("confirmation_csrf_token" ,$confirmationCsrfToken);

            $session->set("csrf_token" ,$csrfToken);

            if ($form->isSubmitted() && $form->isValid()) {
                $html = $this->renderView('security/confirmEmail/confirmation-email.html.twig', [
                    'confirmationUrl' => $confirmationUrl
                ]);

                $email = new Email()
                ->from('stage@sandbox8231820d12ae446d8606a873463dc59e.mailgun.org')
                ->to($this->admin->getEmail())
                ->subject("Confirmation d'email")
                ->html($html);
            
                $mailer->send($email);

                $session->set('csrf_token_timestamp', time());
                $session->set("confirm_email_csrf_token", $confirmationCsrfToken);

                $this->addFlash('success', "Le courrier de confirmation a été envoyé sur votre adresse mail indiqué");

                return $this->render("security/confirmEmail/confirm-email.html.twig");
            }
            

            return $this->render("security/signup.html.twig", [
                "form" => $form,
                "csrfToken" => $csrfToken,
            ]);

        return $this->render("errors/error-403.html.twig");
    }

    #[Route('/confirm-email/{token}', name: 'app_confirm_email')]
    public function confirmEmail(EntityManagerInterface $manager, string $token, SessionInterface $session): Response
    {
        if ($session->isStarted() && time() - $session->get('csrf_token_timestamp') <= 6000) {
            
            $manager->persist($this->admin);
            $manager-flush();

            $session->clear();

            return $this->redirectToRoute('app_admin_panel');
        }

        else if ($session->isStarted() && time() - $session->get('csrf_token_timestamp') > 6000) {
            $this->admin = null;
            $session->clear();
        }

        return $this->render("errors/error-403.html.twig");

    }

    #[Route(path: '/delete-portfolio-img/{id}', name: 'app_delete_portfolio_img')]
    public function deletePortfolioImg(Image $img, EntityManagerInterface $manager): Response
    {

        if ($this->getUser()) {
            $manager->remove($img);
            $manager->flush();

            return $this->redirectToRoute("app_admin_panel");
        }

        return $this->render("errors/error-403.html.twig");
    }
}
