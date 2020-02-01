<?php


namespace App\Controller\Front\Account;

use App\Entity\Account\User;
use App\Form\Account\RegisterType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * connexion
     * @Route("/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('Front/Account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }
    /**
     * deconnexion
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout()
    {
        // void
    }

    /**
     * inscription
     * @Route("/register", name="account_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $manager
     * @return RedirectResponse|Response
     */
    public function register(Request $request, UserPasswordEncoderInterface  $encoder, ObjectManager $manager)
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):

            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "<i class=\"fas fa-check-circle\"></i> Votre compte a bien été crée !"
            );

            return $this->redirectToRoute('homepage');
        endif;

        return $this->render('Front/Account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * account
     * @Route("/account", name="account")
     * @return Response
     */
    public function account()
    {

        return $this->render('Front/Account/account.html.twig', [
            'user' => $this->getUser()
        ]);
    }


}
