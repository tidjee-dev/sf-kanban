<?php

namespace App\Controller\Api;

use App\Service\UsersService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/users', name: 'api_users_')]
class UsersController extends AbstractController
{
  public function __construct(private UsersService $usersService) {}

  #[Route('/', name: 'index', methods: ['GET'])]
  public function index(): JsonResponse
  {
    try {
      $users = $this->usersService->index();
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 500,
          'message' => $e->getMessage()
        ]
      ]);
    }

    return $this->json([
      'status' => [
        'code' => 200,
        'message' => 'Users retrieved successfully'
      ],
      'users' => $users
    ]);
  }

  #[Route('/{id}', name: 'show', methods: ['GET'])]
  public function show(string $id): JsonResponse
  {
    try {
      $user = $this->usersService->show($id);

      return $this->json([
        'status' => [
          'code' => 200,
          'message' => "User `{$user->username}` retrieved successfully"
        ],
        'user' => $user
      ]);
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 404,
          'message' => 'User not found'
        ]
      ], JsonResponse::HTTP_NOT_FOUND);
    }
  }

  #[Route('/', name: 'create', methods: ['POST'])]
  public function create(Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    try {
      $user = $this->usersService->create(
        $data['username'],
        $data['email'],
        $data['password'],
        $data['roles'] ?? [],
        $data['isActive'] ?? false
      );

      return $this->json([
        'status' => [
          'code' => 201,
          'message' => "User `{$user->username}` created successfully"
        ],
        'user' => $user
      ], JsonResponse::HTTP_CREATED);
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 400,
          'message' => $e->getMessage()
        ]
      ], JsonResponse::HTTP_BAD_REQUEST);
    }
  }

  #[Route('/{id}', name: 'update', methods: ['PUT'])]
  public function update(string $id, Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    try {
      $user = $this->usersService->update(
        $id,
        $data['username'],
        $data['email'],
        $data['password'],
        $data['roles'] ?? [],
        $data['isActive'] ?? false
      );

      return $this->json([
        'status' => [
          'code' => 200,
          'message' => "User `{$user->username}` updated successfully"
        ],
        'user' => $user
      ]);
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 404,
          'message' => 'User not found'
        ]
      ], JsonResponse::HTTP_NOT_FOUND);
    }
  }

  #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
  public function delete(string $id): JsonResponse
  {
    try {
      $this->usersService->delete($id);

      return $this->json([
        'status' => [
          'code' => 204,
          'message' => 'User deleted successfully'
        ]
      ], JsonResponse::HTTP_NO_CONTENT);
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 404,
          'message' => 'User not found'
        ]
      ], JsonResponse::HTTP_NOT_FOUND);
    }
  }
}
