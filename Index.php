<?php

require_once "Main.php";

$genres = [
    // array(
    //     "id" => 1,
    //     "genre" => "horror",
    // ),
    // array(
    //     "id" => 2,
    //     "genre" => "motivasi",
    // ),
    // array(
    //     "id" => 6,
    //     "genre" => "hayalan",
    // ),

];

$books = [
    // array(
    //     "id" => 3,
    //     "title" => "Hidup sekali #1",
    //     "year" => 2020,
    //     "authorId" => 37,
    //     "genreId" => 2,
    //     "rentalFee" => 5000,
    // ),
    // array(
    //     "id" => 4,
    //     "title" => "Hari ini Pun Tak Akan Berubah #1",
    //     "year" => 2017,
    //     "authorId" => 38,
    //     "genreId" => 2,
    //     "rentalFee" => 9000,
    // ),
    // array(
    //     "id" => 5,
    //     "title" => "Hidup sekali #2",
    //     "year" => 2017,
    //     "authorId" => 38,
    //     "genreId" => 2,
    //     "rentalFee" => 20000,
    // )
];

$rents = [
    // array(
    //     "id" => 90,
    //     "bookId" => 3,
    //     "nik" => 213,
    //     "name" => "Dipa",
    //     "amount" => 15000,
    //     "rentedOn" => 1685907648,
    //     "duration" => 3,
    //     "shouldReturnedOn" => 1686253248,
    //     "isReturned" => false,
    //     "returnedOn" => null,
    // ),
    // array(
    //     "id" => 91,
    //     "bookId" => 4,
    //     "nik" => 332,
    //     "name" => "Mimi",
    //     "amount" => 15000,
    //     "rentedOn" => 1685812881,
    //     "duration" => 2,
    //     "shouldReturnedOn" => 1685985681,
    //     "isReturned" => false,
    //     "returnedOn" => null,
    // )
];

$authors = [
    // array(
    //     "id" => 37,
    //     "name" => "Joanne Rowling",
    // ),
    // array(
    //     "id" => 38,
    //     "name" => "Kosumta",
    // ),
    // array(
    //     "id" => 40,
    //     "name" => "Komi",
    // )
];

$authors = loadAuthor();
$genres = loadGenre();
$books = loadBook();
$rents = loadRent();
mainMenu();
