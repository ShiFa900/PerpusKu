<?php

require_once __DIR__ . "/Utils.php";

function askForNewBook()
{
    global $authors;
    global $genres;
    global $books;

    while (true) {
        $bookTitle = askForBookTitle();
        // lakukan pengecekan apakah judul dari buku baru ini sudah ada pada database
        $adaBook = isBookExists($books, $bookTitle);
        if ($adaBook) {
            echo "Maaf, buku dengan judul " . '"' . ucwords($bookTitle) . '"' . " sudah ada pada database" . PHP_EOL;
            break;
        } else {
            $year = askForPublicationYear();
            $rent = askForRentalPrice("Harga sewa: ");
            $id = generatedId($books);


            echo "======" . PHP_EOL;
            echo "Pemilihan genre" . PHP_EOL;

            // cari genre yang dipilih

            $searchGenre = searchGenres($genres);
            if ($searchGenre == null) {
                return null;
            }

            $genreOrdinalNumber = getIndex($searchGenre, "Pilih genre diatas: ");
            $selectedGenre = $searchGenre[$genreOrdinalNumber - 1];

            echo "======" . PHP_EOL;
            echo "Pemilihan penulis" . PHP_EOL;
            $searchAuthor = searchAuthors($authors);
            if ($searchAuthor == null) {
                return null;
            }
            $authorOrdinalNumber = getIndex($searchAuthor, "Pilih author diatas: ");
            $selectedAuthor = $searchAuthor[$authorOrdinalNumber - 1];
        }

        return [
            "title" => $bookTitle,
            "year" => $year,
            "rentalFee" => $rent,
            "genreId" => $selectedGenre["id"],
            "authorId" => $selectedAuthor["id"],
            "id" => $id,
        ];
    }
}

function askForBookTitle()
{
    while (true) {
        echo "Judul buku dan nomor seri (nomor koleksi): ";
        $title = getStringInput();

        if ($title == "" || strlen($title) > 30) {
            echo "Masukkan judul buku dengan benar" . PHP_EOL;
        } else {
            return $title;
        }
    }
}

function askForPublicationYear()
{
    while (true) {
        echo "Tahun penerbitan: ";
        $publicationYear = getNumberInput();

        if ($publicationYear == "") {
            echo "Masukkan tahun terbit dengan benar" . PHP_EOL;
        } else {
            return $publicationYear;
        }
    }
}

function saveAuthorintoJson($authors)
{
    $json = json_encode($authors, JSON_PRETTY_PRINT);
    file_put_contents("Authors.json", $json);
}

function saveGenreintoJson($genres)
{
    $json = json_encode($genres, JSON_PRETTY_PRINT);
    file_put_contents("Genres.json", $json);
}

function saveBookintoJson($books)
{
    $json = json_encode($books, JSON_PRETTY_PRINT);
    file_put_contents("Books.json", $json);
}

function loadAuthor()
{
    if (file_exists("Author.json")) {
        $json = file_get_contents("Author.json");
        $author = json_decode($json, true);
        return $author;
    } else {
        return [];
    }
}

function loadBook()
{
    if (file_exists("Books.json")) {
        $json = file_get_contents("Books.json");
        $book = json_decode($json, true);
        return $book;
    } else {
        return [];
    }
}

function loadGenre()
{
    if (file_exists("Genres.json")) {
        $json = file_get_contents("Genres.json");
        $genre = json_decode($json, true);
        return $genre;
    } else {
        return [];
    }
}
