<?php

namespace App\Service;

use App\DTO\TasksDTO;
use App\Entity\Tasks;
use App\Repository\TasksRepository;

class TasksService
{
  public function __construct(
    private IdGenerator $idGenerator,
    private TasksRepository $tasksRepository
  ) {}

  public function getTasksByListId(string $listId): array
  {
    $tasks = $this->tasksRepository->getTasksByListId($listId);

    if (empty($tasks)) {
      throw new \Exception('No tasks found for the given list ID');
    }

    $tasksDTO = [];

    foreach ($tasks as $task) {
      $tasksDTO[] = new TasksDTO(
        "/api/tasks/" . $task->getId(),
        [
          "id" => "/api/lists/" . $task->getList()->getId(),
          "name" => $task->getList()->getTitle()
        ],
        $task->getTitle(),
        $task->getDescription(),
        $task->getPosition(),
        $task->isCompleted(),
        $task->getCreatedAt(),
        $task->getUpdatedAt()
      );
    }

    return $tasksDTO;
  }

  public function create(
    string $listId,
    string $title,
    string $description,
    int $position
  ): TasksDTO {
    $task = new Tasks();
    $task->setId($this->idGenerator->generateId('TSK'));
    $task->setList($this->tasksRepository->find($listId));
    $task->setTitle($title);
    $task->setDescription($description);
    $task->setPosition($position);
    $task->setIsCompleted(false);
    $task->setCreatedAt(new \DateTimeImmutable());

    $this->tasksRepository->getEntityManager()->persist($task);
    $this->tasksRepository->getEntityManager()->flush();

    return new TasksDTO(
      $task->getId(),
      [
        "id" => "/api/lists/" . $task->getList()->getId(),
        "name" => $task->getList()->getTitle()
      ],
      $task->getTitle(),
      $task->getDescription(),
      $task->getPosition(),
      $task->isCompleted(),
      $task->getCreatedAt(),
      $task->getUpdatedAt()
    );
  }

  public function update(
    string $id,
    string $title,
    string $description,
    int $position,
    bool $isCompleted
  ): TasksDTO {
    $task = $this->tasksRepository->find($id);

    if (empty($task)) {
      throw new \Exception("Task not found");
    }

    $task->setTitle($title);
    $task->setDescription($description);
    $task->setPosition($position);
    $task->setIsCompleted($isCompleted);
    $task->setUpdatedAt(new \DateTimeImmutable());

    $this->tasksRepository->getEntityManager()->persist($task);
    $this->tasksRepository->getEntityManager()->flush();

    return new TasksDTO(
      $task->getId(),
      [
        "id" => "/api/lists/" . $task->getList()->getId(),
        "name" => $task->getList()->getTitle()
      ],
      $task->getTitle(),
      $task->getDescription(),
      $task->getPosition(),
      $task->isCompleted(),
      $task->getCreatedAt(),
      $task->getUpdatedAt()
    );
  }

  public function move(string $id, string $listId): TasksDTO
  {
    $task = $this->tasksRepository->find($id);

    if (empty($task)) {
      throw new \Exception("Task not found");
    }

    $task->setList($this->tasksRepository->find($listId));

    $this->tasksRepository->getEntityManager()->persist($task);
    $this->tasksRepository->getEntityManager()->flush();

    return new TasksDTO(
      $task->getId(),
      [
        "id" => "/api/lists/" . $task->getList()->getId(),
        "name" => $task->getList()->getTitle()
      ],
      $task->getTitle(),
      $task->getDescription(),
      $task->getPosition(),
      $task->isCompleted(),
      $task->getCreatedAt(),
      $task->getUpdatedAt()
    );
  }

  public function delete(string $id): void
  {
    $task = $this->tasksRepository->find($id);

    if (empty($task)) {
      throw new \Exception("Task not found");
    }

    $this->tasksRepository->delete($task);
  }
}
