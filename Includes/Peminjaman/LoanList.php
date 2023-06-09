<?php

require_once __DIR__ . "/../../Utils.php";

function showLoanList(array $rents, array $books, array $authors)
{
    while (true) {
        if (isEmpty($rents) == 0) {
            echo "Maaf, tidak ada data di penyimpanan :(";
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
            }


            echoLoanList($theRents, $theBooks, $theBookAuthor, $rents, $loanData);
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

function echoLoanList(array $theRents, array $theBooks, array $theBookAuthor, array $rents, array $loanData)
{
    $tempForLate = [];
    $tempForOnGoing = [];
    $lateness = getLateInDays($theRents["isReturned"], $theRents["shouldReturnedOn"]);

    for ($i = 0; $i < count($rents); $i++) {

        if ($lateness > 0) {
            $tempForLate[] = $loanData[$i];
        } else {
            $tempForOnGoing[] = $loanData[$i];
            // echo "Sewa berjalan (" . count($tempForOnGoing) . "): " . PHP_EOL;

        }
    }
    echo "Melewati batas sewa (" . count($tempForLate) . "): " . PHP_EOL;
    for ($k = 0; $k < count($tempForLate); $k++) {
        showItem($tempForLate, $loanData[$i][1], $theBookAuthor[$i][2], $k);
    }
    echo "Sewa berjalan (" . count($tempForOnGoing) . "): " . PHP_EOL;
    for ($j = 0; $j < count($tempForOnGoing); $j++) {

        showItem($tempForOnGoing, $theBooks, $theBookAuthor, $j);
    }
}

function showItem(array $array, array $theBooks, array $theBookAuthor, $i)
{
    echo $i + 1 . ". " .  $theBooks["title"] . " (" . $theBookAuthor["name"] . ", " . $theBooks["year"] .
        ") -> " . $array[$i]["name"] . " (NIK: " . $array[$i]["nik"] . "), " .
        date('j F Y', $array[$i]["shouldReturnedOn"]) . PHP_EOL;
}
