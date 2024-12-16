<?php

namespace App\Service\Impl;

use App\DTO\PostDTO;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\CategoryServiceInterface;
use App\Service\PostServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PostService implements PostServiceInterface
{
    private PostRepository $postRepository;
    private CategoryServiceInterface $categoryService;
    private EntityManagerInterface $entityManager;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager, CategoryServiceInterface $categoryService)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
        $this->$categoryService = $categoryService;
    }


    public function getPosts(): array
    {
        return $this->postRepository->findAll();
    }


    public function getPostById(int $id): ?Post
    {
        return $this->postRepository->find($id);
    }


    public function addPost(PostDTO $postDTO): void
    {
        $category = $this->categoryService->getCategoryById($postDTO->getCategoryId());

        $post = new Post();
        $post->setTitle($postDTO->getTitle())
            ->setContent($postDTO->getContent())
            ->setCreatedAt(new \DateTime())
            ->setCategory($category);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }


    public function updatePost(Post $post, PostDTO $postDTO): void
    {
        $category = $this->categoryService->getCategoryById($postDTO->categoryId);

        if (!$category) {
            throw new \InvalidArgumentException('Category not found.');
        }

        $post->setTitle($postDTO->title)
            ->setContent($postDTO->content)
            ->setCategory($category);

        $this->entityManager->flush();
    }

    public function deletePost(int $id): void
    {
        $post = $this->postRepository->find($id);
        if ($post) {
            $this->entityManager->remove($post);
            $this->entityManager->flush();
        }
    }

}