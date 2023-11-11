<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'posts.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('post/index.html.twig');
    }

    #[Route('/post/new', name: 'posts.new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->render('post/new.html.twig');
    }

    #[Route('/post/show/{id}', name: 'posts.show', methods: ['GET'])]
    public function show($id): Response
    {
        return $this->render('post/show.html.twig');
    }

    #[Route('/post/{id}/edit', name: 'posts.edit', methods: ['GET', 'POST'])]
    public function edit($id): Response
    {
        // return $this->redirectToRoute('posts.index');
        return $this->render('post/edit.html.twig');
    }

    #[Route('/post/{id}/delete', name: 'posts.delete', methods: ['POST'])]
    public function delete($id): Response
    {
        return new Response('delete post from the database');
    }

    #[Route('/posts/user/{id}', name: 'posts.user', methods: ['GET'])]
    public function user($id): Response
    {
        return $this->render('post/index.html.twig');
    }

    #[Route('/toggleFollow/{user}', name: 'toggleFollow', methods: ['GET'])]
    public function toggleFollow($user): Response
    {
        return new Response('logic for toggling like/dislike');
    }
}
