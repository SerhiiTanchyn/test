<?php

namespace App\Tests\Functional;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use App\Service\EmployeeService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EmployeeService $employeeService;
    private EmployeeRepository $employeeRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->employeeService = self::getContainer()->get(EmployeeService::class);
        $this->employeeRepository = self::getContainer()->get(EmployeeRepository::class);
    }

    public function testCreateEmployeeEmptyName(): void
    {
        $data = [
            'firstName' => '',
            'lastName' => 'Doe',
            'email' => 'john.doe@test.test',
            'hireDate' => '2025-12-12',
            'salary' => 3000,
        ];

        $this->client->request(
            'POST',
            '/api/employees',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreateEmployeeEmptySecondName(): void
    {
        $data = [
            'firstName' => 'Jon',
            'lastName' => '',
            'email' => 'john.doe@test.test',
            'hireDate' => '2025-12-12',
            'salary' => 3000,
        ];

        $this->client->request(
            'POST',
            '/api/employees',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreateEmployeeEmptyEmail(): void
    {
        $data = [
            'firstName' => 'Jon',
            'lastName' => 'DOe',
            'email' => '',
            'hireDate' => '2025-12-12',
            'salary' => 3000,
        ];

        $this->client->request(
            'POST',
            '/api/employees',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreateWrongHireDate(): void
    {
        $data = [
            'firstName' => 'Jon',
            'lastName' => 'DOe',
            'email' => 'john.doe@test.test',
            'hireDate' => '2024-11-11',
            'salary' => 3000,
        ];

        $this->client->request(
            'POST',
            '/api/employees',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreateLessSalary(): void
    {
        $data = [
            'firstName' => 'Jon',
            'lastName' => 'DOe',
            'email' => 'john.doe@test.test',
            'hireDate' => '2028-11-11',
            'salary' => 99,
        ];

        $this->client->request(
            'POST',
            '/api/employees',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreateEmployeeSuccess(): int
    {
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@test.test',
            'hireDate' => '2025-11-11',
            'salary' => 3000,
        ];

        $this->client->request(
            'POST',
            '/api/employees',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($responseData['result']);
        $this->assertArrayHasKey('id', $responseData['data']);
        return $responseData['data']['id'];
    }

    public function testFindEmployeeNotExists(): void
    {
        $this->client->request(
            'GET',
            '/api/employees/99999',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFindEmployeeSuccess()
    {
        $employeeId = $this->employeeService->save($this->buildEmployee());

        $this->client->request('GET', "/api/employees/{$employeeId}");

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseData['result']);
        $this->assertEquals($employeeId, $responseData['data']['id']);
        $this->assertEquals('John', $responseData['data']['firstName']);
        $this->assertEquals('Doe', $responseData['data']['lastName']);
        $this->assertEquals('john.doe@example.com', $responseData['data']['email']);
        $this->assertEquals(3000, $responseData['data']['salary']);
    }

    public function testUpdateEmployeeNotExists(): void
    {
        $this->client->request(
            'PUT',
            '/api/employees/99999',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testUpdateEmployeeEmptyName(): void
    {
        $employee = $this->buildEmployee();
        $employeeId = $this->employeeService->save($employee);

        $data = [
            'firstName' => '',
        ];

        $this->client->request(
            'PUT',
            '/api/employees/' . $employeeId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testUpdateEmployeeEmptySecondName(): void
    {
        $employee = $this->buildEmployee();
        $employeeId = $this->employeeService->save($employee);

        $data = [
            'lastName' => '',
        ];

        $this->client->request(
            'PUT',
            '/api/employees/' . $employeeId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testUpdateEmployeeEmptyEmail(): void
    {
        $employee = $this->buildEmployee();
        $employeeId = $this->employeeService->save($employee);

        $data = [
            'email' => '',
        ];

        $this->client->request(
            'PUT',
            '/api/employees/' . $employeeId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreateLessHireDate(): void
    {
        $employee = $this->buildEmployee();
        $employeeId = $this->employeeService->save($employee);

        $data = [
            'hireDate' => '2024-11-11',
        ];

        $this->client->request(
            'PUT',
            '/api/employees/' . $employeeId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCUpdateLessSalary(): void
    {
        $employee = $this->buildEmployee();
        $employeeId = $this->employeeService->save($employee);

        $data = [
            'salary' => 99,
        ];

        $this->client->request(
            'PUT',
            '/api/employees/' . $employeeId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testUpdateEmployeeSuccess()
    {
        $employeeId = $this->employeeService->save($this->buildEmployee());

        $updateData = [
            'firstName' => 'Jane',
            'lastName' => 'Smith',
            'email' => 'jane.smith@example.com',
            'hireDate' => '2025-12-16',
            'salary' => 4000,
        ];

        $this->client->request(
            'PUT',
            "/api/employees/{$employeeId}",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($updateData)
        );

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($responseData['result']);

        $this->client->request('GET', "/api/employees/{$employeeId}");
        $updatedResponse = $this->client->getResponse();
        $updatedResponseData = json_decode($updatedResponse->getContent(), true);

        $this->assertEquals('Jane', $updatedResponseData['data']['firstName']);
        $this->assertEquals('Smith', $updatedResponseData['data']['lastName']);
        $this->assertEquals('jane.smith@example.com', $updatedResponseData['data']['email']);
        $this->assertEquals('2025-12-16', $updatedResponseData['data']['hireDate']);
        $this->assertEquals(4000, $updatedResponseData['data']['salary']);
        $this->assertNotNull($updatedResponseData['data']['updatedAt']);
    }

    public function testDeleteEmployeeNotExists(): void
    {
        $this->client->request(
            'DELETE',
            '/api/employees/99999',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testDeleteEmployeeSuccess(): void
    {
        $employeeId = $this->employeeService->save($this->buildEmployee());

        $this->client->request(
            'DELETE',
            '/api/employees/' . $employeeId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();

        $savedEmployee = $this->employeeRepository->find($employeeId);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull($savedEmployee);
    }

    private function buildEmployee(): Employee
    {
        $employee = new Employee();
        $employee->setFirstName('John');
        $employee->setLastName('Doe');
        $employee->setEmail('john.doe@example.com');
        $employee->setHireDate(new \DateTime('2025-11-11'));
        $employee->setSalary(3000);
        return $employee;
    }
}
