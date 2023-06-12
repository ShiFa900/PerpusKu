<?php

require_once __DIR__ . "/../../../Utils.php";

/**
 * @param array genre, @param array books
 * 
 */
function deleteGenre(array $genre, array $books)
{
    while (true) {
        // hmm... ok, deskripsinya sama dengan di menu penulis
        if (isEmpty($genre) == 0) {
            echo "Tidak bisa menghapus, karena kamu belum menambahkan data genre :(";
            break;
        } else {

            $search = searchGenres($genre);
            if ($search != null) {
                echo "======" . PHP_EOL;
                $indexOfGenre = getIndex($search, "Pilih genre yang akan dihapus: ");

                $idGenreToBeDeleted = $search[$indexOfGenre - 1]["id"];
                for ($i = 0; $i < count($genre); $i++) {
                    if ($idGenreToBeDeleted == $genre[$i]["id"]) {
                        // dapatkan setidaknya satu buku yang memiliki genre ini
                        $bookWithGenre = getFirstDataFromArray($books, $idGenreToBeDeleted, "genreId");

                        // lakukan pengecekan, jika buku terpublikasi memiliki genre yang akan di hapus
                        if ($bookWithGenre != null) {
                            echo "Genre tidak bisa dihapus, karena terdapat buku yang memiliki genre ini." . PHP_EOL;
                        } else {
                            // otherwise, lakukan ini
                            // mendapatkan nama genre dari genre yang akan di hapus
                            $genreName = $genre[$i]["genre"];

                            // meminta konfirmasi, apakah penghapusan akan dilanjutkan atau tidak
                            if (confirm("Hapus genre " . '"' . ucwords($genreName) . '"' .  " (y/n)? ")) {
                                // jika bernilai true, maka unset genre[$i]
                                unset($genre[$i]);
                                echo "Genre " . '"' . ucwords($genreName) . '"' . " telah dihapus" . PHP_EOL;
                                // set kembali index dari array, agar indexnya berurutan lagi
                                $genre = array_values($genre);
                            } else {
                                // tampilkan pesan ini, jika confirm() = false (pilihan "n")
                                echo "Penghapusan dibatalkan" . PHP_EOL;
                            }
                            break;
                        }
                    }
                }
            }
        }
        break;
    }
    saveGenreintoJson($genre);
    return $genre;
}
