<?php

namespace App\Service;

use App\DTO\UsersDTO;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersService
{
  public function __construct(
    private IdGenerator $idGenerator,
    private UsersRepository $usersRepository,
    private UserPasswordHasherInterface $passwordHasher
  ) {}

  public function index(): array
  {
    $users = $this->usersRepository->findAll();

    if (empty($users)) {
      return [
        'status' => [
          'code' => 404,
          'message' => 'Users not found'
        ]
      ];
    }


    $usersDTO = [];

    foreach ($users as $user) {
      $usersDTO[] = new UsersDTO(
        "/api/users/" . $user->getId(),
        $user->getUsername(),
        $user->getEmail(),
        $user->getPassword(),
        $user->getRoles(),
        $user->isActive(),
        $user->getCreatedAt(),
        $user->getUpdatedAt()
      );
    }

    return $usersDTO;
  }

  public function show(string $id): UsersDTO
  {
    $user = $this->usersRepository->find($id);

    if (empty($user)) {
      throw new \Exception("User not found.");
    }

    return new UsersDTO(
      $user->getId(),
      $user->getUsername(),
      $user->getEmail(),
      $user->getPassword(),
      $user->getRoles(),
      $user->isActive(),
      $user->getCreatedAt(),
      $user->getUpdatedAt()
    );
  }

  public function create(
    string $username,
    string $email,
    string $password,
    array $roles,
    bool $isActive
  ): UsersDTO {
    $id = $this->idGenerator->generateId('USR');
    $hashedPassword = $this->passwordHasher->hashPassword(new Users(), $password);

    $user = new Users();
    $user->setId($id);
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setPassword($hashedPassword);
    $user->setRoles($roles);
    $user->setIsActive($isActive);
    $user->setCreatedAt(new \DateTimeImmutable());

    $this->usersRepository->getEntityManager()->persist($user);
    $this->usersRepository->getEntityManager()->flush();

    return new UsersDTO(
      $id,
      $username,
      $email,
      $hashedPassword,
      $roles,
      $isActive,
      $user->getCreatedAt(),
      null
    );
  }

  public function update(
    string $id,
    string $username,
    string $email,
    string $password,
    array $roles,
    bool $isActive
  ): UsersDTO {
    $user = $this->usersRepository->find($id);

    if (empty($user)) {
      throw new \Exception("User not found.");
    }

    $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

    $user->setUsername($username);
    $user->setEmail($email);
    $user->setPassword($hashedPassword);
    $user->setRoles($roles);
    $user->setIsActive($isActive);
    $user->setUpdatedAt(new \DateTimeImmutable());

    $this->usersRepository->save($user);

    return new UsersDTO(
      $user->getId(),
      $user->getUsername(),
      $user->getEmail(),
      $user->getPassword(),
      $user->getRoles(),
      $user->isActive(),
      $user->getCreatedAt(),
      $user->getUpdatedAt()
    );
  }

  public function delete(string $id): void
  {
    $user = $this->usersRepository->find($id);

    if (empty($user)) {
      throw new \Exception("User not found.");
    }

    $this->usersRepository->delete($user);
  }
}
