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

function isAlphaNumberic()
{
    $input = trim(fgets(STDIN));
    if (ctype_alnum($input)) {
        return $input;
    }
    return "";
}
/**
 * function untuk mendapatkan buku yang ditulis oleh author dengan 'id'
 */
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

/**
 * function untuk mendapatkan buku dengan genre 'id'
 */
function getGenreBooks(array $genre, array $books)
{
    $temp = [];
    for ($i = 0; $i < count($books); $i++) {
        if ($books[$i]["genreId"] == $genre["id"]) {
            $temp[] = $books[$i];
        }
    }
    return $temp;
}

/**
 * function untuk mengecek, apakah $nik ada dalam database rent, dan apakah nilai dari key['isReturned'] = false
 * yang manya jika bernilai false, artinya buku itu belum dikembalikan
 */
function isReturnedTrue(array $rent, string $nik): bool
{
    foreach ($rent as $key) {
        if ($nik == $key["nik"] && $key["isReturned"] == false) {
            return true;
        }
    }
    return false;
}

/**
 * function untuk meminta harga sewa
 */
function askForRentalPrice(string $input)
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

/**
 * function untuk meminta nomor urut, setelah data ditampilkan
 */
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

/**
 * function untuk memberi 'id' pada data baru
 */
function generatedId(array $input)
{
    // jika data masih kosong, maka 'id' yang diberikan dimulai dari 1(opsional)
    if (count($input) == 0) {
        return 1;
    }
    // otherwise, tambahkan sesuai dengan jumlah inputan dikurangu 1, kemudian 'id
    // ditambah 1
    return $input[count($input) - 1]["id"] + 1;
}

/**
 * function untuk mendapatkan nama dari author dengan menggunakan key['name']
 */
function getAuthorsWithName(array $authors, string $name): array
{
    for ($i = 0; $i < count($authors); $i++) {
        if ($name == $authors[$i]["name"]) {
            // return value berupa array author dengan nama dari var $name
            return $authors[$i];
        }
    }
    return [];
}

/**
 * function mencari apakah nama author yang dimaksud ada di database
 * return value boolean
 */
function isAuthorExists(array $authors, string $name): bool
{
    for ($i = 0; $i < count($authors); $i++) {
        // turunkan semua huruf untuk mencari kesamaan (sensitive case)
        if (strtolower($name) == strtolower($authors[$i]["name"])) {
            // return true jika ditemukan, false otherwise
            return true;
        }
    }
    return false;
}

/**
 * function mencari apakah genre yang dimaksud ada di database
 */
function isGenreExists(array $genres, string $input): bool
{
    for ($i = 0; $i < count($genres); $i++) {
        // sensitive case
        if (strtolower($input) == strtolower($genres[$i]["genre"])) {
            return true;
        }
    }
    return false;
}

/**
 * function mencari apakah buku dengan judul yang dimaksud ada di database
 */
function isBookExists(array $books, string $title)
{
    for ($i = 0; $i < count($books); $i++) {
        // ini sensitive case, makanya diturunkan dulu alias tidak kapital
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
    return [];
}

/**
 * function untuk mengembalikan data author dalam bentuk array
 */
function askForAuthor(array $authors, string $name)
{
    $id = generatedId($authors);
    // penambahan 'id' jika data author sebelumnya sudah ada
    // atau 'id' dimulai dari 1 jika data masih kosong

    return [
        "name" => ucwords($name),
        "id" => $id,
    ];
}

/**
 * function untuk mengembalikan data genre dalam bentuk array
 */
function askForGenre(array $genres, string $genre)
{
    $id = generatedId($genres);
    // penambahan 'id' jika data genre sebelumnya sudah ada
    // atau 'id' dimulai dari 1 jika data masih kosong

    return [
        "genre" => ucwords($genre),
        "id" => $id,
    ];
}
/**
 * konfirmasi lanjutan program selanjutnya
 */
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

/**
 * function menampilkan data author
 */
function showAuthors(array $author, int $index)
{
    global $books;
    // mencari buku yang ditulis oleh author $author
    $totalAuthorBooks = getAuthorBooks($author, $books); // var ini berupa array

    if (count($author) == 0) {
        echo "-" . PHP_EOL;
    } else {
        // tampilkan jumlah buku yang di publikasikan oleh author
        echo "\n $index" + 1 . ". " . $author["name"] . " (" . count($totalAuthorBooks) .  ")" . PHP_EOL;
    }
}

/**
 * function menampilkan data genre
 */
function showGenre(array $genres, int $index)
{
    global $books;
    // mencari buku yang mengandung genre $genres
    $totalGenreBooks = getGenreBooks($genres, $books);
    if (count($genres) == 0) {
        echo "-" . PHP_EOL;
    } else {
        // tampilkan jumlah genre yang terkandung dalam buku yang terpublikasi 
        echo "\n $index" + 1 . ". " . $genres["genre"] . " (" . count($totalGenreBooks) . ")" . PHP_EOL;
    }
}

/**
 * function untuk menampilkan data buku
 */
function showBook(array $books, array $author, array $genre)
{

    if (count($books) == 0) {
        echo "-" . PHP_EOL;
    } else {

        // mencari nama author dengan 'authorId' dari database rents
        for ($i = 0; $i < count($author); $i++) {
            if ($author[$i]["id"] == $books["authorId"]) {
                $authorName = $author[$i]["name"];
            }
        }
        // mencari nama genre dengan 'genreId' dari database rents
        for ($i = 0; $i < count($genre); $i++) {
            if ($genre[$i]["id"] == $books["genreId"]) {
                $genreName = $genre[$i]["genre"];
            }
        }

        // tampilkan
        $temp = ucwords($books["title"]) . ", oleh " . ucwords($authorName) .
            " - " . $books["year"] . " (" . $genreName . ")" . PHP_EOL;

        return $temp;
    }
}

/**
 * function yang tugasnya hanya untuk menampilkan dan sedikit mengecek seperti di bawah ini
 */
function echoBook(array $showNewBook, array $genres, array $authors)
{
    // disini dia echo-echo dulu semua
    echo "======" . PHP_EOL;
    echo "Konfirmasi: " . PHP_EOL;
    echo "Judul buku: " .  ucwords($showNewBook["title"]) . PHP_EOL;
    echo "Tahun penerbitan: " . $showNewBook["year"] . PHP_EOL;
    echo "Harga sewa: " . $showNewBook["rentalFee"] . PHP_EOL;
    // show genre
    // mencari genre yang dimaksud, cek apakah pada $showNewBook['genreId'] ada pada database genre['id']
    for ($i = 0; $i < count($genres); $i++) {
        if ($genres[$i]["id"] == $showNewBook["genreId"]) {
            // tampilkan
            echo "Genre: " . ucwords($genres[$i]["genre"]) . PHP_EOL;

            break;
        }
    }
    // show author
    // mencari nama penulis yang dimaksud, cek apakah pada $showNewBook['authorId'] ada pada database author['id']
    for ($i = 0; $i < count($authors); $i++) {
        if ($authors[$i]["id"] == $showNewBook["authorId"]) {
            echo "Penulis: " .  ucwords($authors[$i]["name"]) . PHP_EOL;
            break;
        }
    }

    echo "======" . PHP_EOL;
}

/**
 * lanjutan dari function askForOrdinalNumber()
 * function ini sekalian melakukan pengecekan
 */
function getIndex(array $input, string $sentence)
{
    while (true) {
        $target = askForOrdinalNumber($sentence);
        if ($target > count($input) || $target <= 0) {
            // jika nomor yang dipilh lebih besar dari banyaknya jumlah data dan kurang dari atau sama dengan 0
            // tampilkan pesan ini
            echo "Pilihan tidak ditemukan" . PHP_EOL;
        } else {
            return $target;
        }
    }
}

/**
 * function mendapatkan hari keterlambatan
 */
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

/**
 * function untuk meminta inputan nama author
 */
function askForAuthorName()
{
    while (true) {
        echo "Nama penulis: ";
        $authorName = isAlphaNumberic();
        if ($authorName == "" || strlen($authorName) > 50) {
            echo "Masukkan nama penulis dengan benar" . PHP_EOL;
        } else {
            return $authorName;
        }
    }
}

/**
 * function mendapatkan nama genre
 */
function askForGenreName()
{
    while (true) {
        echo "Nama genre: ";
        $genreName = isAlpha();
        if ($genreName == "" || strlen($genreName) > 30) {
            echo "Masukkan nama genre dengan benar" . PHP_EOL;
        } else {
            return $genreName;
        }
    }
}

/**
 * function yang sebenarnya agak kurang berguna, but boleh lah kalo ada
 * function ini untuk bertugas untuk mengecek apakah dalam array bernilai 0
 */
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

/**
 * function pencarian untuk judul buku
 */
function searchBook(array $books, array $author, array $genre, string $input)
{
    while (true) {
        if (count($books) == 0) {
            echo "Kamu belum menambahkan data buku :( \n";
            break;
        } else {
            echo "Pencarian judul buku: ";
            $title = preg_quote(getStringInput());
            $temp = getBooksByTitle($books, $title);

            if (count($temp) == 0) {
                echo "Maaf, tidak ada buku dengan menggunakan kata kunci tsb." . PHP_EOL;
                break;
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

/**
 * function untuk pencarian nama genre
 */
function searchGenres(array $genres)
{
    while (true) {
        if (count($genres) == 0) {
            echo "Kamu belum menambahkan data genre :( \n";
            break;
        } else {
            echo "Pencarian nama genre: ";
            $genreName = preg_quote(getStringInput());
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
                break;
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

/**
 * function untuk pencarian nama author
 */
function searchAuthors(array $authors)
{
    while (true) {
        // pencarian dilakukan dengan menggunkana nama dari author
        if (count($authors) == 0) {
            echo "Kamu belum menambahkan data author :( \n";
            break;
        } else {
            echo "Pencarian nama penulis: ";
            $authorName = preg_quote(getStringInput());
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
                break;
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

function clearScreen()
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        popen('cls', 'w');
    } else {
        system('clear');
    }
}
