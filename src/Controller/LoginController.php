<?php declare(strict_types=1);

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/', requirements: ['_locale' => 'en|pl'])]
class LoginController extends AbstractController
{
    #[Route('/{_locale}/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username'   => $lastUserName,
            'error'           => $error
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/{_locale}/logout', name: 'app_logout')]
    public function logout()
    {
        throw new Exception("Don't forget to active logout in security.yaml");
    }
}
