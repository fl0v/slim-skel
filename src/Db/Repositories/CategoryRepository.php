<?php

namespace App\Db\Repositories;

use App\Db\Entities\Category;
use Doctrine\ORM\EntityManager;

final readonly class CategoryRepository
{
    public function __construct(
        private EntityManager $entityManager,
    ) {
    }

    public function newCategory(string $name): Category
    {
        $model = new Category($name);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return $model;
    }
}
