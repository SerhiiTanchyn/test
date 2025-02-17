<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class BaseServiceEntityRepository extends ServiceEntityRepository
{
    /**
     * @param object $entity
     * @return $this
     */
    public function persist(object $entity): static
    {
        $this->getEntityManager()->persist($entity);
        return $this;
    }

    /**
     * @param object $entity
     * @return $this
     */
    public function remove(object $entity): bool
    {
        $this->getEntityManager()->remove($entity);
        return true;
    }

    /**
     * @return bool
     */
    public function flush(): bool
    {
        $this->getEntityManager()->flush();
        return true;
    }
}