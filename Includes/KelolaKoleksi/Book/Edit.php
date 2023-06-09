<?php

require_once __DIR__ . "/../../../Utils.php";
require_once __DIR__ . "/../../../NewBookUtils.php";

function editBook(array $book, array $author, array $genre)
{
    while (true) {
        if (isEmpty($book) == 0) {
            echo "Tidak bisa mengedit, karena kamu belum menambahkan data buku :(" . PHP_EOL;
            break;
        } else {
            $search = searchBook($book, $author, $genre, "======");
            if ($search == null) {
                break;
            } else {
                echo "======" . PHP_EOL;
                $indexOfBook = getIndex($search, "Pilih buku yang akan diperbarui: ") . PHP_EOL;
                echo "Memperbarui Data Buku" . PHP_EOL;
                $newBook = askForNewBook();

                $id = $search[$indexOfBook - 1]["id"];
                for ($i = 0; $i < count($book); $i++) {
                    if ($id == $book[$i]["id"]) {
                        $book[$i] = $newBook;
                    }
                }
            }
            echoBook($newBook, $genre, $author);
            echo "Buku dengan judul " . '"' . ucwords($newBook["title"]) . '"' . " telah diperbarui" . PHP_EOL;
        }
        return $book;
    }
}
