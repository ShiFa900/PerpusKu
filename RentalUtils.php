<?php

require_once __DIR__ . "/Utils.php";

/**
 * function meminta data dari si penyewa
 */
function askForNewRent(array $rent, array $book)
{
    while (true) {
        // generated-auto id, artinya 'id' di dalam global $rent akan otomatis bertambah ketika ada data baru
        $id = generatedId($rent);
        // meminta nik dari si penyewa
        $nik = askForNik();

        // lakukan pengecekan bila 'nik' dari si penyewa sudah ada di database penyewaan apa blom
        // jika ada, cek apakah orang dengan 'nik' tersebut sudah mengembalikan buku apa blom
        if (isReturnedTrue($rent, $nik) == true) {
            // tampilkan pesan ini jika si penyewa masih belum mengembalikan buku
            echo "Maaf, kamu sedang meminjam buku yang belum dikembalikan :(" . PHP_EOL;
            return null;
        }
        // lanjutkan penambahan data, jika 'nik' tersebut "aman"
        $name = askForName();

        // mendapatkan durasi dalam bentuk hari (angka), karena kita mintanya begitu
        $duration = askForRentalDuration();

        // nominal standard diambil dari attribute "rentalFee" pada item ybs di $books
        $defaultRentalFee = ($book["rentalFee"]);
        $rent = askForRentalPrice("Biaya sewa (standarnya Rp $defaultRentalFee): ");
        // waktu saat transaksi penyewaan terjadi 
        $currentTime = time();
        // 1 day = 24*60*60 seconds
        // mendapatkan hari dimana buku semestinya dikembalikan
        $shouldReturnedOn = $currentTime + ($duration * 24 * 60 * 60);

        // return berupa array berikut
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

/**
 * function meminta nik,
 */
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

/**
 * function meminta inputan nama
 */
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

/**
 * function meminta inputan durasi penyewaan buku
 */
function askForRentalDuration()
{
    while (true) {
        echo "Durasi sewa (hari): ";
        // kita meminta durasi dalam bentuk hari
        // karena itu perlu di konversi di function askForNewRent()
        $rentalDuration = getNumberInput();

        if ($rentalDuration == "") {
            echo "Masukkan durasi sewa hanya dengan angka" . PHP_EOL;
        } else {
            return $rentalDuration;
        }
    }
}

function saveRentsintoJson($rents)
{
    $json = json_encode($rents, JSON_PRETTY_PRINT);
    file_put_contents("Rents.json", $json);
}

function loadRent()
{
    if (file_exists("Rents.json")) {
        $json = file_get_contents("Rents.json");
        $rent = json_decode($json, true);
        return $rent;
    } else {
        return [];
    }
}
