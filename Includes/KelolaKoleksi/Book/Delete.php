<?php

require_once __DIR__ . "/../../../Utils.php";
require_once __DIR__ . "/../../../NewBookUtils.php";

/**
 * @param array books, @param array author, @param array genre, dan @param aray rent
 * @return array books dengan data baru (karena telah dilakukan penghapusan)
 * function menghapus data buku yang ada di global rent
 */
function deleteBook(array $books, array $author, array $genre, array $rent)
{
    while (true) {
        // penghapusan tidak bisa dilakukan ketika database buku kosong
        if (isEmpty($books) == 0) {
            echo "Tidak bisa menghapus, karena kamu belum menambahkan data buku :(" . PHP_EOL;
            break;
        } else {
            // seperti biasa, akan melakukan pencarian dulu disini
            $search = searchBook($books, $author, $genre, "======");
            if ($search == null) {
                break;
            } else {
                echo "======" . PHP_EOL;
                // meminta nomor urut yang akan di hapus
                $indexOfBook = getIndex($search, "Pilih buku yang akan dihapus: ") . PHP_EOL;
                // mengambil 'id' untuk buku yang akan di hapus
                $idBookToBeDeleted = $search[$indexOfBook - 1]["id"];

                // cek apakah buku yang akan dihapus id nya ada di menu penyewaan
                for ($i = 0; $i < count($books); $i++) {
                    if ($idBookToBeDeleted == $books[$i]["id"]) {
                        // mendapatkan data apakah buku ada di database penyewaan
                        $rentedBook = getFirstDataFromArray($rent, $idBookToBeDeleted, "bookId");

                        if ($rentedBook != null) {
                            // tampilkan pesan berikut, jika ada di database penyewaan dan key['isReturned'] = false
                            echo "Tidak bisa menghapus, karena buku masih disewakan" . PHP_EOL;
                        } else {
                            // mendapatkan judul buku yang akan di hapus
                            $bookTitle = $books[$i]["title"];
                            echoBook($books[$i], $genre, $author);
                            // meminta konfirmasi penghapusan
                            if (confirm("Hapus data buku ini (y/n)? ") == true) {
                                // hapus data buku yang ke $i
                                unset($books[$i]);
                                echo "Buku dengan judul " . '"' . ucwords($bookTitle) . '"' . " sudah dihapus" . PHP_EOL;
                                // atur ulang array buku, agar indexnya berurutan lagi
                                $books = array_values($books);
                            } else {
                                // tampilkan pesan ini, jika function confirm == false (pilihan "n")
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
