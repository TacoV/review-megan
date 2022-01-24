<?php

require_once 'login.php';
$mysqli = new mysqli($hn, $un, $pw, $db);
if ($mysqli->connect_error) die("fatal error");

if (isset($_POST['ltoe'])) {

    $vnaam = sanitizeString($_POST['vnaam']);
    $anaam = sanitizeString($_POST['anaam']);
    $postcode = sanitizeString($_POST['postcode']);
    $huisnr = sanitizeString($_POST['huisnummer']);
    $telnr = sanitizeString($_POST['telnr']);
    $email = sanitizeString($_POST['email']);


    $ltoe = "INSERT INTO lid (`naam`, `voornaam`, `postcode`, `huisnummer`) VALUES ('$anaam','$vnaam','$postcode','$huisnr')";
    $result = $mysqli->multi_query($ltoe);
    $lidnr = $mysqli->insert_id;
    if (!$result) die("tabase access failed");
    if ($result) echo "Dit lid is toegevoegd <br><br>";
    header("Location: ledenbestand.php");

    while ($mysqli->next_result()) {
        if ($mysqli->more_results()) break;
    }
    $mysqli->query("INSERT INTO email VALUES ('$email', '$lidnr')");
    $mysqli->query("INSERT INTO telefoonnummers VALUES ('$telnr','$lidnr')");
}

echo <<<_END
    <html><head><title>Verander lid</title></head><body>
<pre> <button type='button'><a href='ledenbestand.php'>Terug</a></button><style>
    a {
        text-decoration: none;
        color: black;
    }
</style>
----------------------------------------------------------------------------------------------------------------------   
        <h3>Een lid toevoegen:</h3>
        <form method="post" action="">
       
        Voornaam <input type="text" name="vnaam">
        Achternaam <input type="text" name="anaam">
        Postcode <input type="text" name="postcode">
        Huisnummer <input type="text" name="huisnummer">
        Telefoonnummer <input type="text" name="telnr">
        Email <input type="text" name="email">
        <input type="hidden" name="ltoe" value="yes">
        <input type="submit" value="Toevoegen">        
            </form>    </pre></html>
_END;


function sanitizeString($var)
{
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

function sanitizeMySQL($connection, $var)
{
    $var = $connection->real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
}
