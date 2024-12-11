<?php

namespace App\Service\Impl;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\PostServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PostService implements PostServiceInterface
{
    private PostRepository $postRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    // Récupère tous les posts
    public function getPosts(): array
    {
        return $this->postRepository->findAll();
    }

    // Récupère un post par ID
    public function getPostById(int $id): ?Post
    {
        return $this->postRepository->find($id);
    }

    // Ajoute un nouveau post
    public function addPost(string $title, string $content): void
    {
        $post = new Post();
        $post->setTitle($title)
            ->setContent($content)
            ->setCreatedAt(new \DateTime());

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    // Met à jour un post existant
    public function updatePost(Post $post, string $title, string $content): void
    {
        $post->setTitle($title)
            ->setContent($content);

        $this->entityManager->flush(); // Sauvegarde les modifications
    }

    // Supprime un post par ID
    public function deletePost(int $id): void
    {
        $post = $this->postRepository->find($id);
        if ($post) {
            $this->entityManager->remove($post);
            $this->entityManager->flush();
        }
    }

    public function initializeData(): void
    {
        // TODO: Implement initializeData() method.
    }
}