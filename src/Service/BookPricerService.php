<?php 


namespace App\Service;

use App\Entity\Book;

class BookPricerService{


    public function computerPrice(Book $book): void
    {
        // $book->setPrice(strlen($book->getDescription()));
        $desc = $book->getDescription();
        $newPrice = strlen($desc);
        $book->setPrice($newPrice);
    }


}