<?php

require_once "Utils.php";

function editAuthor(array $author)
{
    while (true) {
        if (isEmpty($author) == 0) {
            echo "Tidak bisa mengedit, karena kamu belum menambahkan data author :(" . PHP_EOL;
            break;
        } else {
            $search = searchAuthors($author);
            echo "======" . PHP_EOL;
            $indexOfAuthor = getIndex($search, "Pilih penulis: ");
            echo "Edit nama penulis: ";
            $nama = getStringInput();

            // $arrayOfAuthorName = getAuthorsName($author, $nama);
            $id = $search[$indexOfAuthor - 1]["id"];

            for ($i = 0; $i < count($author); $i++) {
                if ($id == $author[$i]["id"]) {

                    $author[$i]["name"] = $nama;
                    break;
                }
            }
        }
        echo "Penulis " . '"' . ucwords($nama) . '"' . " telah di perbarui" . PHP_EOL;
        break;
    }

    return $author;
}
