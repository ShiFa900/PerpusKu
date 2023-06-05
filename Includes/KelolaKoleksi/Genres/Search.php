<?php

require_once "Utils.php";

function searchGenres(array $genres)
{
    while (true) {
        if (count($genres) == 0) {
            echo "Kamu belum menambahkan data genre :(";
            break;
        } else {
            echo "Pencarian nama genre: ";
            $genreName = getStringInput();
            $temp = [];

            for ($i = 0; $i < count($genres); $i++) {
                if (preg_match("/$genreName/i", $genres[$i]["genre"])) {
                    if (in_array($genres[$i]["genre"], $temp) == false) {
                        $temp[] = $genres[$i];
                    }
                }
            }
            if (count($temp) == 0) {
                echo "Maaf, tidak ada genre dengan menggunakan kata kunci tsb." . PHP_EOL;
                return null;
            } else {
                echo "Hasil pencarian: " . PHP_EOL;
                for ($i = 0; $i < count($temp); $i++) {
                    showGenre($temp[$i], $i);
                }
            }
        }
        return $temp;
    }
}
