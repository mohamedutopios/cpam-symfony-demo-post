<?php

namespace App\Service\Impl;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService implements CategoryServiceInterface
{
private CategoryRepository $categoryRepository;
private EntityManagerInterface $entityManager;

    /**
     * @param CategoryRepository $categoryRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }


    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    public function createCategory(string $name): Category
    {
        $category = new Category();
        $category->setName($name);
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        return $category;
    }

    public function updateCategory(int $id, string $newName): void
    {
        $category = $this->getCategoryById($id);
        $category->setName($newName);
        $this->entityManager->persist($category);
        $this->entityManager->flush();

    }

    public function deleteCategory(int $id): void
    {
      $category = $this->getCategoryById($id);
      if(!$category){
          throw new InvalidArgumentException("Category Id not found");
      }
      $this->entityManager->remove($category);
      $this->entityManager->flush();
    }
}