<?php

require_once __DIR__ . "/../../../Utils.php";
require_once __DIR__ . "/../../../NewBookUtils.php";

/**
 * @param array books, @param array genre, dan @param array author
 */
function addBook(array $books, array $genres, array $authors)
{
    // meminta data buku baru dari function di bawah ini, yang return value berupa array
    $newBook = askForNewBook();
    if ($newBook == null) {
        return null;
    } else {
        // menampilkan konfirmasi data buku
        echoBook($newBook, $genres, $authors);
        if (confirm("Simpan data buku ini (y/n)? ") == false) {
            echo "Data buku batal disimpan" . PHP_EOL;
        } else {
            // simpan data buku pada global database
            $books[] = $newBook;
            echo "Buku dengan judul " . '"' . ucwords($newBook["title"]) . '"' . " telah disimpan" . PHP_EOL;
            echo "======" . PHP_EOL;
        }
    }
    saveBookintoJson($books);
    return $books;
}
