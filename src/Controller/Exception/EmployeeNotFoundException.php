<?php

namespace App\Controller\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeeNotFoundException extends NotFoundHttpException
{
    protected $message = 'Employee not found';
}