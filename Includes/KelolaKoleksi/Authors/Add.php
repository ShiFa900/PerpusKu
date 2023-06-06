<?php

require_once "Utils.php";

/**
 * function untuk menambahkan nama penulis
 */
function addAuthor(array $authors): array
{
    while (true) {
        $authorName = askForAuthorName();
        $adaAuthor = isAuthorExist($authors, $authorName);

        if ($adaAuthor == true) {
            echo "Maaf, \"$authorName\" sudah ada pada database!" . PHP_EOL;
        } else {
            $authors[] = askForAuthor($authors, $authorName);
            echo "Penulis " . '"' . ucwords($authorName) . '"' . " sudah disimpan!" . PHP_EOL;
            break;
        }
    }
    return $authors;
}