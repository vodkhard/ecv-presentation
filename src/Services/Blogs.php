<?php

namespace App\Services;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;

class Blogs
{
    private EntityManagerInterface $em;
    private BlogRepository $blogRepo;

    public function __construct(
        EntityManagerInterface $em,
        BlogRepository $blogRepository
    ) {
        $this->em = $em;
        $this->blogRepo = $blogRepository;
    }

    public function findAll()
    {
        return $this->blogRepo->findAll();
    }

    public function findBlog(int $id)
    {
        return $this->blogRepo->find($id);
    }

    public function create(Blog $blog)
    {
        $this->em->persist($blog);
        $this->em->flush();

        return $blog;
    }
}
