<?php

require_once __DIR__ . "/../../../Utils.php";

function editGenre(array $genres)
{
    while (true) {
        if (count($genres) == 0) {
            echo "Tidak bisa mengedit, karena kamu belum menambahkan data genre :(";
            break;
        } else {
            $search = searchGenres($genres);
            if ($search != null) {
                echo "======" . PHP_EOL;
                $indexOfGenre = getIndex($search, "Pilih genre: ");
                echo "Edit nama genre: ";
                $genreName = getStringInput();

                $id = $search[$indexOfGenre - 1]["id"];
                for ($i = 0; $i < count($genres); $i++) {
                    if ($id == $genres[$i]["id"]) {
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
