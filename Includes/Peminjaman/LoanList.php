<?php

require_once __DIR__ . "/../../Utils.php";
require_once __DIR__ . "/../../RentalUtils.php";

function showLoanList(array $rents, array $books, array $authors)
{
    while (true) {
        if (isEmpty($rents) == 0) {
            echo "Maaf, tidak ada buku yang dipinjam :(";
            break;
        } else {
            echo "DAFTAR PEMINJAMAN" . PHP_EOL;
            echo "======" . PHP_EOL;

            $loanData = getLoanData($rents, $books, $authors);

            // watchout: ini passing function params by reference
            sortLoanData($loanData);

            // pisahkan data rent yang telat dengan data yg belum dari $loanData
            $lateRents = [];
            $ongoingRents = [];

            foreach ($loanData as $loanItem) {
                $lateness = getLateInDays($loanItem[0]["isReturned"], $loanItem[0]["shouldReturnedOn"]);
                if ($lateness > 0) {
                    $lateRents[] = $loanItem;
                } else {
                    $ongoingRents[] = $loanItem;
                }
            }

            // show late and ongoing rents
            echo "Melewati batas sewa (" . count($lateRents) . "): " . PHP_EOL;
            for ($i = 0; $i < count($lateRents); $i++) {
                showItem($lateRents[$i][0], $lateRents[$i][1], $lateRents[$i][2], $i);
            }

            echo "Sewa berjalan (" . count($ongoingRents) . "): " . PHP_EOL;
            for ($i = 0; $i < count($ongoingRents); $i++) {
                showItem($ongoingRents[$i][0], $ongoingRents[$i][1], $ongoingRents[$i][2], $i);
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
    return $temp;
}

/**
 * Sort array of loanData
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

function showItem(array $rentItem, array $rentedBook, array $rentedBookAuthor, $i)
{
    echo $i + 1 . ". " .  $rentedBook["title"] . " (" . $rentedBookAuthor["name"] . ", " . $rentedBook["year"] .
        ") -> " . $rentItem["name"] . " (NIK: " . $rentItem["nik"] . "), " .
        date('j F Y', $rentItem["shouldReturnedOn"]) . PHP_EOL;
}
