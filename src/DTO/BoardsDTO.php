<?php

namespace App\DTO;

class BoardsDTO
{
  public string $id;
  public string $name;
  public ?string $description;
  public array $owner;
  public \DateTimeImmutable $created_at;
  public ?\DateTimeImmutable $updated_at;

  public function __construct(
    string $id,
    string $name,
    ?string $description,
    array $owner,
    \DateTimeImmutable $created_at,
    ?\DateTimeImmutable $updated_at
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->owner = $owner;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
  }
}