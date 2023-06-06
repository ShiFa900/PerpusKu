<?php

require_once __DIR__ . "/../../../Utils.php";

function deleteGenre(array $genre, array $books)
{
    while (true) {
        if (isEmpty($genre) == 0) {
            echo "Tidak bisa menghapus, karena kamu belum menambahkan data genre :(";
            break;
        } else {

            $search = searchGenres($genre);
            echo "======" . PHP_EOL;
            $indexOfGenre = getIndex($search, "Pilih genre yang akan dihapus: ");

            $idGenreToBeDeleted = $search[$indexOfGenre - 1]["id"];
            for ($i = 0; $i < count($genre); $i++) {
                if ($idGenreToBeDeleted == $genre[$i]["id"]) {

                    $bookGenre = genreBooksId($books, $idGenreToBeDeleted);
                    if ($bookGenre == false) {
                        echo "Genre tidak bisa dihapus, karena dimuat di dalam buku yang sudah terpublikasi" . PHP_EOL;
                    } else {
                        // TODO: why do we have another for loop again here?
                        for ($i = 0; $i < count($genre); $i++) {
                            $genreName = "";
                            if ($idGenreToBeDeleted == $genre[$i]["id"]) {
                                $genreName = $genre[$i]["genre"];

                                $sentence = "Hapus genre \"$genreName\" (y/n)? ";
                                if (confirm($sentence)) {
                                    unset($genre[$i]);
                                    echo "Genre " . '"' . ucwords($genreName) . '"' . " telah dihapus" . PHP_EOL;
                                    $genre = array_values($genre);
                                } else {
                                    echo "Pengahapusan dibatalkan" . PHP_EOL;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        break;
    }
    return $genre;
}
