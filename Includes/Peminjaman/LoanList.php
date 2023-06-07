<?php

require_once __DIR__ . "/../../Utils.php";

function showLoanList(array $rents, array $books, array $authors)
{
    while (true) {
        if (isEmpty($rents) == 0) {
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

                echo $i + 1 . ". " .  $theBooks["title"] . " (" . $theBookAuthor["name"] . ", " . $theBooks["year"] .
                    ") -> " . $theRents["name"] . " (NIK: " . $theRents["nik"] . "), " .
                    date('j F Y', $theRents["shouldReturnedOn"]) . PHP_EOL;
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
    // sort by "shouldReturnedOn" field
    array_multisort($loanData, SORT_DESC, $loanData);
}
