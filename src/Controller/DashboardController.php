<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use App\Form\{DeleteAccountFormType, ImageFormType, UserFormType, ChangePasswordFormType};
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/', requirements: ['_locale' => 'en|pl'])]
class DashboardController extends AbstractController
{
    #[Route('/{_locale}/dashboard', name: 'app_dashboard')]
    public function index(string $_locale = 'en'): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/{_locale}/dashboard/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $imageForm = $this->imageForm($request, $entityManager);

        $userForm = $this->userForm($request);

        $passwordForm = $this->passwordForm($request);

        $deleteAccountForm = $this->deleteAccountForm($request);

        return $this->render('dashboard/edit.html.twig', [
            'imageForm'         => $imageForm,
            'userForm'          => $userForm,
            'passwordForm'      => $passwordForm,
            'deleteAccountForm' => $deleteAccountForm
        ]);
    }

    private function imageForm(Request $request, $entityManager): FormInterface
    {
        $image = new Image();
        $imageForm = $this->createForm(ImageFormType::class, $image);
        $imageForm->handleRequest($request);

        $user = $this->getUser();

        if ($imageForm->isSubmitted() && $imageForm->isValid()) {

            $image->setPath($imageForm->get('imageFile')->getData()->getClientOriginalName());

            if ($user->getImage()) {
                $oldImage = $entityManager->getRepository(Image::class)->find($user->getImage()->getId());
                $entityManager->remove($oldImage);
            }

            $user->setImage($image);
            $entityManager->persist($image);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('status-image', 'image-update');

            $this->redirectToRoute('app_profile');
        }

        return $imageForm;
    }

    private function userForm(Request $request): FormInterface
    {
        $user = $this->getUser();
        $userForm = $this->createForm(UserFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $user = $userForm->getData();

            $this->addFlash('status-profile-information', 'user-update');

            $this->redirectToRoute('app_profile');
        }

        return $userForm;
    }

    private function passwordForm(Request $request): FormInterface
    {
        $user = $this->getUser();
        $passwordForm = $this->createForm(ChangePasswordFormType::class, $user);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

            $user = $passwordForm->getData();

            $this->addFlash('status-password', 'password-changes');

            $this->redirectToRoute('app_profile');
        }

        return $passwordForm;
    }

    private function deleteAccountForm(Request $request): FormInterface
    {
        $user = $this->getUser();
        $deleteAccountForm = $this->createForm(DeleteAccountFormType::class, $user);
        $deleteAccountForm->handleRequest($request);

        if ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {

            $user = $deleteAccountForm->getData();

            $this->redirectToRoute('app_profile');
        }

        return $deleteAccountForm;
    }
}
