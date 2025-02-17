<?php

namespace App\DTO;

class UsersDTO
{
  public string $id;
  public string $username;
  public string $email;
  public string $password;
  public array $roles;
  public bool $is_active;
  public \DateTimeImmutable $created_at;
  public ?\DateTimeImmutable $updated_at;

  public function __construct(
    string $id,
    string $username,
    string $email,
    string $password,
    string $roles,
    bool $is_active,
    \DateTimeImmutable $created_at,
    ?\DateTimeImmutable $updated_at
  ) {
    $this->id = $id;
    $this->username = $username;
    $this->email = $email;
    $this->password = $password;
    $this->roles = $roles;
    $this->is_active = $is_active;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
  }
}