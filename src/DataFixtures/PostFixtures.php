<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PostFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 6; $i++) {

            $user = new User();
            $email = $faker->email;
            $user->setEmail($email);
            $user->setName($faker->name);
            $password = $this->hasher->hashPassword($user, $email);
            $user->setPassword($password);

            $manager->persist($user);

            for ($j = 1; $j <= 10; $j++) {
                $post = new Post();
                $post->setTitle($faker->sentence(10));
                $post->setContent($faker->text(3000));

                $number = $faker->numberBetween(-100, -2) . ' days';

                $post->setCreatedAt(new DateTimeImmutable($number));
                $post->setUser($user);

                $manager->persist($post);
            }

        }

        $manager->flush();
    }
}
