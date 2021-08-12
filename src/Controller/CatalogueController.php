<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatalogueController extends AbstractController
{
    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function index(BookRepository $bookRepository): Response
    {

        return $this->render('catalogue/index.html.twig', [
            'books' => $bookRepository->findAll(),

        ]);
    }
}
