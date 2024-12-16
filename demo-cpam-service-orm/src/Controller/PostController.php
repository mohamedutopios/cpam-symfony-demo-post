<?php

namespace App\Controller;


use App\DTO\PostDTO;
use App\Service\CategoryServiceInterface;
use App\Service\PostServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    private PostServiceInterface $postManager;

    private CategoryServiceInterface $categoryService;

    public function __construct(PostServiceInterface $postManager, CategoryServiceInterface $categoryService)
    {
        $this->postManager = $postManager;
        $this->categoryService = $categoryService;

    }
    #[Route('/post', name: 'post_index', methods: ['GET'])]
    public function index(): Response
    {
        $posts = $this->postManager->getPosts();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/{id}/edit', name: 'post_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $post = $this->postManager->getPostById($id);

        if (!$post) {
            throw $this->createNotFoundException(sprintf('Post with ID %d not found.', $id));
        }

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $content = $request->request->get('content');
            $categoryId = $request->request->get('category_id');

            if (empty($title) || empty($content) || empty($categoryId)) {
                $this->addFlash('error', 'Title, content, and category are required.');
                return $this->redirectToRoute('post_edit', ['id' => $id]);
            }

            try {
                $postDTO = new PostDTO($title, $content, (int)$categoryId);
                $this->postManager->updatePost($post, $postDTO);
                $this->addFlash('success', 'Post updated successfully!');
                return $this->redirectToRoute('post_index');
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        $categories = $this->categoryService->getAllCategories();

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'categories' => $categories,
        ]);
    }


    #[Route('/post/create', name: 'post_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $content = $request->request->get('content');
            $categoryId = $request->request->get('category_id');

            if (empty($title) || empty($content) || empty($categoryId)) {
                $this->addFlash('error', 'Title, content, and category are required.');
                return $this->redirectToRoute('post_create');
            }

            try {
                $postDTO = new PostDTO($title, $content, (int)$categoryId);
                $this->postManager->addPost($postDTO);
                $this->addFlash('success', 'Post created successfully!');
                return $this->redirectToRoute('post_index');
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        $categories = $this->categoryService->getAllCategories();

        return $this->render('post/create.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/post/{id}/delete', name: 'post_delete', methods: ['POST'])]
    public function delete(int $id): RedirectResponse
    {
        $post = $this->postManager->getPostById($id);

        if (!$post) {
            $this->addFlash('error', sprintf('The post with ID %d was not found.', $id));
            return $this->redirectToRoute('post_index');
        }

        $this->postManager->deletePost($id);
        $this->addFlash('success', sprintf('Post with ID %d was deleted successfully.', $id));

        return $this->redirectToRoute('post_index');
    }

    #[Route('/post/{id}', name: 'post_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $post = $this->postManager->getPostById($id);

        if (!$post) {
            throw $this->createNotFoundException(sprintf('The post with ID %d was not found.', $id));
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

}
