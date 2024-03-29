<?php

namespace App\Db\Service;

use App\Db\Entity\Category;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

final readonly class CategoryService
{
    public function __construct(
        private EntityManager $entityManager,
    ) {}

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function newCategory(string $name): Category
    {
        $model = new Category($name);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return $model;
    }
}