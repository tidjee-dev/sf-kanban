<?php

namespace App\Controller\Api;

use App\Service\TasksService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tasks', name: 'api_tasks_')]
class TasksController extends AbstractController
{
  public function __construct(
    private TasksService $tasksService
  ) {}

  #[Route('/list/{listId}', requirements: ['id' => '^LST-[0-9]{10}-[0-9]{4}$'], name: 'indexByList', methods: ['GET'])]
  public function index(
    string $listId
  ): JsonResponse {
    try {
      $tasks = $this->tasksService->getTasksByListId($listId);
      $listName = $tasks[0]->list_id['name'];
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 500,
          'message' => $e->getMessage()
        ]
      ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    return $this->json([
      'status' => [
        'code' => 200,
        'message' => "Tasks for `{$listName}` retrieved successfully"
      ],
      'tasks' => $tasks
    ], JsonResponse::HTTP_OK);
  }

  #[Route('/', name: 'create', methods: ['POST'])]
  public function create(Request $request): JsonResponse
  {
    try {
      $task = $this->tasksService->create(
        $request->get('listId'),
        $request->get('title'),
        $request->get('description'),
        $request->get('position')
      );
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 400,
          'message' => $e->getMessage()
        ]
      ], JsonResponse::HTTP_BAD_REQUEST);
    }

    return $this->json([
      'status' => [
        'code' => 201,
        'message' => "Task `{$task->title}` created successfully"
      ],
      'task' => $task
    ], JsonResponse::HTTP_CREATED);
  }

  #[Route('{id}', requirements: ['id' => '^LST-[0-9]{10}-[0-9]{4}$'], name: 'update', methods: ['PUT'])]
  public function update(
    string $id,
    Request $request
  ): JsonResponse {
    try {
      $task = $this->tasksService->update(
        $id,
        $request->get('title'),
        $request->get('description'),
        $request->get('position'),
        $request->get('isCompleted')
      );
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
        'message' => "Task `{$task->title}` updated successfully"
      ],
      'task' => $task
    ], JsonResponse::HTTP_OK);
  }

  #[Route('{id}', requirements: ['id' => '^LST-[0-9]{10}-[0-9]{4}$'], name: 'move', methods: ['PATCH'])]
  public function move(
    string $id,
    Request $request
  ): JsonResponse {
    try {
      $task = $this->tasksService->move(
        $id,
        $request->get('position')
      );
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
        'message' => "Task `{$task->title}` moved successfully"
      ],
      'task' => $task
    ], JsonResponse::HTTP_OK);
  }

  #[Route('{id}', requirements: ['id' => '^LST-[0-9]{10}-[0-9]{4}$'], name: 'delete', methods: ['DELETE'])]
  public function delete(string $id): JsonResponse
  {
    try {
      $this->tasksService->delete($id);
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
        'message' => "Task deleted successfully"
      ]
    ], JsonResponse::HTTP_OK);
  }
}
