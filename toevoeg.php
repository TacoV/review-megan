<?php

require_once 'login.php';
require_once 'functions-sql.php';

$mysqli = new mysqli($hn, $un, $pw, $db);
if ($mysqli->connect_error) die("fatal error");

if (isset($_POST['ptoe'])) {

    $postcode = sanitizeString($_POST['postcode']);
    $adres = sanitizeString($_POST['straat']);
    $woonplaats = sanitizeString($_POST['plaats']);
    $ptoe = "INSERT INTO postcode VALUES ('$postcode','$adres','$woonplaats')";
    $result = $mysqli->query($ptoe);
    if (!$result) die("Database access failed");
    if ($result) echo "Deze postcode is toegevoegd<br><br>";
    header("Location: ledenbestand.php");
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
        <h3>Staat uw postcode niet in ons systeem dan kunt u die hier toevoegen:</h3>
        <form method="post" action="" form="postcode">
        Postcode <input type="text" name="postcode">
        Straat <input type="text" name="straat">
        Woonplaats <input type="text" name="plaats">
        <input type="hidden" name="ptoe" value="yes">
        <input type="submit" value="Postcode toevoegen">        
            </form > </pre>
_END;
