<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Persistence\ManagerRegistry;

class EmployeeRepository extends BaseServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }
}