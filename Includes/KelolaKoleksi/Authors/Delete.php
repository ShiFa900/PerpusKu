<?php

require_once __DIR__ . "/../../../Utils.php";

function deleteAuthor(array $author, array $books)
{
    while (true) {
        if (isEmpty($author) == 0) {
            echo "Tidak bisa menghapus, karena kamu belum menambahkan data author :(" . PHP_EOL;
            break;
        } else {

            $search = searchAuthors($author);
            if ($search != null) {
                echo "======" . PHP_EOL;
                $indexOfAuthor = getIndex($search, "Pilih penulis yang akan dihapus: ");

                $idAuthorToBeDeleted = $search[$indexOfAuthor - 1]["id"];

                for ($i = 0; $i < count($author); $i++) {
                    if ($idAuthorToBeDeleted == $author[$i]["id"]) {
                        // dapatkan setidaknya satu book yg dipublikasikan oleh $author[$i]
                        $authorBooks = getFirstDataFromArray($books, $idAuthorToBeDeleted, "authorId");

                        // jika si penulis blm pernah mempublikasikan buku maka:
                        if ($authorBooks != null) {
                            echo "Penulis tidak bisa dihapus, karena sudah memiliki data buku yang terpublikasikan" . PHP_EOL;
                        } else {
                            $nama = $author[$i]["name"];

                            if (confirm("Hapus penulis " . '"' . ucwords($nama) . '"' . " (y/n)? ") == true) {
                                unset($author[$i]);
                                echo "Penulis " . '"' . ucwords($nama) . '"' . " telah dihapus" . PHP_EOL;
                                $author = array_values($author);
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
    saveAuthorintoJson($author);
    return $author;
}
