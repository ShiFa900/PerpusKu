<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

require_once __DIR__ . "/../../Utils.php";
require_once __DIR__ . "/../../RentalUtils.php";

/**
 * @param array rents, @param array books, @param array author, @param array genre
 * function untuk penutupan sewa buku
 */
function bookReturn(array $rents, array $books, array $author, array $genre)
{
    foreach ($rents as $key) {
        // lakukan pengecekan disini, jika semua buku dalam databse sudah dikembalika alias 'true'
        // maka tampilkan bahwa data penyewaan kosong, dibawah ini adalah kondisi sebaliknya
        while ($key["isReturned"] != true) {
            echo "PENGEMBALIAN" . PHP_EOL;
            echo "======" . PHP_EOL;

            // lakukan pencarian untuk judul buku yang akan di tutup proses transaksinya
            // watchout, $result isinya adalah array of 4 data
            $result = searchBookToBeReturned($rents, $books, $author, $genre);

            if ($result != null) {

                echo "Transaksi sewa: " . PHP_EOL;
                echo "======" . PHP_EOL;
                // TODO: tampilkan
                for ($i = 0; $i < count($result); $i++) {
                    $theRent = $result[$i][0];
                    $theBook = $result[$i][1];
                    $theBookAuthor = $result[$i][2];
                    $theBookGenre = $result[$i][3];

                    showTransaction($theRent, $theBook, $theBookAuthor, $theBookGenre, $i);
                }

                $target = getIndex($result, "Pilih transaksi sewa buku yang akan ditutup: ");

                if (confirm("Lanjutkan proses pengembalian (y/n)? ") == false) {
                    echo "Penutupan sewa buku dibatalkan";
                } else {
                    $theRentData = $result[$target - 1][0];
                    for ($i = 0; $i < count($rents); $i++) {
                        if ($theRentData["id"] == $rents[$i]["id"]) {
                            // penutupan transaksi ditandai dengan pengubahan value dari key isReturned dan isReturned
                            // di bawah ini merupakan pengubahan value dari key yang tertera
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
    saveRentsintoJson($rents);
    echo "Tidak ada buku yang dipinjam" . PHP_EOL;
    return $rents;
}

/**
 * function untuk mencari buku yang akan di kembalikan
 * function pencarian ini berbeda dari function pencarian buku sebelumnya (return itemnya yang berbeda)
 * makanya dibuatkan function sendiri
 */
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

                // mencari dari data penyewaan, apakah dalam data $rent['bookId] sama dengan pencarian buku 'id'
                // dan jika key['isReturned] tidak sama dengan true
                for ($i = 0; $i < count($rents); $i++) {
                    for ($j = 0; $j < count($bookWithTitles); $j++) {
                        if ($rents[$i]["bookId"] == $bookWithTitles[$j]["id"] && $rents[$i]["isReturned"] == false) {
                            $temp[] = [
                                // jika benar, maka disimpan dalam array 3 dimensi seperti ini

                                // data rent:
                                0 => $rents[$i],
                                // data si buku:
                                1 => ($bookWithTitles[$j]),
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

/**
 * function untuk menampilkan data yang telah di olah-olah di atas
 */
function showTransaction(array $rents, array $books, array $author, array $genre, int $index)
{
    // mencari selisih antara tanggal sekarang dengan tanggal dimana buku seharusnya dikembalikan
    // perhitungan untuk mendapatkan ketelatan
    $lateInDays = getLateInDays($rents["rentedOn"], $rents["shouldReturnedOn"]);

    // show...
    echo $index + 1 . ". " . $rents["name"] . " (NIK: " . $rents["nik"] . ") pada " .
        date('j F Y', $rents["rentedOn"]) . " -> " . date('j F Y', $rents["shouldReturnedOn"]);

    // function mengecek keterlambatan pengembalian buku
    if ($lateInDays > 0) {
        // tambahkan pesan ini jika buku telat dikembalikan
        echo " (telat $lateInDays hari)";
    }

    echo "\n" . $books["title"] . ", oleh " . $author["name"] . " - " .
        $books["year"] . " (" . $genre["genre"] . ")" . PHP_EOL;
    echo "======" . PHP_EOL;
}
