<?php

namespace App\Controller\Api;

use App\Service\BoardsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/boards', name: 'boards', methods: ['GET'])]
class BoardsController extends AbstractController
{
  public function __construct(
    private BoardsService $boardsService
  ) {}

  #[Route('/', name: 'index', methods: ['GET'])]
  public function index(): JsonResponse
  {
    try {
      $boards = $this->boardsService->index();
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 500,
          'message' => $e->getMessage()
        ]
      ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    return $this->json(
      [
        'status' => [
          'code' => 200,
          'message' => 'Boards retrieved successfully'
        ],
        'boards' => $boards
      ],
      JsonResponse::HTTP_OK
    );
  }

  #[Route('/{id}', requirements: ['id' => '^BRD-[0-9]{10}-[0-9]{4}$'], name: 'show', methods: ['GET'])]
  public function show(string $id): JsonResponse
  {
    try {
      $board = $this->boardsService->show($id);
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 404,
          'message' => $e->getMessage()
        ]
      ], JsonResponse::HTTP_NOT_FOUND);
    }

    return $this->json(
      [
        'status' => [
          'code' => 200,
          'message' => "Board `{$board->name}` retrieved successfully"
        ],
        'board' => $board
      ],
      JsonResponse::HTTP_OK
    );
  }

  #[Route('/', name: 'create', methods: ['POST'])]
  public function create(Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    try {
      $board = $this->boardsService->create(
        $data['name'],
        $data['description'],
        $data['ownerId']
      );
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 400,
          'message' => $e->getMessage()
        ]
      ], JsonResponse::HTTP_BAD_REQUEST);
    }

    return $this->json(
      [
        'status' => [
          'code' => 201,
          'message' => "Board `{$board->name}` created successfully"
        ],
        'board' => $board
      ],
      JsonResponse::HTTP_CREATED
    );
  }

  #[Route('/{id}', name: 'update', methods: ['PUT'])]
  public function update(string $id, Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    try {
      $board = $this->boardsService->update(
        $id,
        $data['name'],
        $data['description']
      );
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 404,
          'message' => $e->getMessage()
        ]
      ], JsonResponse::HTTP_NOT_FOUND);
    }

    return $this->json(
      [
        'status' => [
          'code' => 200,
          'message' => "Board `{$board->name}` updated successfully"
        ],
        'board' => $board
      ],
      JsonResponse::HTTP_OK
    );
  }

  #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
  public function delete(string $id): JsonResponse
  {
    try {
      $this->boardsService->delete($id);
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 404,
          'message' => $e->getMessage()
        ]
      ], JsonResponse::HTTP_NOT_FOUND);
    }

    return $this->json([
      'status' => [
        'code' => 200,
        'message' => "Board deleted successfully"
      ]
    ], JsonResponse::HTTP_OK);
  }
}
