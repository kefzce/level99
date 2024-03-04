<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Request\EmployeeDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/employee', name: 'app_employee_')]
class EmployeeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/{id\d+}', name: 'get', methods: ['GET'])]
    public function get(#[MapEntity] Employee $employee): JsonResponse
    {
        return $this->json($employee, Response::HTTP_OK);
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] EmployeeDto $request): JsonResponse
    {
        $employee = new Employee(
            firstName: $request->firstName,
            lastName: $request->lastName,
            email: $request->email
        );
        $employee->setSalary($request->salary);
        $employee->setDateOfEmployment($request->dateOfEmployment);

        $this->em->persist($employee);
        $this->em->flush();

        return $this->json($employee, Response::HTTP_CREATED);
    }

    #[Route('/{id\d+}', name: 'update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] EmployeeDto $request, #[MapEntity] Employee $employee): JsonResponse
    {
        $employee->touch()
            ->setFirstName($request->firstName)
            ->setLastName($request->lastName)
            ->setEmail($request->email)
            ->setSalary($request->salary)
            ->setDateOfEmployment($request->dateOfEmployment);

        // thanks to UoW only updated fields of managed entity will be in the final update SQL query.
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id\d+}', name: 'delete', methods: ['DELETE'])]
    public function delete(#[MapEntity] Employee $employee): JsonResponse
    {
        $this->em->remove($employee);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
