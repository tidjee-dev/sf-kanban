<?php

namespace App\Service;

use App\DTO\ListsDTO;
use App\Entity\Lists;
use App\Repository\ListsRepository;

class ListsService
{
  public function __construct(
    private IdGenerator $idGenerator,
    private ListsRepository $listsRepository
  ) {}

  public function getListByBoardId(string $boardId): array
  {
    $lists = $this->listsRepository->getListsByBoardId($boardId);

    // dd($lists);

    if (empty($lists)) {
      return [
        'status' => [
          'code' => 404,
          'message' => 'Lists not found'
        ]
      ];
    }

    $listsDTO = [];

    foreach ($lists as $list) {
      $listsDTO[] = new ListsDTO(
        "/api/lists/" . $list->getId(),
        [
          "id" => "/api/boards/" . $list->getBoard()->getId(),
          "name" => $list->getBoard()->getName()
        ],
        $list->getTitle(),
        $list->getPosition(),
        $list->getCreatedAt(),
        $list->getUpdatedAt()
      );
    }

    return $listsDTO;
  }

  public function create(string $title, int $position, string $boardId): ListsDTO
  {
    $list = new Lists();
    $list->setId($this->idGenerator->generateId('BRD'));
    $list->setBoard($this->listsRepository->find($boardId));
    $list->setTitle($title);
    $list->setPosition($position);
    $list->setCreatedAt(new \DateTimeImmutable());

    $this->listsRepository->getEntityManager()->persist($list);
    $this->listsRepository->getEntityManager()->flush();

    return new ListsDTO(
      $list->getId(),
      [
        "id" => "/api/boards/" . $list->getBoard()->getId(),
        "name" => $list->getBoard()->getName()
      ],
      $list->getTitle(),
      $list->getPosition(),
      $list->getCreatedAt(),
      $list->getUpdatedAt()
    );
  }

  public function update(string $id, string $title, int $position): ListsDTO
  {
    $list = $this->listsRepository->find($id);

    if (empty($list)) {
      throw new \Exception("List not found");
    }

    $list->setTitle($title);
    $list->setPosition($position);
    $list->setUpdatedAt(new \DateTimeImmutable());

    $this->listsRepository->getEntityManager()->persist($list);
    $this->listsRepository->getEntityManager()->flush();

    return new ListsDTO(
      $list->getId(),
      [
        "id" => "/api/boards/" . $list->getBoard()->getId(),
        "name" => $list->getBoard()->getName()
      ],
      $list->getTitle(),
      $list->getPosition(),
      $list->getCreatedAt(),
      $list->getUpdatedAt()
    );
  }

  public function delete(string $id): void
  {
    $list = $this->listsRepository->find($id);

    if (empty($list)) {
      throw new \Exception("List not found");
    }

    $this->listsRepository->delete($list);
  }
}
