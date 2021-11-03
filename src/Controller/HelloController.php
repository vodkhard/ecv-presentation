<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Services\Blogs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/blogs/create", methods={"GET", "POST"})
     */
    public function create(Request $request, Blogs $blogsService)
    {
        $blog = new Blog();

        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogsService->create($blog);

            return $this->redirectToRoute('blogs_list');
        }

        return $this->render('blogs/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blogs", name="blogs_list", methods={"GET"})
     */
    public function list(BlogRepository $blogRepository)
    {
        $blogs = $blogRepository->findAll();

        return $this->render('blogs/list.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    /**
     * @Route("/blogs/{id}", name="blog_show", requirements={"id"="\d+"})
     */
    public function show(Blog $blog)
    {
        return $this->render('blogs/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/blogs/{id}/edit", name="blog_edit", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function edit(
        Blog $blog,
        Request $request,
        EntityManagerInterface $em
    ) {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('blog_show', ['id' => $blog->getId()]);
        }

        return $this->render('blogs/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
