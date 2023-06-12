<?php

require_once __DIR__ . "/../../../Utils.php";

/**
 * @param array author @param array books
 * function menghapus data author
 */
function deleteAuthor(array $author, array $books)
{
    while (true) {
        // penghapusan tidak dapat dilakukan saat data kosong
        if (isEmpty($author) == 0) {
            echo "Tidak bisa menghapus, karena kamu belum menambahkan data author :(" . PHP_EOL;
            break;
        } else {
            // melakukan search untuk judul buku yang akan di hapus
            $search = searchAuthors($author);
            if ($search != null) {
                echo "======" . PHP_EOL;
                // meminta nomor urut yang akan dihapus dari hasil pencarian
                $indexOfAuthor = getIndex($search, "Pilih penulis yang akan dihapus: ");
                // nomor urut akan di - 1 untuk mencari, data yang akan dihapus (karena array dimulai dari 0)
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

                            // hapus data penulis dari database
                            if (confirm("Hapus penulis " . '"' . ucwords($nama) . '"' . " (y/n)? ") == true) {
                                unset($author[$i]);
                                echo "Penulis " . '"' . ucwords($nama) . '"' . " telah dihapus" . PHP_EOL;
                                // set kembali no index dari array
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
