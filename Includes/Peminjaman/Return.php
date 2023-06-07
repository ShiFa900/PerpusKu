<?php

require_once __DIR__ . "/../../Utils.php";

function bookReturn(array $rents, array $books, array $author, array $genre)
{
    // while (true) {
    if (isEmpty($rents) == 0) {
        echo "Kamu belum meminjam buku apapun :(";
        // break;
    } else {
        echo "PENGEMBALIAN" . PHP_EOL;

        // lakukan pencarian untuk judul buku yang akan di tutup proses transaksinya
        echo "======" . PHP_EOL;

        // watchout, $result isinya adalah array of 4 data
        $result = searchBookToBeReturned($rents, $books, $author, $genre);

        if ($result != null) {
            echo "======" . PHP_EOL;

            // TODO: tampilkan
            for ($i = 0; $i < count($result); $i++) {
                $theRent = $result[$i][0];
                $theBook = $result[$i][1];
                $theBookAuthor = $result[$i][2];
                $theBookGenre = $result[$i][3];

                // mencari keterlambatan pengembalian buku
                $time = time();
                $diff = $time - $theRent["shouldReturnedOn"];

                // show...
                echo "Transaksi sewa: " . PHP_EOL;
                echo $i + 1 . ". " . $theRent["name"] . " (NIK: " . $theRent["nik"] . ") pada " .
                    date('j F Y', ceil($theRent["rentedOn"])) . " -> " . date('j F Y', ceil($theRent["shouldReturnedOn"]))
                    . " (telat " . floor(date('j', $diff / 60 * 60 * 24)) . " hari)" . PHP_EOL;
                echo $theBook["title"] . ", oleh " . $theBookAuthor["name"] . " - " .
                    $theBook["year"] . " (" . $theBookGenre["genre"] . ")" . PHP_EOL;
                echo "\n";
            }

            $target = getIndex($result, "Pilih transaksi sewa buku yang akan ditutup: ");

            if (confirm("Lanjutkan proses pengembalian (y/n)? ") == false) {
                echo "Penutupan sewa buku dibatalkan";
            } else {
                $theRentData = $result[$target - 1][0];
                for ($i = 0; $i < count($rents); $i++) {
                    if ($theRentData["id"] == $rents[$i]["id"]) {
                        $rents[$i]["isReturned"] = true;
                        $rents[$i]["returnedOn"] = time();
                        echo "Data sewa buku telah ditutup!" . PHP_EOL;
                        echo "======" . PHP_EOL;
                        break;
                    }
                }
            }
        }

        return $rents;
    }
}

function searchBookToBeReturned(array $rents, array $books, $authors, $genres)
{
    while (true) {
        if (isEmpty($rents) == 0) {
            echo "Data peminjaman kosong :(";
            break;
        } else {
            echo "Pencarian judul buku: ";
            $title = getStringInput();
            $bookWithTitles = getBooksByTitle($books, $title);

            if (count($bookWithTitles) == 0) {
                echo "Maaf, tidak ada buku dengan judul tsb. \n" . PHP_EOL;
                break;
            } else {
                $temp = [];

                for ($i = 0; $i < count($rents); $i++) {
                    for ($j = 0; $j < count($bookWithTitles); $j++) {
                        if ($rents[$i]["bookId"] == $bookWithTitles[$j]["id"] && $rents[$i]["isReturned"] == false) {
                            $temp[] = [
                                // data rent:
                                0 => $rents[$i],
                                // data si buku:
                                1 => $bookWithTitles[$j],
                                // penulis si buku:
                                2 => getFirstDataFromArray($authors, $bookWithTitles[$j]["authorId"], "id"),
                                // genre si buku:
                                3 => getFirstDataFromArray($genres, $bookWithTitles[$j]["genreId"], "id")
                            ];
                        }
                    }
                }

                return $temp;
            }
        }
    }
    return null;
}
