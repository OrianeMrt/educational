<?php

namespace App\Controller\Back\Crud\User;

use App\Entity\Account\User;
use App\Form\Back\User\EditType;
use App\Form\Back\User\RegisterType;
use App\Repository\Account\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    /**
     * liste des utilisateurs
     * @Route("/admin/users", name="admin_users")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository)
    {
        return $this->render('Back/Crud/User/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * afficher le profil de utilisateur
     * @Route("/admin/users/add", name="admin_users_add")
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function add(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):

            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            if ($user->getRole() === "Administrateur")
                $user->setRoles(['ROLE_ADMIN']);
            else
                $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "<i class=\"fas fa-check-circle\"></i> Création d'un utilisateur réalisé avec succès"
            );

            return $this->redirectToRoute('admin_users');

        endif;

        return $this->render('Back/Crud/User/add.html.twig', [
            'form' => $form->createView(),
            'title' => "Ajouter un utilisateur"
        ]);

    }

    /**
     * afficher le profil de utilisateur
     * @Route("/admin/users/{id}", name="admin_users_id")
     * @param User $user
     * @return Response
     */
    public function id(User $user)
    {
        return $this->render('Back/Crud/User/id.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * edit le compte
     * @Route("/admin/users/edit/{id}", name="admin_user_edit_id")
     * @param User $user
     * @param Request $request
     * @param ObjectManager $manager
     * @return RedirectResponse|Response
     */
    public function edit(User $user ,Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(EditType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()):

            if ($user->getRole() === "Administrateur")
                $user->setRoles(['ROLE_ADMIN']);
            else
                $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "<i class=\"fas fa-check-circle\"></i> Le compte a été modifié"
            );

            return $this->redirectToRoute('admin_users');

        endif;

        return $this->render('Back/Crud/User/add.html.twig', [
            'form' => $form->createView(),
            'title' => "Editer un utilisateur"
        ]);

    }

    /**
     * delete le compte
     * @Route("/admin/users/delete/{id}", name="admin_user_delete_id")
     * @param User $user
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(User $user, ObjectManager $manager)
    {
        $manager->remove($user);
        $manager->flush();
        return $this->render('Back/_partials/alert/deleteUser.html.twig');
    }

}
