<?php

require_once __DIR__ . "/../../Utils.php";

function bookReturn(array $rents, array $books, array $author, array $genre)
{
    while (true) {
        if (isEmpty($rents) == 0) {
            echo "Kamu belum meminjam buku apapun :(";
            break;
        } else {
            echo "PENGEMBALIAN" . PHP_EOL;


            // TODO: Seharusnya yang dicari adalah judul-judul buku yang ada di $rents db..

            // lakukan pencarian untuk judul buku yang akan di tutup proses transaksinya
            echo "======" . PHP_EOL;
            $search = searchForBookAndTenant($rents, $books, $author, $genre);
            // tampilkan juga penyewa buku, $rent["bookId"] => $search["id"];
            $temp = [];
            for ($i = 0; $i < count($search); $i++) {
                if ($rents[$i]["bookId"] ==  $search[$i]["id"]) {
                    $temp[] = $rents[$i];
                }
            }
            // showTenant($temp);
            echo "======" . PHP_EOL;
            
            $target = getIndex($search, "Pilih transaksi sewa buku yang akan ditutup: ");
            if (confirm("Lanjutkan proses pengembalian (y/n)? ") == false) {
                echo "Penutupan sewa buku dibatalkan";
                break;
            } else {
                $id = $temp[$target - 1]["id"];
                for ($i = 0; $i < count($rents); $i++) {
                    if ($id == $rents[$i]["id"]) {
                        $rents[$i]["isReturned"] = true;
                        $rents[$i]["returnedOn"] = time();
                    }
                }
            }

            echo "Data sewa buku telah ditutup!" . PHP_EOL;
            if (confirm("Apakah kamu ingin melanjutkan penutupan transaksi (y/n)? ")) {
                bookReturn($rents, $books, $author, $genre);
            }
        }
        return $rents;
    }
}

function searchForBookAndTenant(array $rent, array $books, array $author, array $genre)
{
    while (true) {
        if (isEmpty($rent) == 0) {
            echo "Kamu belum menambahkan data peminjamnan :(";
            break;
        } else {
            echo "Pencarian judul buku: ";
            $title = getStringInput();
            $temp = [];

            for ($i = 0; $i < count($books); $i++) {
                if (preg_match("/$title/i", $books[$i]["title"])) {
                    if (in_array($books[$i]["title"], $temp) == false) {
                        $temp[] = $books[$i];
                    }
                }
            }

            if (count($temp) == 0) {
                echo "Maaf, tidak ada buku dengan menggunakan kata kunci tsb." . PHP_EOL;
                return null;
            } else {
                echo "Hasil pencarian: " . PHP_EOL;
                echo "Transaksi sewa: \n";
                for ($i = 0; $i < count($temp); $i++) {
                    for ($i = 0; $i < count($rent); $i++) {
                        if ($rent[$i]["bookId"] == $temp[$i]["id"]) {
                            echo $i + 1 . ". " . showBook(books: $temp[$i], author: $author, genre: $genre)
                                . "\n", showTenant($rent) . PHP_EOL;
                        }
                    }
                }
            }
        }
        return $temp;
    }
}