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

    #[Route('/posts/frame', name: 'post_list_frame', methods: ['GET'])]
    public function listFrame(Request $request): Response
    {
        $posts = $this->postManager->getPosts();

        return $this->render('post/_list.html.twig', [
            'posts' => $posts,
        ]);
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
                return $this->render('post/_form.html.twig', [
                    'error' => 'Title, content, and category are required.',
                    'categories' => $this->categoryService->getAllCategories(),
                    'post' => $post,
                    'action' => $this->generateUrl('post_edit', ['id' => $id]),
                    'button_label' => 'Update',
                ]);
            }

            try {
                $postDTO = new PostDTO($title, $content, (int)$categoryId);
                $this->postManager->updatePost($post, $postDTO);

                // Réutilisation du fichier create_and_list_stream.html.twig
                return $this->render('post/create_and_list_stream.html.twig', [
                    'posts' => $this->postManager->getPosts(),
                ], new Response('', 200, ['Content-Type' => 'text/vnd.turbo-stream.html']));
            } catch (\InvalidArgumentException $e) {
                return $this->render('post/_form.html.twig', [
                    'error' => $e->getMessage(),
                    'categories' => $this->categoryService->getAllCategories(),
                    'post' => $post,
                    'action' => $this->generateUrl('post_edit', ['id' => $id]),
                    'button_label' => 'Update',
                ]);
            }
        }

        return $this->render('post/_form.html.twig', [
            'categories' => $this->categoryService->getAllCategories(),
            'post' => $post,
            'action' => $this->generateUrl('post_edit', ['id' => $id]),
            'button_label' => 'Update',
        ]);
    }



    #[Route('/post/create', name: 'post_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $categories = $this->categoryService->getAllCategories();


        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $content = $request->request->get('content');
            $categoryId = $request->request->get('category_id');

            if (empty($title) || empty($content) || empty($categoryId)) {
                return $this->render('post/_form.html.twig', [
                    'categories' => $categories, // Liste des catégories pour le formulaire
                    'post' => null, // Aucun post n'est en cours de modification
                    'action' => $this->generateUrl('post_create'), // URL pour soumettre le formulaire
                    'button_label' => 'Create', // Texte du bouton d'action
                    'error' => 'All fields are required.', // Message d'erreur à afficher
                ]);
            }


                $postDTO = new PostDTO($title, $content, (int)$categoryId);
                $this->postManager->addPost($postDTO);

                return $this->render('post/create_and_list_stream.html.twig', [
                    'posts' => $this->postManager->getPosts(),
                ], new Response('', 200,['Content-Type' => 'text/vnd.turbo-stream.html']));

        }

        return $this->render('post/_form.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/post/{id}/delete', name: 'post_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $post = $this->postManager->getPostById($id);

        if (!$post) {
            return $this->render('post/_list.html.twig', [
                'error' => 'Post with ID ' . $id . ' not found.',
                'posts' => $this->postManager->getPosts(),
            ]);
        }
        $this->postManager->deletePost($id);
        return $this->render('post/_list.html.twig', [
            'posts' => $this->postManager->getPosts(),
        ]);
    }

    #[Route('/post/{id}', name: 'post_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $post = $this->postManager->getPostById($id);

        if (!$post) {
            throw $this->createNotFoundException(sprintf('The post with ID %d was not found.', $id));
        }

        return $this->render('post/_details.html.twig', [
            'post' => $post,
        ]);
    }

}
