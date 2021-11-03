<?php

namespace App\Controller;

use App\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/blogs/create", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {
        $blog = new Blog();

        $form = $this->createFormBuilder($blog)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine = $this->getDoctrine();
            $manager = $doctrine->getManager();
            $manager->persist($blog);
            $manager->flush();
        }

        return $this->render('blogs/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blogs")
     */
    public function list()
    {
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(Blog::class);
        $blogs = $repository->findAll();

        return $this->render('blogs/list.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    /**
     * @Route("/blogs/{id}", name="blog_show", requirements={"id"="\d+"})
     */
    public function show(int $id)
    {
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(Blog::class);
        $blog = $repository->find($id);

        return $this->render('blogs/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/blogs/{id}/edit", name="blog_edit", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function edit(Request $request, int $id)
    {
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(Blog::class);
        $blog = $repository->find($id);

        $form = $this->createFormBuilder($blog)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine = $this->getDoctrine();
            $manager = $doctrine->getManager();
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $blog->getId()]);
        }

        return $this->render('blogs/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
