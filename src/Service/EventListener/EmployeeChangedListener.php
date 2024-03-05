<?php

namespace App\Service\EventListener;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Employee::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Employee::class)]
readonly class EmployeeChangedListener
{
    public function postUpdate(Employee $employee, PostUpdateEventArgs $args): void
    {
        /** @var Employee $entity */
        $entity = $args->getObject();

        if (!$entity instanceof Employee) {
            return;
        }

        if ($entity->isNew()) {
            return;
        }

        $entity->touch();
    }

    public function prePersist(Employee $employee, PrePersistEventArgs $args): void
    {
        /** @var Employee $entity */
        $entity = $args->getObject();
        if (!$entity instanceof Employee) {
            return;
        }

        if (null === $entity->getCreatedAt()) {
            $entity->setCreatedAt(new \DateTime('now'));
        }
    }
}
