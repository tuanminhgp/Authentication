<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Model\UserRegistrationFormModel;
use App\Form\UserRigistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error

        ]);
    }

    /**
     * @Route("/logout",name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('Logout');

    }

    /**
     * @Route("/register",name="app_register")
     */
    public function register(Request $request,UserPasswordEncoderInterface $passwordEncoder,GuardAuthenticatorHandler $guardHandler,LoginFormAuthenticator $formAuthenticator)
    {
        $form=$this->createForm(UserRigistrationFormType::class);
        $form->handleRequest($request);


        if($form->isSubmitted()&& $form->isValid()){
            /**@var UserRegistrationFormModel $userModel */
            $userModel= $form->getData();
            $user= new User();
            $user->setEmail($userModel->email);
            $user->setPassword($passwordEncoder->encodePassword(
               $user,
               $userModel->plainPassword

            ));

            if(true===$userModel->agreeTerms){
                $user->setAgreeTerms();
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );

        }
        return $this->render('security/register.html.twig',[
            'registrationForm'=>$form->createView()
        ]);

    }
}
