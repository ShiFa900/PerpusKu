<?php

require_once __DIR__ . "/../../../Utils.php";

/**
 * @param array author
 * function untuk mengedit data author
 */
function editAuthor(array $author)
{
    while (true) {
        // pengeditan tidak dapat dilakukan ketika database author kosong
        if (isEmpty($author) == 0) {
            echo "Tidak bisa mengedit, karena kamu belum menambahkan data author :(" . PHP_EOL;
            break;
        } else {
            // melakukan pencarian author
            $search = searchAuthors($author);
            if ($search != null) {
                echo "======" . PHP_EOL;
                // meminta nomor urut author yang akan di edit
                $indexOfAuthor = getIndex($search, "Pilih penulis: ");
                // meminta nama author baru
                echo "Edit nama penulis: ";
                $nama = getStringInput();

                // mencari 'id' author yang akan di edit
                $id = $search[$indexOfAuthor - 1]["id"];

                for ($i = 0; $i < count($author); $i++) {
                    if ($id == $author[$i]["id"]) {
                        // jika 'id' dari author yang akan dihapus telah didapatkan, maka ganti 'nama' author tersebut
                        // dengan nama baru yang telah di input
                        $author[$i]["name"] = ucwords($nama);
                        break;
                    }
                }
                echo "Penulis " . '"' . ucwords($nama) . '"' . " telah di perbarui" . PHP_EOL;
            }
        }
        break;
    }
    saveAuthorintoJson($author);
    return $author;
}
