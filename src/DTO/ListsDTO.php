<?php

namespace App\DTO;

class ListsDTO
{
  public string $id;
  public array $board_id;
  public string $title;
  public string $position;
  public \DateTimeImmutable $created_at;
  public ?\DateTimeImmutable $updated_at;

  public function __construct(
    string $id,
    array $board_id,
    string $title,
    string $position,
    \DateTimeImmutable $created_at,
    ?\DateTimeImmutable $updated_at
  ) {
    $this->id = $id;
    $this->board_id = $board_id;
    $this->title = $title;
    $this->position = $position;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
  }
}