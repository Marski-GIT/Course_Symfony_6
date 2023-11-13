<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', requirements: ['_locale' => 'en|pl'])]
class DashboardController extends AbstractController
{
    #[Route('/{_locale}/dashboard', name: 'app_dashboard')]
    public function index(string $_locale = 'en'): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/{_locale}/dashboard/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('dashboard/edit.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
