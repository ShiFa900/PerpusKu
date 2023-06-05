<?php
require_once "Utils.php";

/**
 * function untuk mencari author pada database
 */
function searchAuthors(array $authors)
{
    while (true) {
        // pencarian dilakukan dengan menggunkana nama dari author atau nama penanya(?)
        if (count($authors) == 0) {
            echo "Kamu belum menambahkan data author :(";
            break;
        } else {
            echo "Pencarian nama penulis: ";
            $authorName = getStringInput();
            $temp = [];

            for ($i = 0; $i < count($authors); $i++) {
                if (preg_match("/$authorName/i", $authors[$i]["name"])) {
                    if (in_array($authors[$i]["name"], $temp) == false) {
                        $temp[] = $authors[$i];
                    }
                }
            }

            if (count($temp) == 0) {
                echo "Maaf, tidak ada penulis dengan menggunakan kata kunci tsb." . PHP_EOL;
                return null;
            } else {
                echo "Hasil pencarian: " . PHP_EOL;
                for ($i = 0; $i < count($temp); $i++) {
                    // saat menampilkan author, tampilkan juga jumlah buku yang ditulisnya
                    showAuthors($temp[$i], $i);
                }
            }
        }
        return $temp;
    }
}
