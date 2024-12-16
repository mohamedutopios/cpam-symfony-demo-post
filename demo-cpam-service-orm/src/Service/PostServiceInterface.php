<?php

namespace App\Service;

use App\DTO\PostDTO;
use App\Entity\Post;

interface PostServiceInterface
{

    public function getPosts(): array;

    public function getPostById(int $id): ?Post;

    public function addPost(PostDTO $postDTO): void;


    public function deletePost(int $id): void;


}