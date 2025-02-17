<?php

namespace App\Controller;

use App\Controller\DTO\EmployeeDTO;
use App\Service\EmployeeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route('/api/employees')]
class EmployeeController extends AbstractController
{
    public function __construct(
        private readonly EmployeeService $employeeService,
    )
    {
    }

    #[Route(methods: 'POST')]
    #[OA\Post(
        summary: 'Create a new employee',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'firstName', type: 'string', example: 'John'),
                    new OA\Property(property: 'lastName', type: 'string', example: 'Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'test@test.test'),
                    new OA\Property(property: 'hireDate', type: 'string', format: 'date', example: '2025-10-10'),
                    new OA\Property(property: 'salary', type: 'number', example: 3000),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Employee created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Employee created successfully'),
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                        ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                    ]
                )
            ),
        ]
    )]
    public function save(
        Request $request
    ): Response
    {
        try {
            $employee = $this->employeeService->saveEmployee($request);
            return $this->json(
                [
                    'result' => true,
                    'message' => 'Employee created successfully',
                    'data' => [
                        'id' => $employee->getId(),
                    ],
                ],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'result' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route(path: '/{id}', methods: 'PUT')]
    #[OA\Put(
        summary: 'Update an employee',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'firstName', type: 'string', example: 'John'),
                    new OA\Property(property: 'lastName', type: 'string', example: 'Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'test@test.test'),
                    new OA\Property(property: 'hireDate', type: 'string', format: 'date', example: '2025-10-10'),
                    new OA\Property(property: 'salary', type: 'number', example: 3000),
                ]
            )
        ),
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Employee updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Employee updated successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Validation error or invalid request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Employee not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Could not find employee'),
                    ]
                )
            ),
        ]
    )]
    public function update(
        int     $id,
        Request $request
    ): Response
    {
        try {
            $this->employeeService->saveEmployee($request, $id);
            return $this->json(
                [
                    'result' => true,
                    'message' => 'Employee updated successfully',
                ],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'result' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route(path: '/{id}', methods: 'GET')]
    #[OA\Get(
        summary: 'Get employee by ID',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Employee found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'boolean', example: true),
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'firstName', type: 'string', example: 'John'),
                            new OA\Property(property: 'lastName', type: 'string', example: 'Doe'),
                            new OA\Property(property: 'email', type: 'string', example: 'test@test.com'),
                            new OA\Property(property: 'hireDate', type: 'string', format: 'date', example: '2025-02-16'),
                            new OA\Property(property: 'salary', type: 'number', example: 3000),
                            new OA\Property(property: 'createdAt', type: 'string', format: 'date-time', example: '2024-02-14 11:11:22'),
                            new OA\Property(property: 'updatedAt', type: 'string', format: 'date-time', example: '2024-02-14 11:21:22', nullable: true),
                        ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Employee not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Could not find employee'),
                    ]
                )
            ),
        ]
    )]
    public function find(
        int $id,
    ): Response
    {
        try {
            $employee = $this->employeeService->findById($id);
        } catch (\Exception $e) {
            return $this->json(
                [
                    'result' => false,
                    'message' => 'Could not found employee',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json(
            [
                'result' => true,
                'data' => EmployeeDTO::build($employee)->toArray(),
            ]
        );
    }

    #[Route(path: '/{id}', methods: 'DELETE')]
    #[OA\Delete(
        summary: 'Delete employee by ID',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Employee deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'boolean', example: true)
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Employee not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Could not found employee')
                    ]
                )
            )
        ]
    )]
    public function delete(
        int $id,
    ): Response
    {
        try {
            $result = $this->employeeService->remove($id);
        } catch (\Exception $e) {
            return $this->json(
                [
                    'result' => false,
                    'message' => 'Could not found employee',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json(
            [
                'result' => $result,
            ]
        );
    }
}