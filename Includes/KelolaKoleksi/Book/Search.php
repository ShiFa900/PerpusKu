<?php

require_once "Utils.php";


function searchBook(array $books, array $author, array $genre, string $input)
{

    while (true) {
        if (count($books) == 0) {
            echo "Kamu belum menambahkan data buku :(";
            break;
        } else {
            echo "Pencarian judul buku: ";
            $title = getStringInput();
            $temp = [];

            for ($i = 0; $i < count($books); $i++) {
                if (preg_match("/$title/i", $books[$i]["title"])) {
                    if (in_array($books[$i]["title"], $temp) == false) {
                        $temp[] = $books[$i];
                    }
                }
            }

            if (count($temp) == 0) {
                echo "Maaf, tidak ada buku dengan menggunakan kata kunci tsb." . PHP_EOL;
                return null;
            } else {
                echo "Hasil pencarian: " . PHP_EOL;
                echo "$input\n";
                for ($i = 0; $i < count($temp); $i++) {
                    echo "\n $i" + 1 . ". " . showBook(books: $temp[$i], author: $author, genre: $genre);
                }
            }
        }
        return $temp;
    }
}
