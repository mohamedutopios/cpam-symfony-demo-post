<?php

namespace App\Service;

use App\Entity\Post;

interface PostServiceInterface
{

    public function initializeData(): void;

    public function getPosts(): array;

    public function getPostById(int $id): ?Post;

    public function addPost(string $title, string $content, ): void;


    public function deletePost(int $id): void;


}