<?php

function deleteAuthor(array $author, array $books)
{
    while (true) {
        if (isEmpty($author) == 0) {
            echo "Tidak bisa menghapus, karena kamu belum menambahkan data author :(" . PHP_EOL;
            break;
        } else {

            $search = searchAuthors($author);
            echo "======" . PHP_EOL;
            $indexOfAuthor = getIndex($search, "Pilih penulis yang akan dihapus: ");

            $idAuthorToBeDeleted = $search[$indexOfAuthor - 1]["id"];

            for ($i = 0; $i < count($author); $i++) {
                if ($idAuthorToBeDeleted == $author[$i]["id"]) {

                    $bookAuthor = authorBooksId($books, $idAuthorToBeDeleted);
                    if ($bookAuthor != null) {
                        echo "Penulis tidak bisa dihapus, karena sudah memiliki data buku yang terpublikasikan" . PHP_EOL;
                    } else {
                        for ($i = 0; $i < count($author); $i++) {
                            $nama = "";
                            if ($idAuthorToBeDeleted == $author[$i]["id"]) {
                                $nama = $author[$i]["name"];

                                $sentence = "Hapus penulis \"$nama\" (y/n)? ";
                                if (confirm($sentence) == true) {
                                    unset($author[$i]);
                                    echo "Penulis " . '"' . ucwords($nama) . '"' . " telah dihapus" . PHP_EOL;
                                    $author = array_values($author);
                                } else {
                                    echo "Penghapusan dibatalkan" . PHP_EOL;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        break;
    }
    return $author;
}
