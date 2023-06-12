<?php

require_once __DIR__ . "/../../../Utils.php";

/**
 * function untuk menambahkan nama penulis
 */
function addAuthor(array $authors): array
{
    while (true) {
        $authorName = askForAuthorName();
        $adaAuthor = isAuthorExists($authors, $authorName);

        if ($adaAuthor == true) {
            echo "Maaf, \"$authorName\" sudah ada pada database!" . PHP_EOL;
        } else {
            $authors[] = askForAuthor($authors, $authorName);
            echo "Penulis " . '"' . ucwords($authorName) . '"' . " sudah disimpan!" . PHP_EOL;
            break;
        }
    }
    saveAuthorintoJson($authors);
    return $authors;
}
