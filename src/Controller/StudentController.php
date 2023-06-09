<?php

namespace App\Controller;

use App\Entity\Student;
// use App\Form\SearchStudentType;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/student/read', name: 'app_student_read')]
    public function read(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Student::class);
        $list = $repository->findAll();
        return $this->render('student/read.html.twig', [
            'students' => $list,
        ]);
    }

    #[Route('/student/create', name: 'app_student_create')]
    public function create(
        ManagerRegistry $doctrine,
        Request $request,
        StudentRepository $studentRepository,
    ): Response {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            // $studentRepository->save($student, true);
            $em->persist($student);
            $em->flush();
            return $this->redirectToRoute('app_student_read');
        }
        return $this->renderForm('student/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/student/edit/{id}', name: 'app_student_edit')]
    public function edit(
        StudentRepository $repository,
        $id,
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $em = $doctrine->getManager();
        $student = $repository->find($id);
        $form = $this->createForm(StudentType::class, $student);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($student);
            $em->flush();
            return $this->redirectToRoute('app_student_read');
        }
        return $this->renderForm('student/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/student/delete/{id}', name: 'app_student_delete')]
    public function delete(
        StudentRepository $repository,
        $id,
        ManagerRegistry $doctrine
    ): Response {
        $em = $doctrine->getManager();
        $student = $repository->find($id);
        $em->remove($student);
        $em->flush();
        // update table (flush)
        return $this->redirectToRoute('app_student_read');
    }
}
