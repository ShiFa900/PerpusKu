<?php

require_once __DIR__ . "/../../../Utils.php";
require_once __DIR__ . "/../../../NewBookUtils.php";

function deleteBook(array $books, array $author, array $genre, array $rent)
{
    while (true) {
        if (isEmpty($books) == 0) {
            echo "Tidak bisa mengahapus, karena kamu belum menambahkan data buku :(" . PHP_EOL;
            break;
        } else {
            $search = searchBook($books, $author, $genre, "======");
            if ($search == null) {
                break;
            } else {
                echo "======" . PHP_EOL;
                $indexOfBook = getIndex($search, "Pilih buku yang akan dihapus: ") . PHP_EOL;

                $idBookToBeDeleted = $search[$indexOfBook - 1]["id"];

                // cek apakah buku yang akan dihapus id nya ada di menu penyewaan
                for ($i = 0; $i < count($books); $i++) {
                    if ($idBookToBeDeleted == $books[$i]["id"]) {
                        // get if the book if on the rents db
                        $rentedBook = getFirstDataFromArray($rent, $idBookToBeDeleted, "bookId");

                        if ($rentedBook != null) {
                            echo "Tidak bisa menghapus, karena buku masih disewakan" . PHP_EOL;
                        } else {
                            $bookTitle = $books[$i]["title"];
                            echoBook($books[$i], $genre, $author);

                            if (confirm("Hapus data buku ini (y/n)? ") == true) {
                                unset($books[$i]);
                                echo "Buku dengan judul " . '"' . ucwords($bookTitle) . '"' . " sudah dihapus" . PHP_EOL;
                                $books = array_values($books);
                            } else {
                                echo "Penghapusan dibatalkan" . PHP_EOL;
                            }
                            break;
                        }
                    }
                }
            }
        }
        break;
    }
    saveBookintoJson($books);
    return $books;
}
