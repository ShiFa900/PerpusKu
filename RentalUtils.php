<?php

require_once __DIR__ . "/Utils.php";

function askForNewRent(array $rent, array $book)
{
    while (true) {
        $id = generatedId($rent);
        $nik = askForNik();

        if (isNikOnRent($rent, $nik) == true) {
            echo "Maaf, kamu sedang meminjam buku yang belum dikembalikan :(" . PHP_EOL;
            return null;
        }

        $name = askForName();

        // duration in days
        $duration = askForRentalDuration();

        // nominal standard diambil dari attribute "rentalFee" pada item ybs di $books
        $defaultRentalFee = ($book["rentalFee"]);
        $rent = askForRentalPrice("Biaya sewa (standarnya Rp $defaultRentalFee): ");

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
