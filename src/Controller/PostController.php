<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', requirements: ['_locale' => 'en|pl'])]
class PostController extends AbstractController
{
    #[Route('/{_locale}', name: 'posts.index', methods: ['GET'])]
    public function index(string $_locale = 'en'): Response
    {
        return $this->render('post/index.html.twig');
    }

    #[Route('/{_locale}/post/new', name: 'posts.new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('post/new.html.twig');
    }

    #[Route('/{_locale}/post/{id}', name: 'posts.show', methods: ['GET'])]
    public function show($id): Response
    {
        return $this->render('post/show.html.twig');
    }

    #[Route('/{_locale}/post/{id}/edit', name: 'posts.edit', methods: ['GET', 'POST'])]
    public function edit($id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // return $this->redirectToRoute('posts.index');
        return $this->render('post/edit.html.twig');
    }

    #[Route('/{_locale}/post/{id}/delete', name: 'posts.delete', methods: ['POST'])]
    public function delete($id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return new Response('delete post from the database');
    }

    #[Route('/{_locale}/posts/user/{id}', name: 'posts.user', methods: ['GET'])]
    public function user($id): Response
    {
        return $this->render('post/index.html.twig');
    }

    #[Route('/{_locale}/toggleFollow/{user}', name: 'toggleFollow', methods: ['GET'])]
    public function toggleFollow($user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return new Response('logic for toggling like/dislike');
    }
}
