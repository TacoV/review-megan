<?php

require_once 'login.php';
require_once 'functions-sql.php';

$mysqli = new mysqli($hn, $un, $pw, $db);
if ($mysqli->connect_error) die("fatal error");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$ttelnr = isset($_GET["telefoon"]) ? sanitizeString($_GET["telefoon"]) : "";
$temail = isset($_GET["email"]) ? sanitizeString($_GET["email"]) : "";

$id = sanitizeString($_GET["id"]);
$olidnr = sanitizeString($_GET["id"]);

$test = "SELECT naam FROM lid WHERE lidnummer='$id';";
$result = $mysqli->query($test);
$oanaam = $result->fetch_assoc()['naam'];

$test1 = "SELECT postcode FROM lid WHERE lidnummer='$id';";
$result1 = $mysqli->query($test1);
$opostcode = $result1->fetch_assoc()['postcode'];

$test2 = "SELECT huisnummer FROM lid WHERE lidnummer='$id';";
$result2 = $mysqli->query($test2);
$ohuisnr = $result2->fetch_assoc()['huisnummer'];

$test3 = "SELECT telefoonnummer FROM telefoonnummers WHERE lidnummer='$id';";
$result3 = $mysqli->query($test3);
$otelnr = $result3->fetch_assoc()['telefoonnummer'];

$test4 = "SELECT emailadres FROM email WHERE lidnummer='$id';";
$result4 = $mysqli->query($test4);
$oemail = $result4->fetch_assoc()['emailadres'];

$test5 = "SELECT voornaam FROM lid WHERE lidnummer='$id';";
$result5 = $mysqli->query($test5);
$ovnaam = $result5->fetch_assoc()['voornaam'];


if (isset($_POST['lpas'])) {

    $lidnr = $id;
    $vnaam = sanitizeString($_POST['vnaam']);
    $anaam = sanitizeString($_POST['anaam']);
    $postcode = sanitizeString($_POST['postcode']);
    $huisnr = sanitizeString($_POST['huisnummer']);
    $telnr = sanitizeString($_POST['telnr']);
    $email = sanitizeString($_POST['email']);

    $mysqli->multi_query("UPDATE lid SET naam='$anaam', voornaam='$vnaam', postcode='$postcode', huisnummer='$huisnr' WHERE lidnummer='$lidnr'");
    while ($mysqli->next_result()) {
        if ($mysqli->more_results()) break;
    }

    $mysqli->query("UPDATE telefoonnummers SET telefoonnummer='$telnr', lidnummer='$lidnr' WHERE lidnummer='$lidnr'");
    $mysqli->query("UPDATE email SET emailadres='$email' WHERE emailadres='$oemail' && lidnummer='$lidnr'");
    header("Refresh:0");
    print_r(error_get_last());
}

if (isset($_POST['eaan'])) {

    $lidnr = $id;
    $oemail = sanitizeString($_POST['oemail']);
    $nemail = sanitizeString($_POST['nemail']);


    $result7 = $mysqli->query("UPDATE email SET emailadres='$nemail' WHERE emailadres='$oemail' && lidnummer='$lidnr'");
    if (!$result7) die("Database access failed");
    header("Refresh:0");
}

if (isset($_POST['taan'])) {

    $lidnr = $id;
    $ntelnr = sanitizeString($_POST['ntelnr']);
    $otelnr = sanitizeString($_POST['otelnr']);

    $mysqli->query("UPDATE telefoonnummers SET telefoonnummer='$ntelnr' WHERE telefoonnummer='$otelnr' && lidnummer='$lidnr'");
    header("Refresh:0");
}

if (isset($_POST['etoe'])) {

    $lidnr = $id;
    $email = sanitizeString($_POST['email']);

    $mysqli->query("INSERT INTO email(emailadres, lidnummer) VALUES ('$email','$lidnr')");
    header("Refresh:0");
}

if (isset($_POST['ttoe'])) {

    $lidnr = $id;
    $telnr = sanitizeString($_POST['telnr']);

    $mysqli->query("INSERT INTO telefoonnummers VALUES ('$telnr','$lidnr')");
    header("Refresh:0");
}

echo <<<_END
    <html><style>
    a {
        text-decoration: none;
        color: black;
    }
</style>
<pre> <button type='button'><a href='ledenbestand.php'>Terug</a></button>
----------------------------------------------------------------------------------------------------------------------         
    <h3>Jouw gegevens aanpassen. Als u alleen een emailadres of telefoonnummer wilt aanpassen of aanvullen dan kan dat onder dit formulier.</h3>
        <form method="post" action=""> 
        Uw nieuwe gegevens?
    
        Lidnummer: $id
        Voornaam <input type="text" name="vnaam" value="$ovnaam">
        Achternaam <input type="text" name="anaam" value="$oanaam">
        Postcode <input type="text" name="postcode" value="$opostcode">
        Huisnummer <input type="text" name="huisnummer" value="$ohuisnr">
        Telefoonnummer <input type="text" name="telnr" value="$otelnr">
        Email <input type="text" name="email" value="$oemail">
        <input type="hidden" name="lpas" value="yes">
        <input type="submit" value="Aanpassen">       
        </form>
---------------------------------------------------------------------------------------------------------------------        
        <h3>Wilt u uw email wijzigen dan kan dat hier.</h3>
        Deze emailadressen zijn bekend bij ons
_END;
$lzien = "SELECT email.emailadres FROM email WHERE lidnummer=$id";
$result6 = $mysqli->query($lzien);
if (!$result6) die("Database access failed");

$rows1 = $result6->num_rows;
echo "<table><tr><th>Emailadres</th></tr>";
for ($j = 0; $j < $rows1; ++$j) {
    $row1 = $result6->fetch_array(MYSQLI_NUM);
    $n = $row1[0];

    echo "<tr>";

    for ($k = 0; $k < 1; ++$k) {
        echo "<td>" . htmlspecialchars($row1[$k]) . "</td> ";
    }

    echo "<td><button type='submit' value='veranderen' ><a href='verander.php?id=$id&email=$n'> Verander</a>";
    echo "</tr>";
}

echo "</table>";


echo <<<_END
       
        <form method="post" action="" form="verwijder">
        Lidnummer: $id
        Oud Email adres <input type="text" name="oemail" value="$temail">
        Nieuw Email adres<input type="text" name="nemail">
        <input type="hidden" name="eaan" value="yes">
        <input type="submit" value="Wijzigen">        
            </form >  
----------------------------------------------------------------------------------------------------------------------    
        <h3>Wilt u uw telefoonnummer wijzigen dan kan dat hier.</h3>
        Deze telefoonnummers zijn bekend bij ons:
_END;

$lzie = "SELECT telefoonnummers.telefoonnummer FROM telefoonnummers WHERE lidnummer=$id";
$result8 = $mysqli->query($lzie);
if (!$result8) die("Database access failed");

$rows = $result8->num_rows;
echo "<table><tr><th>Telefoonnumer</th></tr>";
for ($j = 0; $j < $rows; ++$j) {
    $row = $result8->fetch_array(MYSQLI_NUM);
    $n = $row[0];

    echo "<tr>";

    for ($k = 0; $k < 1; ++$k) {
        echo "<td>" . htmlspecialchars($row[$k]) . "</td> ";
    }

    echo "<td><button type='submit' value='veranderen' ><a href='verander.php?id=$id&telefoon=$n'> Verander</a>";
    echo "</tr>";
}

echo "</table>";

echo <<<_END
        
        <form method="post" action="" form="verwijder">
        Lidnummer: $id
        Oud Telefoon nummer <input type="text" name="otelnr" value="$ttelnr">
        Nieuw Telefoon nummer <input type="text" name="ntelnr">
        <input type="hidden" name="taan" value="yes">
        <input type="submit" value="Wijzigen">        
            </form >     
----------------------------------------------------------------------------------------------------------------------
        <h3>Extra email adres toevoegen aan een lid.</h3>
        <form method="post" action="" form="verwijder">
        Lidnummer: $id
        Email <input type="text" name="email">
        <input type="hidden" name="etoe" value="yes">
        <input type="submit" value="Toevoegen">        
            </form >      
----------------------------------------------------------------------------------------------------------------------
        <h3>Extra telefoonnummer toevoegen aan een lid.</h3>
        <form method="post" action="" form="verwijder">
        Lidnummer: $id
        Telefoon nummer <input type="text" name="telnr">
        <input type="hidden" name="ttoe" value="yes">
        <input type="submit" value="Toevoegen">        
            </form >  </pre> </html>
_END;
