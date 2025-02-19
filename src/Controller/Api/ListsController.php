<?php

namespace App\Controller\Api;

use App\Service\ListsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/lists', name: 'api_lists_')]
class ListsController extends AbstractController
{
  public function __construct(
    private ListsService $listsService
  ) {}
  #[Route('/board/{boardId}', requirements: ['id' => '^BRD-[0-9]{10}-[0-9]{4}$'], name: 'indexByBoard', methods: ['GET'])]
  public function indexByBoard(string $boardId): JsonResponse
  {
    try {
      $lists = $this->listsService->getListByBoardId($boardId);
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 404,
          'message' => $e->getMessage()
        ]
      ]);
    }

    return $this->json([
      'status' => [
        'code' => 200,
        'message' => 'Lists retrieved successfully'
      ],
      'lists' => $lists
    ]);
  }

  #[Route('/', name: 'create', methods: ['POST'])]
  public function create(Request $request): JsonResponse
  {
    try {
      $list = $this->listsService->create(
        $request->get('title'),
        $request->get('position'),
        $request->get('boardId')
      );
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 400,
          'message' => $e->getMessage()
        ]
      ]);
    }
  }

  #[Route('/{id}', name: 'update', methods: ['PUT'])]
  public function update(string $id, Request $request): JsonResponse
  {
    try {
      $list = $this->listsService->update($id, $request->get('title'), $request->get('position'));
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 404,
          'message' => $e->getMessage()
        ]
      ]);
    }

    return $this->json([
      'status' => [
        'code' => 200,
        'message' => "List `{$list->title}` updated successfully"
      ],
      'list' => $list
    ]);
  }

  #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
  public function delete(string $id): JsonResponse
  {
    try {
      $this->listsService->delete($id);
    } catch (\Exception $e) {
      return $this->json([
        'status' => [
          'code' => 404,
          'message' => $e->getMessage()
        ]
      ]);
    }

    return $this->json([
      'status' => [
        'code' => 200,
        'message' => "List deleted successfully"
      ]
    ]);
  }
}
