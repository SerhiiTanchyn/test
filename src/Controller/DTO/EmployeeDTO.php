<?php

namespace App\Controller\DTO;

use App\Entity\Employee;

class EmployeeDTO
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $email;
    private \DateTimeInterface $hireDate;
    private float $salary;
    private \DateTimeInterface $createdAt;
    private ?\DateTimeInterface $updatedAt;


    public static function build(Employee $employee): self
    {
        $dto = new self();

        $dto->id = $employee->getId();
        $dto->firstName = $employee->getFirstName();
        $dto->lastName = $employee->getLastName();
        $dto->email = $employee->getEmail();
        $dto->hireDate = $employee->getHireDate();
        $dto->salary = $employee->getSalary();
        $dto->createdAt = $employee->getCreatedAt();
        $dto->updatedAt = $employee->getUpdatedAt();

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'hireDate' => $this->hireDate->format('Y-m-d'),
            'salary' => $this->salary,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}