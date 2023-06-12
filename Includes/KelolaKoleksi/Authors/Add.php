<?php

require_once __DIR__ . "/../../../Utils.php";

/**
 * @param array global author (database)
 * @return array author 
 * function untuk menambahkan nama penulis
 */
function addAuthor(array $authors): array
{
    while (true) {
        // meminta inputan nama author/penulis
        $authorName = askForAuthorName();
        // cek apakah nama penulis sudah ada di database, jangan sampai nama penulis duplikat
        $adaAuthor = isAuthorExists($authors, $authorName);

        // tampilkan pesan berikut jika nama penulis sudah ada di database
        if ($adaAuthor == true) {
            echo "Maaf, \"$authorName\" sudah ada pada database!" . PHP_EOL;
        } else {
            // oterwise, simpan nama penulis baru di dalam global database
            $authors[] = askForAuthor($authors, $authorName);
            // menampilkan pesan bahwa nama penulis sudah disimpan
            echo "Penulis " . '"' . ucwords($authorName) . '"' . " sudah disimpan!" . PHP_EOL;
            break;
        }
    }
    saveAuthorintoJson($authors);
    return $authors;
}
