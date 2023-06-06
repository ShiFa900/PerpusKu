<?php

require_once "Utils.php";

function addRent(array $rent, array $books, array $authors, array $genres)
{
    while (true) {
        if (isEmpty($books) == 0) {
            echo "Data buku kosong, tidak ada buku yang bisa dipinjam :(" . PHP_EOL;
            break;
        } else {
            $search = searchBook($books, $authors, $genres, "======");
            if ($search == null) {
                break;
            } else {
                echo "======" . PHP_EOL;
                getIndex($search, "Pilih buku yang akan disewa: ");
                echo "======" . PHP_EOL;
                echo "TAMBAH PEMINJAMAN" . PHP_EOL;

                for ($i = 0; $i < count($search); $i++) {
                    $newRent = askForRent($rent, $search[$i]["id"]);
                    if ($newRent == null) {
                        return null;
                    } else {
                        echo "======" . PHP_EOL;
                        if (confirm("Simpan data buku ini (y/n)? ") == false) {
                            echo "Penyewaan buku dibatalkan" . PHP_EOL;
                            break;
                        } else {
                            $rent[] = $newRent;
                            echo "Data sewa buku telah disimpan!" . PHP_EOL;
                            break;
                        }
                    }
                }
            }
        }
        return $rent;
    }
}
