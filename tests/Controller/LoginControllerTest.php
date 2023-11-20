<?php

namespace App\Tests\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class LoginControllerTest extends WebTestCase
{
    /**
     * @throws Exception
     */
    public function testLogin(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('iwisoky@gmail.com');

        $client->loginUser($testUser);

        $client->request('GET', 'pl/dashboard');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('', 'iwisoky@gmail.com');
    }
}
