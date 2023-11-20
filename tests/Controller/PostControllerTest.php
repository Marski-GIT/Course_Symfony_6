<?php

namespace App\Tests\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Post;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostRepository;
use App\Services\TestCalculator;

class PostControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'SymfonyMicroBlog');
    }

    public function testSeeContentEn(): void
    {
        $client = static::createClient();
        $client->request('GET', 'en/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Registration');
    }

    public function testSeeContentPL(): void
    {
        $client = static::createClient();
        $client->request('GET', 'pl/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Rejestracja');
    }

    /**
     * 1. Tworzenie klienta.
     *      - logowanie użytkownika,
     * 2. Tworzenie posta.
     * 3. Przekierowanie na stronę edycji.
     *      - sprawdzenie, czy można edytować post,
     *      - czy w polu input 'title' znajduje się tytuł edytowanego posta,
     *
     * @throws Exception
     */
    public function testCreatePost(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('rkozey@hahn.com');

        $client->loginUser($testUser);
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $testPost = new Post();
        $testPost->setUser($testUser);
        $testPost->setCreatedAt(new \DateTimeImmutable('now'));
        $testPost->setTitle('Test Post');
        $testPost->setContent('Test content post.');

        $entityManager->persist($testPost);
        $entityManager->flush();

        $client->request('GET', 'en/post/' . $testPost->getId() . '/edit');
        $this->assertResponseIsSuccessful();
        $this->assertInputValueSame('post_form[title]', 'Test Post');
    }

    /**
     * @throws \Exception
     */
    public function testDataBaseCount(): void
    {
        $postRepository = static::getContainer()->get(PostRepository::class);

        $totalPost = $postRepository->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertEquals(67, $totalPost);
    }

    /**
     * @throws Exception
     */
    public function testEditProfile(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('rkozey@hahn.com');

        $client->loginUser($testUser);
        $crawler = $client->request('GET', 'en/dashboard/profile');

        $this->assertSelectorTextContains('h2', 'Profile Information');

        $form = $crawler->selectButton('Save')->form([
            'user_form[name]' => 'new name'
        ]);

        $client->submit($form);

        $us = $userRepository->findOneBy(['name' => 'new name']);

        $this->assertNotNull($us);
        $this->assertSame('new name', $us->getName());
    }

//    public function testApiAddUser(): void
//    {
//        $client = static::createClient();
//        $client->request('POST', 'api/register', [], [], ['CONTENT_TYPE' => 'application/json',
//            '{
//            "name": "name@example.pl",
//            "email": "name@example.pl",
//            "password": "name@example.pl"
//            }']);
//
//        $this->assertResponseIsSuccessful();
//    }

//    public function testApiUserToken(): void
//    {
//        $client = static::createClient();
//
//        $client->request('POST', 'api/login_check', [], [], ['CONTENT_TYPE' => 'application/json',
//            '{
//            "name": "omclaughlin@koepp.biz",
//            "password": "omclaughlin@koepp.biz"
//            }']);
//
//        $response = $client->getResponse();
//
//        dump(json_decode($response->getContent(), true)['token']);
//    }

    public function testMock(): void
    {

        $client = static::createClient();

        $serviceMock = $this->createMock(TestCalculator::class);

        $serviceMock->method('add')->willReturn(100);

        $client->getContainer()->set(TestCalculator::class, $serviceMock);
        $crawler = $client->request('GET', '/test');

        $this->assertStringContainsString(100, $crawler->html());


    }
}
