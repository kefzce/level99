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
    public function postUpdate(PostUpdateEventArgs $args): void
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

        $args->getObjectManager()->flush();
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        /** @var Employee $entity */
        $entity = $args->getObject();
        if (!$entity instanceof Employee) {
            return;
        }

        if (null === $entity->getCreatedAt()) {
            $entity->setCreatedAt(new \DateTime('now'));
        }

        $args->getObjectManager()->flush();
    }
}
