<?php

require_once __DIR__ . "/../../../Utils.php";
require_once __DIR__ . "/../../../NewBookUtils.php";

/**
 * @param array book, @param array author, @param array genre
 * @return array global books yang telah di edit
 */
function editBook(array $book, array $author, array $genre)
{
    while (true) {
        //pengeditan tidak bisa dilakukan karena data buku masih kosong di database penyewaan
        if (isEmpty($book) == 0) {
            echo "Tidak bisa mengedit, karena kamu belum menambahkan data buku :(" . PHP_EOL;
            break;
        } else {
            // melakukan pencarian buku
            $search = searchBook($book, $author, $genre, "======");
            if ($search == null) {
                return null;
            } else {
                echo "======" . PHP_EOL;
                // mendapatkan nomor urut dari buku yang telah dicari
                $indexOfBook = getIndex($search, "Pilih buku yang akan diperbarui: ") . PHP_EOL;
                echo "Memperbarui Data Buku" . PHP_EOL;
                // meminta data buku baru, yang nantinya akan mengganti data dari buku yang dilakukan pengeditan
                $newBook = askForNewBook();

                // mendapatkan 'id' dari buku yang akan di edit
                $id = $search[$indexOfBook - 1]["id"];
                // lakukan pencarian untuk $id
                for ($i = 0; $i < count($book); $i++) {
                    if ($id == $book[$i]["id"]) {
                        // tumpuk hasilnya ke buku dengan id = $id;
                        $book[$i] = $newBook;
                    }
                }
            }
            if ($newBook != null) {
                // menampilkan konfirmasi buku yang telah di edit
                echoBook($newBook, $genre, $author);
                echo "Buku dengan judul " . '"' . ucwords($newBook["title"]) . '"' . " telah diperbarui" . PHP_EOL;
            }
        }
        saveBookintoJson($book);
        return $book;
    }
}
