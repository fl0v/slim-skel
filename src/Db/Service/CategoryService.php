<?php

namespace App\Db\Service;

use App\Db\Entity\Category;
use Doctrine\ORM\EntityManager;

final readonly class CategoryService
{
    public function __construct(
        private EntityManager $entityManager,
    ) {}

    public function newCategory(string $name): Category
    {
        $model = new Category($name);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return $model;
    }
}