<?php

namespace App\Service;

use App\DTO\BoardsDTO;
use App\Entity\Boards;
use App\Repository\BoardsRepository;

class BoardsService
{
  public function __construct(
    private IdGenerator $idGenerator,
    private BoardsRepository $boardsRepository
  ) {}

  public function index(): array
  {
    $boards = $this->boardsRepository->findAll();

    if (empty($boards)) {
      return [
        'status' => [
          'code' => 404,
          'message' => 'Boards not found'
        ]
      ];
    }

    $boardsDTO = [];

    foreach ($boards as $board) {
      $boardsDTO[] = new BoardsDTO(
        "/api/boards/" . $board->getId(),
        $board->getName(),
        $board->getDescription(),
        [
          "id" => "/api/users/" . $board->getOwner()->getId(),
          "username" => $board->getOwner()->getUsername()
        ],
        $board->getCreatedAt(),
        $board->getUpdatedAt()
      );
    }

    return $boardsDTO;
  }

  public function show(string $id): BoardsDTO
  {
    $board = $this->boardsRepository->find($id);

    if (empty($board)) {
      throw new \Exception("Board not found");
    }

    return new BoardsDTO(
      $board->getId(),
      $board->getName(),
      $board->getDescription(),
      [
        "id" => "/api/users/" . $board->getOwner()->getId(),
        "username" => $board->getOwner()->getUsername()
      ],
      $board->getCreatedAt(),
      $board->getUpdatedAt()
    );
  }

  public function create(string $name, string $description, string $ownerId): BoardsDTO
  {
    $board = new Boards();
    $board->setId($this->idGenerator->generateId('BRD'));
    $board->setName($name);
    $board->setDescription($description);
    $board->setOwner($this->boardsRepository->find($ownerId));
    $board->setCreatedAt(new \DateTimeImmutable());

    $this->boardsRepository->getEntityManager()->persist($board);
    $this->boardsRepository->getEntityManager()->flush();

    return new BoardsDTO(
      $board->getId(),
      $board->getName(),
      $board->getDescription(),
      [
        "id" => "/api/users/" . $board->getOwner()->getId(),
        "username" => $board->getOwner()->getUsername()
      ],
      $board->getCreatedAt(),
      $board->getUpdatedAt()
    );
  }

  public function update(string $id, string $name, string $description): BoardsDTO
  {
    $board = $this->boardsRepository->find($id);

    if (empty($board)) {
      throw new \Exception("Board not found");
    }

    $board->setName($name);
    $board->setDescription($description);
    $board->setUpdatedAt(new \DateTimeImmutable());

    $this->boardsRepository->getEntityManager()->persist($board);
    $this->boardsRepository->getEntityManager()->flush();

    return new BoardsDTO(
      $board->getId(),
      $board->getName(),
      $board->getDescription(),
      [
        "id" => "/api/users/" . $board->getOwner()->getId(),
        "username" => $board->getOwner()->getUsername()
      ],
      $board->getCreatedAt(),
      $board->getUpdatedAt()
    );
  }

  public function delete(string $id): void
  {
    $board = $this->boardsRepository->find($id);

    if (empty($board)) {
      throw new \Exception("Board not found");
    }

    $this->boardsRepository->delete($board);
  }
}
