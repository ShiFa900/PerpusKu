<?php

require_once __DIR__ . "/../../../Utils.php";

/**
 * @param array genre
 * @return array global genre yang telah di tambahkan data baru
 */
function addGenre(array $genres): array
{
    while (true) {
        // meminta nama genre
        $genreName = askForGenreName();
        // cek apakah nama genre yang di input sudah ada di database ap blom
        $adaGenre = isGenreExists($genres, $genreName);

        if ($adaGenre == true) {
            // tampilkan pesan ini, jika nama genre sudah ada di database
            echo "Maaf genre \"$genreName\" sudah ada pada database!" . PHP_EOL;
        } else {
            // tampung nama dari genre baru di global database
            $genres[] = askForGenre($genres, $genreName);
            echo "Genre " . '"' . ucwords($genreName) . '"' . " telah disimpan" . PHP_EOL;
            break;
        }
    }
    saveGenreintoJson($genres);
    return $genres;
}
