<?php

require_once __DIR__ . "/Utils.php";
require_once __DIR__ . "/RentalUtils.php";
require_once __DIR__ . "/NewBookUtils.php";
require_once __DIR__ . "/Includes/KelolaKoleksi/Book/Add.php";
require_once __DIR__ . "/Includes/KelolaKoleksi/Book/Delete.php";
require_once __DIR__ . "/Includes/KelolaKoleksi/Authors/Add.php";
require_once __DIR__ . "/Includes/KelolaKoleksi/Authors/Edit.php";
require_once __DIR__ . "/Includes/KelolaKoleksi/Authors/Delete.php";
require_once __DIR__ . "/Includes/KelolaKoleksi/Genres/Delete.php";
require_once __DIR__ . "/Includes/KelolaKoleksi/Genres/Add.php";
require_once __DIR__ . "/Includes/KelolaKoleksi/Genres/Edit.php";
require_once __DIR__ . "/Includes/KelolaKoleksi/Book/Edit.php";
require_once __DIR__ . "/Includes/Peminjaman/Add.php";
require_once __DIR__ . "/Includes/Peminjaman/Return.php";
require_once __DIR__ . "/Includes/Peminjaman/LoanList.php";

/**
 * function main menu
 */
function showMainMenu()
{
    clearScreen();
    echo PHP_EOL . "PERPUSKU" . PHP_EOL;
    echo "1. Kelola koleksi" . PHP_EOL;
    echo "2. Peminjaman" . PHP_EOL;
    echo "3. Keluar" . PHP_EOL;
    echo "Pilih menu: ";

    $menu = getNumberInput();
    if ($menu == "" || $menu > 3) {
        return 0;
    }
    return $menu;
}

function showCollectionMenu(array $books, array $authors, array $genres)
{
    clearScreen();
    echo PHP_EOL . "KELOLA KOLEKSI" . PHP_EOL;
    //pada menu ini, tampilkan juga jumlah data yang ada di database
    echo "1. Buku" . " (" . count($books) . ")" . PHP_EOL; // tampilkan jumlah buku
    echo "2. Penulis" . " (" . count($authors) . ")" .  PHP_EOL; // tampilkan jumlah penulis
    echo "3. Genres" . " (" . count($genres) . ")" .  PHP_EOL; // tampilkan jumlah genres buku 
    echo "4. Ke menu utama" . PHP_EOL;
    echo "Pilih menu: ";

    $menu = getNumberInput();
    if ($menu == "" || $menu > 4) {
        return 0;
    }
    return $menu;
}

function showBookCollectionMenu()
{
    clearScreen();
    echo PHP_EOL . "KELOLA KOLEKSI BUKU" . PHP_EOL;
    echo "1. Cari" . PHP_EOL;
    echo "2. Tambah" . PHP_EOL;
    echo "3. Edit" . PHP_EOL;
    echo "4. Hapus" . PHP_EOL;
    echo "5. Ke menu utama" . PHP_EOL;
    echo "Pilih menu: ";

    $menu = getNumberInput();
    if ($menu == "" || $menu > 5) {
        return 0;
    }
    return $menu;
}

function showAuthorsCollectionMenu()
{
    // clearScreen();
    echo PHP_EOL . "KELOLA KOLEKSI PENULIS" . PHP_EOL;
    echo "1. Cari" . PHP_EOL;
    echo "2. Tambah" . PHP_EOL;
    echo "3. Edit" . PHP_EOL;
    echo "4. Hapus" . PHP_EOL;
    echo "5. Ke menu utama" . PHP_EOL;
    echo "Pilih menu: ";

    $menu = getNumberInput();
    if ($menu == "" || $menu > 5) {
        return 0;
    }
    return $menu;
}

function showGenresCollectionMenu()
{
    // clearScreen();
    echo PHP_EOL . "KELOLA KOLEKSI GENRE" . PHP_EOL;
    echo "1. Cari" . PHP_EOL;
    echo "2. Tambah" . PHP_EOL;
    echo "3. Edit" . PHP_EOL;
    echo "4. Hapus" . PHP_EOL;
    echo "5. Ke menu utama" . PHP_EOL;
    echo "Pilih menu: ";

    $menu = getNumberInput();
    if ($menu == "" || $menu > 5) {
        return 0;
    }
    return $menu;
}

function showbookBorrowingMenu()
{
    // clearScreen();
    echo PHP_EOL . "PEMINJAMAN" . PHP_EOL;
    echo "1. Daftar peminjaman" . PHP_EOL;
    echo "2. Tambah" . PHP_EOL;
    echo "3. Kembalikan"  . PHP_EOL;
    echo "4. Ke menu utama" . PHP_EOL;
    echo "Pilih menu: ";

    $menu = getNumberInput();
    if ($menu == "" || $menu > 4) {
        return 0;
    }
    return $menu;
}

function mainMenu()
{
    $exit = false;

    while ($exit == false) {
        $menu = showMainMenu();
        if ($menu == 1) {
            mainCollectionMenu();
        } elseif ($menu == 2) {
            mainBookBorrowingMenu();
        } elseif ($menu == 0) {
            echo "Pilih nomor yang tersedia" . PHP_EOL;
        } else {
            $exit = true;
            echo "Have a nice day (>^o^)>♡";
        }
    }
}

function mainCollectionMenu()
{
    global $books;
    global $authors;
    global $genres;
    $exit = false;

    clearScreen();
    while ($exit == false) {
        $menu = showCollectionMenu($books, $authors, $genres);
        if ($menu == 1) {
            mainBookCollectionMenu();
        } elseif ($menu == 2) {
            mainAuthorsCollectionMenu();
        } elseif ($menu == 3) {
            mainGenresCollectionMenu();
        } elseif ($menu == 0) {
            echo "Pilih nomor yang tersedia" . PHP_EOL;
        } else {
            $exit = true;
        }
    }
}

function mainBookCollectionMenu()
{
    global $genres;
    global $books;
    global $authors;
    global $rents;
    $exit = false;


    while ($exit == false) {
        $menu = showBookCollectionMenu();
        if ($menu == 1) {
            searchBook($books, $authors, $genres, "======");
        } elseif ($menu == 2) {
            $temp = addBook($books, $genres, $authors);
            if ($temp != null) {
                $books = $temp;
            }
        } elseif ($menu == 3) {
            // menu edit
            $temp = editBook($books, $authors, $genres);
            if ($temp != null) {
                $books = $temp;
            }
        } elseif ($menu == 4) {
            // menu delete
            $books = deleteBook($books, $authors, $genres, $rents);
        } elseif ($menu == 0) {
            echo "Pilih nomor yang tersedia" . PHP_EOL;
        } else {
            $exit = true;
        }
    }
}

function mainAuthorsCollectionMenu()
{
    global $authors;
    global $books;
    $exit = false;

    while ($exit == false) {
        $menu = showAuthorsCollectionMenu();
        if ($menu == 1) {
            // menu pencarian penulis
            searchAuthors($authors);
        } elseif ($menu == 2) {
            // menu tambah penulis
            $authors = addAuthor($authors);
        } elseif ($menu == 3) {
            // menu edit penulis
            $authors = editAuthor($authors);
        } elseif ($menu == 4) {
            // menu hapus penulis
            $authors = deleteAuthor($authors, $books);
        } elseif ($menu == 0) {
            echo "Pilih nomor yang tersedia" . PHP_EOL;
        } else {
            $exit = true;
        }
    }
}

function mainGenresCollectionMenu()
{
    global $genres;
    global $books;
    $exit = false;

    while ($exit == false) {
        $menu = showGenresCollectionMenu();
        if ($menu == 1) {
            // menu pencarian genre buku
            searchGenres($genres);
        } elseif ($menu == 2) {
            // menu tambah genre buku
            $genres = addGenre($genres);
        } elseif ($menu == 3) {
            // menu edit genre buku
            $genres = editGenre($genres);
        } elseif ($menu == 4) {
            // menu hapus genre
            $genres = deleteGenre($genres, $books);
        } elseif ($menu == 0) {
            echo "Pilih nomor yang tersedia" . PHP_EOL;
        } else {
            $exit = true;
        }
    }
}

function mainBookBorrowingMenu()
{
    $exit = false;
    global $rents;
    global $books;
    global $genres;
    global $authors;

    while ($exit == false) {
        $menu = showbookBorrowingMenu();
        if ($menu == 1) {
            // daftar peminjaman
            showLoanList($rents, $books, $authors);
        } elseif ($menu == 2) {
            // tambah data peminjaman
            $temp = addRent($rents, $books, $authors, $genres);
            if ($temp != null) {
                $rents = $temp;
            }
        } elseif ($menu == 3) {
            // daftar pengembalian buku
            // TODO: while (true) disini untuk mempermudah pengembalian buku berikutnya
            while (true) {
                $rents = bookReturn($rents, $books, $authors, $genres);
                if (confirm("Ingin melakukan pengembalian buku lagi (y/n)? ") == false) {
                    break;
                }
            }
        } elseif ($menu == 0) {
            echo "Pilih nomor yang tersedia" . PHP_EOL;
        } else {
            $exit = true;
        }
    }
}
