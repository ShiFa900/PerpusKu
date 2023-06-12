<?php

require_once __DIR__ . "/../../Utils.php";
require_once __DIR__ . "/../../RentalUtils.php";

/**
 * @param array rents, @param array books, @param array author
 * function menampilkan data yang ada di database penyewaan (rents)
 */
function showLoanList(array $rents, array $books, array $authors)
{
    while (true) {
        if (isEmpty($rents) == 0) {
            // tampilkan pesan jika database penyewaan masih ksong
            echo "Maaf, tidak ada buku yang dipinjam :(" . PHP_EOL;
            break;
        } else {
            echo "DAFTAR PEMINJAMAN" . PHP_EOL;
            echo "======" . PHP_EOL;

            // mendapatkan data penyewaan yang ada (return value berupa array 3 dimensi (mungkin))
            $loanData = getLoanData($rents, $books, $authors);

            // watchout: ini passing function params by reference
            sortLoanData($loanData);

            // pisahkan data rent yang telat dengan data yg belum dari $loanData
            $lateRents = [];
            $ongoingRents = [];

            foreach ($loanData as $loanItem) {
                $lateness = getLateInDays($loanItem[0]["isReturned"], $loanItem[0]["shouldReturnedOn"]);
                if ($lateness > 0) {
                    // tambahkan data para var bertipe array, jika melebihi batas sewa
                    $lateRents[] = $loanItem;
                } else {
                    // tambahkan data pada var bertipe array, jika penyewaan masih berjalan
                    $ongoingRents[] = $loanItem;
                }
            }

            if (count($lateRents) != 0) {
                // menampilkan data yang melewati batas penyewaan
                echo "Melewati batas sewa (" . count($lateRents) . "): " . PHP_EOL;
                for ($i = 0; $i < count($lateRents); $i++) {
                    showItem($lateRents[$i][0], $lateRents[$i][1], $lateRents[$i][2], $i);
                }
            }

            if (count($ongoingRents) != 0) {
                // menampilkan data penyewaan yang masih berjalan
                echo "Sewa berjalan (" . count($ongoingRents) . "): " . PHP_EOL;
                for ($i = 0; $i < count($ongoingRents); $i++) {
                    showItem($ongoingRents[$i][0], $ongoingRents[$i][1], $ongoingRents[$i][2], $i);
                }
            }
        }
        saveRentsintoJson($rents);
        return $rents;
    }
}

/**
 * Returns triple elements in array with index:
 * 0 => the rent item
 * 1 => the book being rented based on the [0]
 * 2 => the author of the book on the [1]
 */
function getLoanData(array $rents, array $books, array $authors): array
{
    // mencari data-data penyewaan
    // sekaligus mengecek, apakah data sewa masih berjalan atau melebihi batas sewa
    $temp = [];
    for ($i = 0; $i < count($rents); $i++) {
        for ($j = 0; $j < count($books); $j++) {
            if ($rents[$i]["bookId"] == $books[$j]["id"] && $rents[$i]["isReturned"] == false) {
                $temp[] = [
                    0 => $rents[$i],
                    1 => $books[$j],
                    2 => getFirstDataFromArray($authors, $books[$j]["authorId"], "id"),
                ];
            }
        }
    }
    // return dalam array 3 dimensi
    return $temp;
}

/**
 * mengsorting data dari $loanData
 * @param $loanData array a pass by reference parameter of loanData
 */
function sortLoanData(array &$loanData)
{
    for ($i = 1; $i < count($loanData); $i++) {
        $key = $loanData[$i];

        //variable j akan bernilai $i sekarang dikurangi dengan 1 
        $j = $i - 1;

        //jika umur persons paling kiri lebih besar dari umur dikanannya, maka tukar posisi antar keduanya
        while ($j >= 0 && $loanData[$j][0]["shouldReturnedOn"] > $key[0]["shouldReturnedOn"]) {
            $loanData[$j + 1] = $loanData[$j];
            $j = $j - 1;
        }
        //umur ke-n sekarang bernilai 
        $loanData[$j + 1] = $key;
    }
}

/**
 * function menampilkan data yang di olah-olah
 * untuk output yang diminta
 * 
 */
function showItem(array $rentItem, array $rentedBook, array $rentedBookAuthor, $i)
{
    echo $i + 1 . ". " .  $rentedBook["title"] . " (" . $rentedBookAuthor["name"] . ", " . $rentedBook["year"] .
        ") -> " . $rentItem["name"] . " (NIK: " . $rentItem["nik"] . "), " .
        date('j F Y', $rentItem["shouldReturnedOn"]) . PHP_EOL;
}
