<?php

require_once __DIR__ . "/../../Utils.php";
require_once __DIR__ . "/../../RentalUtils.php";

/**
 * @param array rent, @param array books, @param array author, @param array genre
 * function menerima data penyewaan buku baru
 */
function addRent(array $rent, array $books, array $authors, array $genres)
{
    while (true) {
        // peminjaman tidak dapat dilakukan ketika database buku kosong
        if (isEmpty($books) == 0) {
            echo "Data buku kosong, tidak ada buku yang bisa dipinjam :(" . PHP_EOL;
            break;
        } else {
            // melakukan pencarian buku
            $search = searchBook($books, $authors, $genres, "======");
            if ($search == null) {
                break;
            } else {
                echo "======" . PHP_EOL;
                // meminta nomor urut buku yang akan disewakan
                $target = getIndex($search, "Pilih buku yang akan disewa: ");
                $id = $search[$target - 1]["id"];
                echo "======" . PHP_EOL;
                echo "TAMBAH PEMINJAMAN" . PHP_EOL;

                // mencari buku dengan id dari var $id
                for ($j = 0; $j < count($books); $j++) {
                    if ($id == $books[$j]["id"]) {
                        // jika ditemukan maka simpan var $idBook isinya array $book(dengan $id)
                        $idBook = $books[$j];
                    }
                }

                for ($i = 0; $i < count($search); $i++) {

                    // meminta data si penyewa
                    $newRent = askForNewRent($rent, $idBook);
                    if ($newRent == null) {
                        return null;
                    } else {
                        echo "======" . PHP_EOL;
                        // mengkonfirmasi, apakah data penyewaan di lanjutkan
                        if (confirm("Simpan data buku ini (y/n)? ") == false) {
                            echo "Penyewaan buku dibatalkan" . PHP_EOL;
                            break;
                        } else {
                            // global rent akan ditambahkan dengan data baru yang telah dilakukan di atas
                            $rent[] = $newRent;
                            echo "Data sewa buku telah disimpan!" . PHP_EOL;
                            break;
                        }
                    }
                }
            }
        }
        saveRentsintoJson($rent);
        return $rent;
    }
}
