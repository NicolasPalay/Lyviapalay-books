<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\SetPassWordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/reset/password', name: 'reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        if ($request->get('email')){
            $user = $this ->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if ($user){
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();

                //envoi d'un email à l'utilisateur avec un lien lui permettant de mettre à jour son mot de passe
               $url = $this->generateUrl('update_password',[
                   'token' => $resetPassword->getToken()]);
                $mail = new Mail();
                $content = "bonjour " . $user->getFirstname() . "<br> Vous avez dmandez à réinitialiser votre mot de passe sur le site de Lyvia Palay<br><br>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='".$url."'>mettre à jour votre mot de passe</a>";
                $mail->send($user->getEmail(),$user->getFirstname(). ' ' . $user->getLastName(), 'réinitialiser votre mot de passe de la boutique de Lyvia Palay', $content);

            }
        }

        return $this->render('reset_password/index.html.twig', [

        ]);
    }

    #[Route('/modifier/password/{token}', name: 'update_password')]
    public function update($token,Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        $resetPassword = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        if (!$resetPassword){
            return $this->redirectToRoute('reset_password');
        }
       ;
        $now = new \DateTimeImmutable();
        if ($now >  $resetPassword->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de la renouveller');
            return $this->redirectToRoute('reset_password');
        }
        $form = $this->createForm(setPasswordType::class);
        $form->handleRequest($request);
        if ($form ->isSubmitted() && $form->isValid()){

            $resetPassword->getuser()->setPassword(
                $userPasswordHasher->hashPassword(
                    $resetPassword->getuser(),
                    $form->get('plainPassword')->getData()
                )
            );
            $this->entityManager->flush();
            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),
            'token' => $token
        ]);
    }
}
