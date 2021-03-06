<?php

require_once 'dev-settings.php';
require_once 'login.php';
require_once 'functions-sql.php';

$mysqli = new mysqli($hn, $un, $pw, $db);
if ($mysqli->connect_error) {
    die("Could not connect to the database");
}
//Ledenbestand inzien
echo <<<_END
<html>
    <head>
        <title>Informatie ledenbestand</title>
    <style>
    a {
        text-decoration: none;
        color: black;
    }
</style>
    </head>
    <body>
        <pre> 
        <b><h1>Welkom op bij het ledenbestand van VV Boxtel.</h1></b>
<button type='button'><a href='lidtoe.php'>Lid toevoegen</a></button><br><br>
_END;

//if (isset($_POST['lzien'])){    

$lzien = "SELECT lid.*, email.emailadres, t.telefoonnummer, postcode.*
    FROM lid 
    LEFT JOIN email ON lid.lidnummer=email.lidnummer
    LEFT JOIN telefoonnummers t ON lid.lidnummer=t.lidnummer
    LEFT JOIN postcode ON postcode.postcode=lid.postcode
    ORDER BY lidnummer ASC";
$result = $mysqli->query($lzien);
if (!$result) {
    die("Could not fetch data from database");
}

$rows = $result->num_rows;
echo "<table><tr><th>Lidnummer</th><th>Achternaam</th><th>Voornaam</th><th>Postcode</th><th>Huisnummer</th><th>Emailadres</th><th>Telefoonnummer</th><th>Postcode</th><th>Straat</th><th>Woonplaats</th></tr>";
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    echo "<tr>";
    foreach ($row as $element) {
        echo "<td>" . htmlspecialchars($element) . "</td> ";
    }
    echo "<td><button type='submit' value='verander'><a href='verander.php?id=" . $row['lidnummer'] . "'>Verander</a></button></td>";
    echo "<td><button type='submit' value='verwijder'><a href='verwijder.php?id=" . $row['lidnummer'] . "'>Verwijderen</a></button></td> ";
    echo "</tr>";
}

echo "</table>";


echo <<<_END
----------------------------------------------------------------------------------------------------------------------        
<h2>Deze postcodes zijn bij ons bekend.</h2>
    
_END;


$pcheck = "SELECT * FROM postcode";
$result = $mysqli->query($pcheck);
if (!$result) die("Database access failed");
$rows = $result->num_rows;
echo "<table><tr><th>Postcode</th><th>Straatnaam</th><th>Plaats</th></tr>";
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    echo "<tr>";
    foreach ($row as $element) {
        echo "<td>" . htmlspecialchars($element) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

echo "<br><br><button type='button'><a href='toevoeg.php'>Postcode toevoegen</a></button>";
