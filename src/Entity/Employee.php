<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use App\Request\EmployeeDto;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Employee implements NormalizableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'first_name', type: 'string', length: 64, nullable: false)]
    private string $firstName;

    #[ORM\Column(name: 'last_name', type: 'string', length: 64, nullable: false)]
    private string $lastName;

    #[ORM\Column(name: 'email', type: 'string', unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(name: 'date_of_employment', type: 'datetime', nullable: false)]
    private \DateTime $dateOfEmployment;

    #[ORM\Column(name: 'salary', type: 'float', nullable: false)]
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
        return null !== $this->id;
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
}
