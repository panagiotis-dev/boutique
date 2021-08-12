<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Service\BookPricerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BookController extends AbstractController
{
    /**
     * @Route("admin/book/", name="book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('admin/book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("admin/book/new", name="book_new", methods={"GET","POST"})
     */
    public function new(Request $request, BookPricerService $bookPricerService): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookPricerService->computerPrice($book);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    /**
     * @Route("admin/book/{id}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('admin/book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("admin/book/{id}/edit", name="book_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book, BookPricerService $bookPricerService): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bookPricerService->computerPrice($book);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    /**
     * @Route("admin/book/{id}", name="book_delete", methods={"POST"})
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
    }



}
