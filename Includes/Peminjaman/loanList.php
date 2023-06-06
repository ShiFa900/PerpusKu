<?php

require_once "Utils.php";

function showLoanList(array $rents)
{
    while (true) {
        if (isEmpty($rents) == 0) {
            echo "Maaf, belum ada data penyimpanan :(";
            break;
        } else {
            echo "DAFTAR PEMINJAMAN" . PHP_EOL;
            echo "======" . PHP_EOL;
        }
    }
}
