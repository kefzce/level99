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

    #[Assert\GreaterThanOrEqual(
        value: 100,
        message: 'Salary should be greater than or equal to 100'
    )]
    #[Assert\NotNull]
    public float $salary;

    #[Assert\GreaterThanOrEqual(
        value: 'today',
        message: 'The Date of Employment  {{ value }} supposed to be in a future after {{ compared_value }}',
    )]
    #[Assert\Valid]
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
