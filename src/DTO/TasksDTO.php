<?php

namespace App\DTO;

class TasksDTO
{
  public string $id;
  public string $list_id;
  public string $title;
  public string $description;
  public string $position;
  public \DateTimeImmutable $created_at;
  public ?\DateTimeImmutable $updated_at;

  public function __construct(
    string $id,
    string $list_id,
    string $title,
    string $description,
    string $position,
    \DateTimeImmutable $created_at,
    ?\DateTimeImmutable $updated_at
  ) {
    $this->id = $id;
    $this->list_id = $list_id;
    $this->title = $title;
    $this->description = $description;
    $this->position = $position;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
  }
}