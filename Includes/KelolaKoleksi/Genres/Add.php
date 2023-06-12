<?php

require_once __DIR__ . "/../../../Utils.php";

function addGenre(array $genres): array
{
    while (true) {
        $genreName = askForGenreName();
        $adaGenre = isGenreExists($genres, $genreName);

        if ($adaGenre == true) {
            echo "Maaf genre \"$genreName\" sudah ada pada database!" . PHP_EOL;
        } else {
            $genres[] = askForGenre($genres, $genreName);
            echo "Genre " . '"' . ucwords($genreName) . '"' . " telah disimpan" . PHP_EOL;
            break;
        }
    }
    saveGenreintoJson($genres);
    return $genres;
}
