<?php

require_once __DIR__ . "/../../Utils.php";

function showLoanList(array $rents, array $books, array $authors)
{
    while (true) {
        if ($rents[0]["isReturned"] == true) {
            echo "Maaf, belum ada data penyimpanan :(";
            break;
        } else {
            echo "DAFTAR PEMINJAMAN" . PHP_EOL;
            echo "======" . PHP_EOL;

            $loanData = getLoanData($rents, $books, $authors);

            // watchout: ini passing function params by reference
            sortLoanData($loanData);

            for ($i = 0; $i < count($loanData); $i++) {

                $theRents = $loanData[$i][0];
                $theBooks = $loanData[$i][1];
                $theBookAuthor = $loanData[$i][2];

                echoLoanList($theRents, $theBooks, $theBookAuthor, $rents);
            }
        }
        return $rents;
    }
}

function getLoanData(array $rents, array $books, array $authors)
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

function sortLoanData(array &$loanData)
{
    for ($i = 1; $i < count($loanData); $i++) {
        $loanKeN = $loanData[$i];

        //variable j akan bernilai $i sekarang dikurangi dengan 1 
        $j = $i - 1;

        //jika umur persons paling kiri lebih besar dari umur dikanannya, maka tukar posisi antar keduanya
        while ($j >= 0 && $loanData[0][0]["shouldReturnedOn"] > $loanKeN[0]["shouldReturnedOn"]) {

            $loanData[$j + 1] = $loanData[$j];
            $j = $j - 1;
        }
        //umur ke-n sekarang bernilai 
        $loanData[$j + 1] = $loanKeN;
    }
    return $loanData;
}

function echoLoanList(array $theRents, array $theBooks, array $theBookAuthor, array $loanData)
{
    $tempForLate = [];
    $tempForOnGoing = [];
    $lateness = getLateInDays($theRents["rentedOn"], $theRents["shouldReturnedOn"]);

    for ($i = 0; $i < count($loanData); $i++) {
        if ($lateness > 0) {
            $tempForLate[] = $theRents;
            echo "Melewati waktu sewa (" . count($tempForLate) . "): " . PHP_EOL;
        } else {
            $tempForOnGoing[] = $theRents;
            echo "Sewa berjalan (" . count($tempForOnGoing) . "): " . PHP_EOL;
        }
        echo $i + 1 . ". " .  $theBooks["title"] . " (" . $theBookAuthor["name"] . ", " . $theBooks["year"] .
            ") -> " . $theRents["name"] . " (NIK: " . $theRents["nik"] . "), " .
            date('j F Y', $theRents["shouldReturnedOn"]) . PHP_EOL;
    }
}
