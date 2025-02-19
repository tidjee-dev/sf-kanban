<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api', name: 'api_')]
class HomeController extends AbstractController
{
  #[Route('/', name: 'index', methods: ['GET'])]
  public function index(): JsonResponse
  {
    return $this->json([
      'status' => [
        'code' => 200,
        'message' => 'Welcome to the API'
      ],
      'endpoints' => [
        'users' => [
          'register' => [
            'description' => 'Register a new user',
            'method' => 'POST',
            'endpoint' => '/api/users/register',
            'body' => [
              'username' => 'string',
              'email' => 'string',
              'password' => 'string',
              'roles' => 'array',
            ],
            'response' => [
              'id' => 'string',
              'username' => 'string',
              'email' => 'string',
              'roles' => 'array',
              'is_active' => 'boolean',
              'created_at' => 'datetime',
              'updated_at' => 'datetime | null'
            ]
          ],
          'login' => [
            'description' => 'Login and obtain a JWT',
            'method' => 'POST',
            'endpoint' => '/api/users/login'
          ],
          'me' => [
            'description' => 'Get current user details',
            'method' => 'GET',
            'endpoint' => '/api/users/me'
          ],
          'update' => [
            'description' => 'Update user information',
            'method' => 'PUT',
            'endpoint' => '/api/users/{id}'
          ],
          'delete' => [
            'description' => 'Delete a user',
            'method' => 'DELETE',
            'endpoint' => '/api/users/{id}'
          ]
        ],
        'boards' => [
          'list' => [
            'description' => 'List all boards',
            'method' => 'GET',
            'endpoint' => '/api/boards'
          ],
          'create' => [
            'description' => 'Create a new board',
            'method' => 'POST',
            'endpoint' => '/api/boards'
          ],
          'get' => [
            'description' => 'Get board details',
            'method' => 'GET',
            'endpoint' => '/api/boards/{id}'
          ],
          'update' => [
            'description' => 'Update board',
            'method' => 'PUT',
            'endpoint' => '/api/boards/{id}'
          ],
          'delete' => [
            'description' => 'Delete a board',
            'method' => 'DELETE',
            'endpoint' => '/api/boards/{id}'
          ]
        ],
        'lists' => [
          'create' => [
            'description' => 'Create a new list',
            'method' => 'POST',
            'endpoint' => '/api/lists'
          ],
          'get' => [
            'description' => 'Get list details',
            'method' => 'GET',
            'endpoint' => '/api/lists/{id}'
          ],
          'update' => [
            'description' => 'Update list',
            'method' => 'PUT',
            'endpoint' => '/api/lists/{id}'
          ],
          'delete' => [
            'description' => 'Delete a list',
            'method' => 'DELETE',
            'endpoint' => '/api/lists/{id}'
          ]
        ],
        'tasks' => [
          'create' => [
            'description' => 'Create a new task',
            'method' => 'POST',
            'endpoint' => '/api/tasks'
          ],
          'list' => [
            'description' => 'List tasks for a given list',
            'method' => 'GET',
            'endpoint' => '/api/tasks/list/{listId}'
          ],
          'move' => [
            'description' => 'Move a task to a different list',
            'method' => 'PATCH',
            'endpoint' => '/api/tasks/{id}/move'
          ],
          'update' => [
            'description' => 'Update task details',
            'method' => 'PUT',
            'endpoint' => '/api/tasks/{id}'
          ],
          'delete' => [
            'description' => 'Delete a task',
            'method' => 'DELETE',
            'endpoint' => '/api/tasks/{id}'
          ]
        ]
      ]
    ]);
  }
}
