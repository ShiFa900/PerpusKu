<?php

require_once "Utils.php";

function bookReturn(array $rents, array $books, array $author, array $genre)
{
    while (true) {
        if (isEmpty($rents) == 0) {
            echo "Kamu belum meminjam buku apapun :(";
            break;
        } else {
            echo "PENGEMBALIAN" . PHP_EOL;
            // lakukan pencarian untuk judul buku yang akan di tutup proses transaksinya
            echo "======" . PHP_EOL;
            $search = searchBook($books, $author, $genre, "Transaksi sewa: ");
            // tampilkan juga penyewa buku, $rent["bookId"] => $search["id"];
            $temp = [];
            for ($i = 0; $i < count($search); $i++) {
                if ($rents[$i]["bookId"] ==  $search[$i]["id"]) {
                    $temp[] = $rents[$i];
                }
            }
            showTenant($temp);
            echo "======" . PHP_EOL;
            $target = getIndex($search, "Pilih transaksi sewa buku yang akan ditutup: ");
            $id = $temp[$target - 1]["id"];
            for ($i = 0; $i < count($rents); $i++) {
                if ($id == $rents[$i]["id"]) {
                    $rents[$i]["isReturned"] = true;
                    $rents[$i]["returnedOn"] = time();
                }
            }

            return $rents;
        }
    }
}
