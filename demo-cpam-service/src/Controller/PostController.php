<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\PostServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    private PostServiceInterface $postManager;

    public function __construct(PostServiceInterface $postManager)
    {
        $this->postManager = $postManager;

    }

    #[Route('/post', name: 'post_index', methods: ['GET'])]
    public function index(): Response
    {
        $posts =  $this->postManager->getPosts();

        if(!$posts){
            $this->postManager->initializeData();
        }

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
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


    #[Route('/post/{id}/edit', name: 'post_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        // Récupérer le post par son ID
        $post = $this->postManager->getPostById($id);

        // Vérifier si le post existe
        if (!$post) {
            throw $this->createNotFoundException(sprintf('The post with ID %d was not found.', $id));
        }

        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $title = $request->request->get('title');
            $content = $request->request->get('content');

            // Validation des données
            if (empty($title) || empty($content)) {
                $this->addFlash('error', 'Both title and content are required.');
                return $this->redirectToRoute('post_edit', ['id' => $id]);
            }

            // Mettre à jour le post
            $posts = $this->postManager->getPosts();
            $posts[$id]->setTitle($title);
            $posts[$id]->setContent($content);

            $this->postManager->initializeData(); // Réinitialiser les données dans la session
            $this->addFlash('success', 'Post updated successfully!');

            return $this->redirectToRoute('post_show', ['id' => $id]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
        ]);
    }



    #[Route('/post/create', name: 'post_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $content = $request->request->get('content');

            // Validation des données
            if (empty($title) || empty($content)) {
                $this->addFlash('error', 'Both title and content are required.');
                return $this->redirectToRoute('post_create');
            }

            $this->postManager->addPost($title, $content);

            $this->addFlash('success', 'Post created successfully!');
            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/create.html.twig');
    }

    #[Route('/post/delete/{id}', name: 'post_delete', methods: ['POST'])]
    public function delete(int $id): Response
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



}
