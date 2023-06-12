<?php

require_once __DIR__ . "/../../../Utils.php";

/**
 * @param array genre
 * @return array genre
 */
function editGenre(array $genres)
{
    while (true) {
        // jika database genre kosong, maka pengeditan tidak dapat dilakukan
        if (count($genres) == 0) {
            echo "Tidak bisa mengedit, karena kamu belum menambahkan data genre :(";
            break;
        } else {
            // otherwise, lakukan pencarian genre yang nantinya akan di edit
            $search = searchGenres($genres);
            if ($search != null) {
                echo "======" . PHP_EOL;
                // meminta nomor urut dari genre setelah dilakukan pencarian
                $indexOfGenre = getIndex($search, "Pilih genre: ");
                echo "Edit nama genre: ";
                $genreName = getStringInput();

                // mendapatkan 'id' dari genre yang akan di hapus
                $id = $search[$indexOfGenre - 1]["id"];
                for ($i = 0; $i < count($genres); $i++) {
                    // melakukan pencarian untuk ['id'] == $id
                    if ($id == $genres[$i]["id"]) {
                        // ganti nama genre ke-$i dengan nama genre baru
                        $genres[$i]["genre"] = ucwords($genreName);
                        break;
                    }
                }
                echo "Genre " . '"' . ucwords($genreName) . '"' . " telah di perbarui" . PHP_EOL;
            }
        }

        break;
    }
    saveGenreintoJson($genres);
    return $genres;
}
