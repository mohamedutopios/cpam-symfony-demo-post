<?php

namespace App\Service;

use App\Entity\Category;

interface CategoryServiceInterface
{
    public function getCategoryById(int $id): ?Category;
    public function getAllCategories(): array;
    public function createCategory(string $name): Category;
    public function updateCategory(int $id, string $newName): void;
    public function deleteCategory(int $id): void;

}