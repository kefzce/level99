<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use App\Request\EmployeeDto;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route(path: '/api/employee', name: 'app_employee_')]
class EmployeeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private readonly NormalizerInterface $normalizer,
        private readonly EmployeeRepository $employeeRepository
    ) {
    }

    #[OA\Response(
        response: 200,
        description: 'Returns all employees if any are preserved',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Employee::class))
        )
    )]
    #[Route('', name: 'list', methods: ['GET'])]
    public function all(): JsonResponse
    {
        return $this->json($this->employeeRepository->findAll());
    }

    #[OA\Response(
        response: 200,
        description: 'Return specific employee by its id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Employee::class))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Entity not found',
    )]
    #[Route('/{id<\d+>}', name: 'get', methods: ['GET'])]
    public function get(#[MapEntity] Employee $employee): JsonResponse
    {
        return $this->json($this->normalizer->normalize($employee, JsonEncoder::FORMAT, [AbstractObjectNormalizer::PRESERVE_EMPTY_OBJECTS => true]), Response::HTTP_OK);
    }

    #[OA\Response(
        response: 201,
        description: 'Success',
    )]
    #[Route('', name: 'create', methods: ['POST'])]
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

        return $this->json($this->normalizer->normalize($employee, 'json'), Response::HTTP_CREATED);
    }

    #[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
    #[OA\Response(
        response: 404,
        description: 'Entity not found',
    )]
    #[OA\Response(
        response: 204,
        description: 'Success',
    )]
    public function update(#[MapRequestPayload] EmployeeDto $request, #[MapEntity] Employee $employee, EntityManagerInterface $em): JsonResponse
    {
        $employee->touch()
            ->setFirstName($request->firstName)
            ->setLastName($request->lastName)
            ->setEmail($request->email)
            ->setSalary($request->salary)
            ->setDateOfEmployment($request->dateOfEmployment);

        // thanks to UoW only updated fields of managed entity will be in the final update SQL query.
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 204,
        description: 'Success',
    )]
    public function delete(int $id): JsonResponse
    {
        // we don't want to use here #[MapEntity] Employee
        // since it give to us non-idempotent DELETE which brakes RFC2616
        // EntityValueResolver works kinda bad with DELETE
        $employee = $this->employeeRepository->find($id);
        if (null !== $employee) {
            $this->em->remove($employee);
            $this->em->flush();
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
