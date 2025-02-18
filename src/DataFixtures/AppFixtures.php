<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Lists;
use App\Entity\Tasks;
use App\Entity\Users;
use App\Entity\Boards;
use App\Service\IdGenerator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private IdGenerator $idGenerator
    ) {}

    public function load(
        ObjectManager $manager
    ): void {
        $faker = Factory::create('fr_BE');

        /**
         ** Users
         */
        $users = [];

        $admin = new Users();
        $admin->setId($this->idGenerator->generateId('USR'));
        $admin->setUsername('admin');
        $admin->setEmail('admin@localhost');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setIsActive(true);
        $admin->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d H:i:s')));
        $admin->setUpdatedAt(
            rand(0, 1) ? new \DateTimeImmutable($faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s')) : null
        );

        $users[] = $admin;
        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user = new Users();
            $user->setId($this->idGenerator->generateId('USR'));
            $user->setUsername($faker->userName);
            $user->setEmail($faker->email);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setIsActive(rand(0, 1));
            $user->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d H:i:s')));
            $user->setUpdatedAt(
                rand(0, 1) ? new \DateTimeImmutable($faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s')) : null
            );

            $users[] = $user;
            $manager->persist($user);
        }

        /**
         ** Boards
         */
        $boards = [];

        for ($i = 0; $i < 20; $i++) {
            $board = new Boards();
            $board->setId($this->idGenerator->generateId('BRD'));
            $board->setName($faker->sentence(rand(3, 6)));
            $board->setDescription(
                rand(0, 1) ? $faker->sentence : null
            );
            $board->setOwner($users[rand(0, count($users) - 1)]);
            $board->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d H:i:s')));
            $board->setUpdatedAt(
                rand(0, 1) ? new \DateTimeImmutable($faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s')) : null
            );

            $boards[] = $board;
            $manager->persist($board);
        }

        /**
         ** Lists
         */
        $lists = [];

        for ($i = 0; $i < 20; $i++) {
            $list = new Lists();
            $list->setId($this->idGenerator->generateId('LST'));
            $list->setTitle($faker->sentence(rand(3, 6)));
            $list->setBoard($boards[rand(0, count($boards) - 1)]);
            $list->setPosition(0);
            $list->setIsCompleted(rand(0, 1));
            $list->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d H:i:s')));
            $list->setUpdatedAt(
                rand(0, 1) ? new \DateTimeImmutable($faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s')) : null
            );

            $lists[] = $list;
            $manager->persist($list);
        }

        /**
         ** Tasks
         */
        $tasks = [];

        for ($i = 0; $i < 100; $i++) {
            $task = new Tasks();
            $task->setId($this->idGenerator->generateId('TSK'));
            $task->setTitle($faker->sentence(rand(3, 6)));
            $task->setDescription(
                rand(0, 1) ? $faker->sentence : null
            );
            $task->setList($lists[rand(0, count($lists) - 1)]);
            $task->setPosition(0);
            $task->setIsCompleted(rand(0, 1));
            $task->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d H:i:s')));
            $task->setUpdatedAt(
                rand(0, 1) ? new \DateTimeImmutable($faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s')) : null
            );

            $tasks[] = $task;
            $manager->persist($task);
        }

        $manager->flush();
    }
}
