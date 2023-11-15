<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use App\Form\{ImageFormType, UserFormType};

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
    public function profile(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $image = new Image();
        $imageForm = $this->createForm(ImageFormType::class, $image);
        $imageForm->handleRequest($request);

        $this->imageForm($imageForm);

        $user = $this->getUser();
        $userForm = $this->createForm(UserFormType::class, $user);
        $userForm->handleRequest($request);

        $this->userForm($userForm);

        return $this->render('dashboard/edit.html.twig', [
            'imageForm' => $imageForm,
            'userForm'  => $userForm
        ]);
    }

    private function imageForm($imageForm): void
    {
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {

            $image = $imageForm->getData();

            $this->redirectToRoute('app_profile');
        }
    }

    private function userForm($userForm): void
    {
        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $user = $userForm->getData();

            $this->redirectToRoute('app_profile');
        }

    }
}
