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
        return new Response(
            '<h1>List of all posts here <br> Named route that we will use in the view: ' . $this->generateUrl('posts.index') . '</h1>'
        );
    }

    #[Route('/posts/user/{id}', name: 'posts.user', methods: ['GET'])]
    public function user($id): Response
    {
        return new Response(
            '<h1>List of posts from specific user <br> User id: ' . $id . '<br> Named route that we will use in the view: ' . $this->generateUrl('posts.user', ['id' => $id]) . '</h1>'
        );
    }

    #[Route('/toggleFollow/{user}', name: 'toggleFollow', methods: ['GET'])]
    public function toggleFollow($user): Response
    {
        return new Response(
            '<h1>Togle like/dislike<br> User id: ' . $user . '<br> Named route that we will use in the view: ' . $this->generateUrl('toggleFollow', ['user' => $user]) . '</h1>'
        );
    }
}
