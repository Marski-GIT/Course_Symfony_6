<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    #[Route('/api/post/new', methods: ['POST'])]
    public function post(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $data = json_decode($request->getContent(), true);

            if (!$data && !$data['title'] && !$data['content']) {
                throw new \Exception('data not valid');
            }

            $post = new Post();
            $post->setTitle($data['title']);
            $post->setContent($data['content']);
            $post->setUser($this->getUser());
            $post->setCreatedAt(new \DateTimeImmutable('now'));
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->json([
                'message' => 'Post added!'
            ], 201);

        } catch (\Exception $e) {
            return $this->json([
                'error'   => 'Post not added!',
                'message' => $e->getMessage()
            ], 400);
        }

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path'    => 'src/Controller/Api/ApiController.php',
        ]);
    }
}
