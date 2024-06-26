<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/post')]
class PostController extends AbstractController
{
  #[Route('/', name: 'app_post_index', methods: ['GET'])]
  public function index(PostRepository $postRepository): Response
  {
    return $this->render('post/index.html.twig', [
      'posts' => $postRepository->findAllPostsWithAuthor(),
    ]);
  }

  #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $post = new Post();
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $post->setAuthor($this->getUser());
      $entityManager->persist($post);
      $entityManager->flush();

      $this->addFlash('success', 'Your post has been created!');

      return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('post/new.html.twig', [
      'post' => $post,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
  public function show(Post $post): Response
  {
    return $this->render('post/show.html.twig', [
      'post' => $post,
    ]);
  }

  #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
  {
    if ($this->getUser() !== $post->getAuthor()) {
      $this->addFlash('error', 'You are not allowed to edit this post!');
      return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('post/edit.html.twig', [
      'post' => $post,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
  public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->getPayload()->get('_token'))) {
      $entityManager->remove($post);
      $entityManager->flush();
      $this->addFlash('success', 'Your post has been deleted!');
    }

    return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
  }
}
