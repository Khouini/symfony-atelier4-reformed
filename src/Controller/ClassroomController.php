<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Repository\ClassroomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ClassroomController extends AbstractController
{
    #[Route('/classroom/read', name: 'app_classroom_read')]
    public function read(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Classroom::class);
        $list = $repository->findAll();
        return $this->render('classroom/read.html.twig', [
            'classrooms' => $list,
        ]);
    }
    #[Route('/classroom/read2', name: 'app_classroom_read2')]
    public function read2(ClassroomRepository $repository): Response
    {
        $list = $repository->findAll();
        return $this->render('classroom/read.html.twig', [
            'classrooms' => $list,
        ]);
    }

    #[Route('/classroom/delete/{id}', name: 'app_classroom_delete')]
    public function delete(
        ClassroomRepository $repository,
        $id,
        ManagerRegistry $doctrine
    ): Response {
        $em = $doctrine->getManager();
        $classroom = $repository->find($id);
        $em->remove($classroom);
        $em->flush();
        // update table (flush)
        return $this->redirectToRoute('app_classroom_read');
    }

    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
}
