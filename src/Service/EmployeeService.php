<?php

namespace App\Service;

use App\Controller\Exception\EmployeeNotFoundException;
use App\Controller\Exception\EmployeeWrongFieldValueException;
use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

readonly class EmployeeService
{
    public function __construct(
        private EmployeeRepository   $employeeRepository,
        private FormFactoryInterface $formFactory
    )
    {
    }

    /**
     * @param Employee $employee
     * @return int|null
     */
    public function save(Employee $employee): ?int
    {
        return $this->employeeRepository->persist($employee)->flush()
            ? $employee->getId()
            : NULL;
    }

    /**
     * @param Employee $employee
     * @return bool
     */
    public function update(Employee $employee): bool
    {
        return (bool)$this->save($employee);
    }

    /**
     * @param int $id
     * @return Employee
     */
    public function findById(int $id): Employee
    {
        $employee = $this->employeeRepository->find($id);

        if (!$employee) {
            throw new EmployeeNotFoundException();
        }
        return $employee;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool
    {
        $employee = $this->findById($id);
        return $this->employeeRepository->remove($employee) &&
            $this->employeeRepository->flush();
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return Employee
     * @throws \Exception
     */
    public function saveEmployee(Request $request, ?int $id = NULL): Employee
    {
        $data = json_decode($request->getContent(), true);

        $employee = $id
            ? $this->findById($id)
            : new Employee();

        $form = $this->formFactory->create(EmployeeType::class, $employee);
        $form->submit($data);

        if ($errors = $this->getFormErrorsToArray($form)) {
            // we return only first error
            $firstError = array_shift($errors);
            throw new EmployeeWrongFieldValueException("field {$firstError['field']}: {$firstError['message']}");
        }

        try {
            $this->save($employee);
        } catch (\Exception) {
            throw new \Exception('Could not create employee');
        }

        return $employee;
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    private function getFormErrorsToArray(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = [
                'field' => $error->getOrigin()->getName(),
                'message' => $error->getMessage(),
            ];
        }

        foreach ($form->all() as $childForm) {
            // extend if need to work with Collection
            if ($childForm instanceof FormInterface) {
                $childErrors = $this->getFormErrorsToArray($childForm);
                if (!empty($childErrors)) {
                    $errors = array_merge($errors, $childErrors);
                }
            }
        }
        return $errors;
    }
}