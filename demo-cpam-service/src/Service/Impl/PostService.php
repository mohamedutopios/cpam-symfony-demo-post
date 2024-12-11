<?php

namespace App\Service\Impl;

use App\Entity\Post;
use App\Service\PostServiceInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PostService implements PostServiceInterface
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function initializeData(): void
    {
        if (!$this->session->has('posts')) {
            $posts = [
                1 => new Post(1, 'Post 1', 'This is the first post.', new \DateTime('-2 days')),
                2 => new Post(2, 'Post 2', 'This is the second post.', new \DateTime('-1 days')),
            ];
            $this->session->set('posts', $posts);
        }
    }

    public function getPosts(): array
    {
        return $this->session->get('posts', []);
    }

    public function getPostById(int $id): ?Post
    {
        $posts = $this->getPosts();
        return $posts[$id] ?? null;
    }

    public function addPost(string $title, string $content): void
    {
        $posts = $this->getPosts();
        $newId = count($posts) + 1;

        $posts[$newId] = new Post($newId, $title, $content, new \DateTime());
        $this->session->set('posts', $posts);
    }

    public function deletePost(int $id): void
    {
        $posts = $this->getPosts();
        if (isset($posts[$id])) {
            unset($posts[$id]);
            $this->session->set('posts', $posts);
        }
    }
}