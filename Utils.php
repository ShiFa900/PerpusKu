<?php

/**
 *function mengecek apakah inputan dari user bertipe int 
 */
function getNumberInput()
{
    $input = trim(fgets(STDIN));
    if (is_numeric($input)) {
        return (int)$input;
    }
    return ""; // invalid input on terminal
}

function getNumberFloat()
{
    $input = trim(fgets(STDIN));
    if (is_numeric($input)) {
        return (float)$input;
    }
    return "";
}

function getStringInput(): string
{
    $input = trim(fgets(STDIN));
    if (is_string($input)) {
        return (string)$input;
    }
    return "";
}

function isAlpha(): string
{
    $input = trim(fgets(STDIN));
    if (ctype_alpha($input)) {
        return (string)$input;
    }
    return "";
}

function bookTitle()
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

function publicationYear()
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

function rentalPrice(string $input)
{
    while (true) {
        echo $input;
        $rent = getNumberFloat();

        if ($rent == "") {
            echo "Masukkan harga sewa dengan benar" . PHP_EOL;
        } else {
            $rupiah = "Rp " . number_format($rent);
            return $rupiah;
        }
    }
}

function askForNik()
{
    while (true) {
        echo "NIK penyewa: ";
        $nik = getStringInput();

        if ($nik == "") {
            echo "Masukkan NIK dengan benar" . PHP_EOL;
        } else {
            return $nik;
        }
    }
}

function askForName()
{
    while (true) {
        echo "Nama penyewa: ";
        $name = getStringInput();

        if ($name == "") {
            echo "Masukkan nama penyewa dengan benar" . PHP_EOL;
        } else {
            return ucwords($name);
        }
    }
}

function askForRentalDuration()
{
    while (true) {
        echo "Durasi sewa (hari): ";
        $rentalDuration = getNumberInput();

        if ($rentalDuration == "") {
            echo "Masukkan durasi sewa hanya dengan angka" . PHP_EOL;
        } else {
            return $rentalDuration;
        }
    }
}

function getAuthorBooks(array $author, array $books)
{
    $temp = [];
    for ($i = 0; $i < count($books); $i++) {
        if ($books[$i]["authorId"] == $author["id"]) {
            $temp[] = $books[$i];
        }
    }
    return $temp;
}

function getGenreBooks(array $genre, array $books)
{
    $temp = [];
    for ($i = 0; $i < count($genre); $i++) {
        if ($books[$i]["genreId"] == $genre["id"]) {
            $temp[] = $books[$i];
        }
    }
    return $temp;
}

function isNikOnRent(array $rent, string $nik): bool
{
    for ($i = 0; $i < count($rent); $i++) {
        if ($nik == $rent[$i]["nik"]) {
            return true;
        }
    }
    return false;
}

function askForNewBook()
{
    global $authors;
    global $genres;
    global $books;

    while (true) {
        $bookTitle = bookTitle();
        // lakukan pengecekan apakah judul dari buku baru ini sudah ada pada database
        $adaBook = isBookExist($books, $bookTitle);
        if ($adaBook) {
            echo "Maaf, buku dengan judul " . '"' . ucwords($bookTitle) . '"' . " sudah ada pada database" . PHP_EOL;
            break;
        } else {
            $year = publicationYear();
            $rent = rentalPrice("Harga sewa: ");
            $id = getId($books);


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

function askForRent(array $rent, array $book)
{
    while (true) {
        $id = getId($rent);
        $nik = askForNik();

        if (isNikOnRent($rent, $nik) == true) {
            echo "Maaf, kamu sedang meminjam buku yang belum dikembalikan :(" . PHP_EOL;
            return null;
        }

        $name = askForName();

        // duration in days
        $duration = askForRentalDuration();

        // nominal standard diambil dari attribute "rentalFee" pada item ybs di $books
        $defaultRentalFee = number_format($book["rentalFee"]);
        $rent = rentalPrice("Biaya sewa (standarnya Rp $defaultRentalFee): ");

        // timestamp untuk kapan si buku dikembalikan
        // 1 day = 24*60*60 seconds
        $currentTime = time();
        $shouldReturnedOn = $currentTime + ($duration * 24 * 60 * 60);

        return [
            "id" => $id,
            "bookId" => $book["id"],
            "name" => $name,
            "nik" => $nik,
            "duration" => $duration,
            "amount" => $rent,
            "rentedOn" => $currentTime,
            "shouldReturnedOn" => $shouldReturnedOn,
            // otomatis akan ter-assign jika buku telah dikembalikan (true)
            "isReturned" => false,
            // otomatis akan terisi dengan tanggal dimana penyewa mengembalikan buku (jika buku sudah dikembalikan)
            "returnedOn" => null,
        ];
    }
}

function askForOrdinalNumber(string $input)
{
    while (true) {
        echo $input;
        $confirm = getNumberInput();
        if ($confirm == "") {
            echo "Masukkan angka" . PHP_EOL;
        } else {
            return $confirm;
        }
    }
}

function getId(array $input)
{
    if (count($input) == 0) {
        return 1;
    }
    return $input[count($input) - 1]["id"] + 1;
}


function getAuthorsName(array $authors, string $name): array
{
    for ($i = 0; $i < count($authors); $i++) {
        if ($name == $authors[$i]["name"]) {
            return $authors[$i];
        }
    }
    return [];
}

function isAuthorExist(array $authors, string $name): bool
{
    for ($i = 0; $i < count($authors); $i++) {
        if (strtolower($name) == strtolower($authors[$i]["name"])) {
            return true;
        }
    }
    return false;
}

function isGenreExist(array $genres, string $input): bool
{
    for ($i = 0; $i < count($genres); $i++) {
        if (strtolower($input) == strtolower($genres[$i]["genre"])) {
            return true;
        }
    }
    return false;
}

function isBookExist(array $books, string $title)
{
    for ($i = 0; $i < count($books); $i++) {
        if (strtolower($title) == strtolower($books[$i]["title"])) {
            return $books[$i];
        }
    }
    return null;
}

/**
 * Helper function untuk mendapatkan elemen pada array $input, dengan $keyname yang bernilai $id
 */
function getFirstDataFromArray(array $input, int $id, string $keyName): array
{
    for ($i = 0; $i < count($input); $i++) {
        if ($id == $input[$i][$keyName]) {
            return $input[$i];
        }
    }
    return null;
}

function booksId(array $books, string $id)
{
    for ($i = 0; $i < count($books); $i++) {
        if ($id == $books[$i]["bookId"]) {
            return false;
        }
    }
    return true;
}

function askForAuthor(array $authors, string $name)
{
    $id = getId($authors);

    return [
        "name" => $name,
        "id" => $id,
    ];
}

function askForGenre(array $genres, string $genre)
{
    $id = getId($genres);

    return [
        "genre" => $genre,
        "id" => $id,
    ];
}
function confirm(string $input): bool
{
    while (true) {
        echo $input;
        $confirm = getStringInput();
        if ($confirm == "y") {
            return true;
            //jika user menginput "n", maka program akan langsung keluar
        } elseif ($confirm == "n") {
            return false;
        }
    }
    return $input;
}

function showAuthors(array $author, int $index)
{
    global $books;
    $totalAuthorBooks = getAuthorBooks($author, $books);

    if (count($author) == 0) {
        echo "-" . PHP_EOL;
    } else {
        echo "\n $index" + 1 . ". " . $author["name"] . " (" . count($totalAuthorBooks) .  ")" . PHP_EOL;
    }
    // }
}

function showGenre(array $genres, int $index)
{
    global $books;
    $totalGenreBooks = getGenreBooks($genres, $books);
    if (count($genres) == 0) {
        echo "-" . PHP_EOL;
    } else {
        // tampilkan jumlah suatu genre yang terkandung dalam semua buku
        echo "\n $index" + 1 . ". " . $genres["genre"] . " (" . count($totalGenreBooks) . ")" . PHP_EOL;
    }
}

function showBook(array $books, array $author, array $genre)
{

    if (count($books) == 0) {
        echo "-" . PHP_EOL;
    } else {

        for ($i = 0; $i < count($author); $i++) {
            if ($author[$i]["id"] == $books["authorId"]) {
                $authorName = $author[$i]["name"];
            }
        }

        for ($i = 0; $i < count($genre); $i++) {
            if ($genre[$i]["id"] == $books["genreId"]) {
                $genreName = $genre[$i]["genre"];
            }
        }


        $temp = ucwords($books["title"]) . ", oleh " . ucwords($authorName) .
            " - " . $books["year"] . " (" . $genreName . ")" . PHP_EOL;

        return $temp;
    }
}

function showTenant(array $rent)
{
    if (count($rent) == 0) {
        echo "-";
        return null;
    } else {
        for ($i = 0; $i < count($rent); $i++) {
            $rentedOn = date('j F Y', $rent[$i]["rentedOn"]);
            $shouldReturnedOn = date('j F Y', $rent[$i]["shouldReturnedOn"]);
            // $lateness = 
            // tambahkan validasi untuk menampilkan keterlambatan pengembalian buku
            echo $rent[$i]["name"] . " (NIK: " . $rent[$i]["nik"] . ") pada " . $rentedOn .
                " -> " . $shouldReturnedOn  . PHP_EOL;
        }
    }
}

function echoBook(array $showNewBook, array $genres, array $authors)
{
    echo "======" . PHP_EOL;
    echo "Konfirmasi: " . PHP_EOL;
    echo "Judul buku: " .  ucwords($showNewBook["title"]) . PHP_EOL;
    echo "Tahun penerbitan: " . $showNewBook["year"] . PHP_EOL;
    echo "Harga sewa: " . $showNewBook["rentalFee"] . PHP_EOL;
    // show genre
    for ($i = 0; $i < count($genres); $i++) {
        if ($genres[$i]["id"] == $showNewBook["genreId"]) {
            // tampilkan
            echo "Genre: " . ucwords($genres[$i]["genre"]) . PHP_EOL;

            break;
        }
    }
    // show author
    for ($i = 0; $i < count($authors); $i++) {
        if ($authors[$i]["id"] == $showNewBook["authorId"]) {
            echo "Penulis: " .  ucwords($authors[$i]["name"]) . PHP_EOL;
            break;
        }
    }

    echo "======" . PHP_EOL;
}

function getIndex(array $input, string $sentence)
{
    while (true) {
        $target = askForOrdinalNumber($sentence);
        if ($target > count($input) || $target <= 0) {
            echo "Pilihan tidak ditemukan" . PHP_EOL;
        } else {
            return $target;
        }
    }
}

function getLateInDays(int $rentDate, int $shouldReturnedDate): int
{
    // make sure $shouldReturnedDate > $rentDate
    if ($shouldReturnedDate > $rentDate) {
        // masih dalam satuan seconds
        $diff = time() - $shouldReturnedDate;

        // konversi ke hari
        return floor($diff / (60 * 60 * 24));
    }
    return -1;
}

function askForAuthorName()
{
    while (true) {
        echo "Nama penulis: ";
        $authorName = (getStringInput());
        if ($authorName == "" || strlen($authorName) > 50) {
            echo "Masukkan nama penulis dengan benar" . PHP_EOL;
        } else {
            return $authorName;
        }
    }
}

function askForGenreName()
{
    while (true) {
        echo "Nama genre: ";
        $genreName = isAlpha();
        if ($genreName == "" || strlen($genreName) > 30) {
            echo "Masukkan nama genre dengan benar";
        }
        return $genreName;
    }
}

function isEmpty(array $input)
{
    if (count($input) == 0) {
        return false;
    }
    return true;
}

/**
 * Mencari dan mengembalikan buku-buku dalam array $books yang memiliki judul serupa $title
 */
function getBooksByTitle(array $books, string $title): array
{
    $temp = [];

    for ($i = 0; $i < count($books); $i++) {
        if (preg_match("/$title/i", $books[$i]["title"])) {
            if (in_array($books[$i]["title"], $temp) == false) {
                $temp[] = $books[$i];
            }
        }
    }

    return $temp;
}

function searchBook(array $books, array $author, array $genre, string $input)
{
    while (true) {
        if (count($books) == 0) {
            echo "Kamu belum menambahkan data buku :(";
            break;
        } else {
            echo "Pencarian judul buku: ";
            $title = getStringInput();
            $temp = getBooksByTitle($books, $title);

            if (count($temp) == 0) {
                echo "Maaf, tidak ada buku dengan menggunakan kata kunci tsb." . PHP_EOL;
                return null;
            } else {
                echo "Hasil pencarian: " . PHP_EOL;
                echo "$input\n";
                for ($i = 0; $i < count($temp); $i++) {
                    echo "\n $i" + 1 . ". " . showBook(books: $temp[$i], author: $author, genre: $genre);
                }
            }
        }
        return $temp;
    }
}

function searchGenres(array $genres)
{
    while (true) {
        if (count($genres) == 0) {
            echo "Kamu belum menambahkan data genre :(";
            break;
        } else {
            echo "Pencarian nama genre: ";
            $genreName = getStringInput();
            $temp = [];

            for ($i = 0; $i < count($genres); $i++) {
                if (preg_match("/$genreName/i", $genres[$i]["genre"])) {
                    if (in_array($genres[$i]["genre"], $temp) == false) {
                        $temp[] = $genres[$i];
                    }
                }
            }
            if (count($temp) == 0) {
                echo "Maaf, tidak ada genre dengan menggunakan kata kunci tsb." . PHP_EOL;
                return null;
            } else {
                echo "Hasil pencarian: " . PHP_EOL;
                echo "======" . PHP_EOL;
                for ($i = 0; $i < count($temp); $i++) {
                    showGenre($temp[$i], $i);
                }
            }
        }
        return $temp;
    }
}

function searchAuthors(array $authors)
{
    while (true) {
        // pencarian dilakukan dengan menggunkana nama dari author atau nama penanya(?)
        if (count($authors) == 0) {
            echo "Kamu belum menambahkan data author :(";
            break;
        } else {
            echo "Pencarian nama penulis: ";
            $authorName = getStringInput();
            $temp = [];

            for ($i = 0; $i < count($authors); $i++) {
                if (preg_match("/$authorName/i", $authors[$i]["name"])) {
                    if (in_array($authors[$i]["name"], $temp) == false) {
                        $temp[] = $authors[$i];
                    }
                }
            }

            if (count($temp) == 0) {
                echo "Maaf, tidak ada penulis dengan menggunakan kata kunci tsb." . PHP_EOL;
                return null;
            } else {
                echo "Hasil pencarian: " . PHP_EOL;
                echo "======" . PHP_EOL;
                for ($i = 0; $i < count($temp); $i++) {
                    // saat menampilkan author, tampilkan juga jumlah buku yang ditulisnya
                    showAuthors($temp[$i], $i);
                }
            }
        }
        return $temp;
    }
}
