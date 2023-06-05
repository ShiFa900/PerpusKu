<?php

require_once "Utils.php";

function addBook(array $books, array $genres, array $authors)
{
    $newBook = askForNewBook();
    if ($newBook == null) {
        return null;
    } else {
        echoBook($newBook, $genres, $authors);
        if (confirm("Simpan data buku ini (y/n)? ") == false) {
            echo "Data buku batal disimpan" . PHP_EOL;
        } else {
            $books[] = $newBook;
            echo "Buku dengan judul " . '"' . ucwords($newBook["title"]) . '"' . " telah disimpan" . PHP_EOL;
            echo "======" . PHP_EOL;
        }
    }

    return $books;
}
