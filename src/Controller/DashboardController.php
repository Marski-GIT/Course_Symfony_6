<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use App\Form\{DeleteAccountFormType, ImageFormType, UserFormType, ChangePasswordFormType};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

//use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Services\ImageUploader;

#[Route('/', requirements: ['_locale' => 'en|pl'])]
class DashboardController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private Request $request;
    private null|UserInterface $userProfile;
    private ImageUploader $imageUploader;

    #[Route('/{_locale}/dashboard', name: 'app_dashboard')]
    public function index(string $_locale = 'en'): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/{_locale}/dashboard/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager, Security $security, ImageUploader $imageUploader): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->imageUploader = $imageUploader;
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

            $imageFile = $imageForm->get('imageFile')->getData();
            if ($imageFile) {

                if ($this->userProfile->getImage()?->getPath()) {

                    $oldImage = $this->entityManager->getRepository(Image::class)->find($this->userProfile->getImage()->getId());

                    $fileName = $this->getParameter('images_directory') . '/' . $this->userProfile->getImage()->getPath();

                    if (file_exists($fileName)) {
                        unlink($fileName);
                    }

                    $this->userProfile->setImage(null);
                    $this->entityManager->remove($oldImage);
                }

                $newFileName = $this->imageUploader->upload($imageFile);

                $image->setPath($newFileName);

                $this->userProfile->setImage($image);
                $this->entityManager->persist($image);
                $this->entityManager->persist($this->userProfile);
                $this->entityManager->flush();

                $this->addFlash('status-image', 'image-update');

                $this->redirectToRoute('app_profile');
            }
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
        $passwordForm = $this->createForm(ChangePasswordFormType::class, $this->userProfile);
        $passwordForm->handleRequest($this->request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

            $this->entityManager->persist($this->userProfile);
            $this->entityManager->flush();

            $this->addFlash('status-password', 'password-changes');

            $this->redirectToRoute('app_profile');
        }

        return $passwordForm;
    }
}
