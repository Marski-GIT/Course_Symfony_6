<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{RedirectResponse, Response, Request};
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use App\Form\{DeleteAccountFormType, ImageFormType, UserFormType, ChangePasswordFormType};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/', requirements: ['_locale' => 'en|pl'])]
class DashboardController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private Request $request;
    private null|UserInterface $userProfile;

    #[Route('/{_locale}/dashboard', name: 'app_dashboard')]
    public function index(string $_locale = 'en'): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/{_locale}/dashboard/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->userProfile = $this->getUser();

        $imageForm = $this->imageForm();

        $userForm = $this->userForm();

        $passwordForm = $this->passwordForm();

        $deleteAccountForm = $this->createForm(DeleteAccountFormType::class, $this->userProfile);
        $deleteAccountForm->handleRequest($this->request);

        if ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {

            $security->logout(false);

            $this->entityManager->remove($this->userProfile);
            $this->entityManager->flush();
            $this->request->getSession()->invalidate();

            return $this->redirectToRoute('posts.index');
        }

        return $this->render('dashboard/edit.html.twig', [
            'imageForm'         => $imageForm,
            'userForm'          => $userForm,
            'passwordForm'      => $passwordForm,
            'deleteAccountForm' => $deleteAccountForm
        ]);
    }

    private function imageForm(): FormInterface
    {
        $image = new Image();
        $imageForm = $this->createForm(ImageFormType::class, $image);
        $imageForm->handleRequest($this->request);


        if ($imageForm->isSubmitted() && $imageForm->isValid()) {

            $image->setPath($imageForm->get('imageFile')->getData()->getClientOriginalName());

            if ($this->userProfile->getImage()) {
                $oldImage = $this->entityManager->getRepository(Image::class)->find($this->userProfile->getImage()->getId());
                $this->entityManager->remove($oldImage);
            }

            $this->userProfile->setImage($image);
            $this->entityManager->persist($image);
            $this->entityManager->persist($this->userProfile);
            $this->entityManager->flush();

            $this->addFlash('status-image', 'image-update');

            $this->redirectToRoute('app_profile');
        }

        return $imageForm;
    }

    private function userForm(): FormInterface
    {
        $userForm = $this->createForm(UserFormType::class, $this->userProfile);
        $userForm->handleRequest($this->request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $this->entityManager->persist($this->userProfile);
            $this->entityManager->flush();

            $this->addFlash('status-profile-information', 'user-update');

            $this->redirectToRoute('app_profile');
        }

        return $userForm;
    }

    private function passwordForm(): FormInterface
    {
        $user = $this->getUser();
        $passwordForm = $this->createForm(ChangePasswordFormType::class, $this->userProfile);
        $passwordForm->handleRequest($this->request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

            $user = $passwordForm->getData();

            $this->addFlash('status-password', 'password-changes');

            $this->redirectToRoute('app_profile');
        }

        return $passwordForm;
    }
}
