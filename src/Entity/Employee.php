<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use App\Request\EmployeeDto;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Employee implements NormalizableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[OA\Property(description: 'The unique identifier of the employee.')]
    private ?int $id = null;

    #[ORM\Column(name: 'first_name', type: 'string', length: 64, nullable: false)]
    #[OA\Property(type: 'string', maxLength: 64)]
    #[SerializedName('first_name')]
    private string $firstName;

    #[ORM\Column(name: 'last_name', type: 'string', length: 64, nullable: false)]
    #[OA\Property(type: 'string', maxLength: 64)]
    #[SerializedName('last_name')]
    private string $lastName;

    #[ORM\Column(name: 'email', type: 'string', unique: true, nullable: false)]
    #[OA\Property(type: 'string')]
    private string $email;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[SerializedName('created_at')]
    #[OA\Property(description: 'Created at', type: 'string', format: 'date-time')]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    #[SerializedName('updated_at')]
    #[OA\Property(description: 'Updated at', type: 'string', format: 'date-time')]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(name: 'date_of_employment', type: 'datetime', nullable: false)]
    #[SerializedName('date_of_employment')]
    #[OA\Property(description: 'Date of Employment', type: 'string', format: 'date-time')]
    private \DateTime $dateOfEmployment;

    #[ORM\Column(name: 'salary', type: 'float', nullable: false)]
    #[OA\Property(type: 'string', format: 'float')]
    private float $salary;

    public function __construct(string $firstName, string $lastName, string $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->createdAt = new \DateTime('now');
    }

    public function touch(): self
    {
        $this->updatedAt = new \DateTime('now');

        return $this;
    }

    public static function new(EmployeeDto $data): self
    {
        return new self(
            firstName: $data->firstName,
            lastName: $data->lastName,
            email: $data->email
        );
    }

    public function setDateOfEmployment(\DateTime $dateOfEmployment): self
    {
        $this->dateOfEmployment = $dateOfEmployment;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setSalary(float $salary): Employee
    {
        $this->salary = $salary;

        return $this;
    }

    public function isNew(): bool
    {
        return null === $this->id;
    }

    public function normalize(NormalizerInterface $normalizer, ?string $format = null, array $context = []): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'date_of_employment' => $this->dateOfEmployment->format('Y-m-d H:i:s'),
            'salary' => $this->salary,
        ];
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getDateOfEmployment(): \DateTime
    {
        return $this->dateOfEmployment;
    }

    public function getSalary(): float
    {
        return $this->salary;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
