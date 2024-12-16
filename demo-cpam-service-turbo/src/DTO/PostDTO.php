<?php

namespace App\DTO;

class PostDTO
{

    public string $title;
    public string $content;
    public int $categoryId;

    /**
     * @param string $title
     * @param string $content
     * @param int $categoryId
     */
    public function __construct(string $title, string $content, int $categoryId)
    {
        $this->title = $title;
        $this->content = $content;
        $this->categoryId = $categoryId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }




}