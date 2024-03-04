<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class EmployeeDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $firstName;
    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $lastName;

    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $email;

    #[Assert\GreaterThanOrEqual(100)]
    #[Assert\NotNull]
    public float $salary;

    #[Assert\GreaterThanOrEqual('today')]
    #[Assert\DateTime]
    public \DateTime $dateOfEmployment;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        float $salary,
        \DateTime $dateOfEmployment
    ) {
        $this->email = $email;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->salary = $salary;
        $this->dateOfEmployment = $dateOfEmployment;
    }
}
