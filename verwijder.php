<?php

require_once 'dev-settings.php';
require_once 'login.php';
require_once 'functions-sql.php';

$mysqli = new mysqli($hn, $un, $pw, $db);
if ($mysqli->connect_error) die("fatal error");

$id = sanitizeString($_GET["id"]);
$temail = isset($_GET["email"]) ? sanitizeString($_GET["email"]) : "";
$ttelnr = isset($_GET["telefoon"]) ? sanitizeString($_GET["telefoon"]) : "";

if (isset($_POST['lver'])) {

    $lidnr = $id;

    $mysqli->multi_query("DELETE  FROM email WHERE lidnummer='$lidnr'");
    while ($mysqli->next_result()) {
        if ($mysqli->more_results()) break;
    }

    $mysqli->query("DELETE  FROM telefoonnummers WHERE lidnummer='$lidnr'");
    $mysqli->query("DELETE  FROM lid WHERE lidnummer='$lidnr'");
    header("Location: ledenbestand.php");
}

if (isset($_POST['ever'])) {

    $lidnr = $id;
    $email = sanitizeString($_POST['email']);


    $result = $mysqli->query("DELETE  FROM email WHERE lidnummer='$lidnr' && emailadres='$temail'");
    if (!$result) die("tabase access failed");
    if ($result) echo "Dit emailadres is verwijderd. <br><br>";
    header("Refresh:0");
}

if (isset($_POST['tver'])) {

    $lidnr = $id;
    $telnr = sanitizeString($_POST['telnr']);

    $result = $mysqli->query("DELETE  FROM telefoonnummers WHERE lidnummer='$lidnr' && telefoonnummer='$ttelnr'");
    if (!$result) die("tabase access failed");
    if ($result) echo "Dit telefoonnummer is verwijderd. <br><br>";
    header("Refresh:0");
}


echo "    <html><head><title>Verander lid</title></head><body>
<pre> <button type='button'><a href='ledenbestand.php'>Terug</a></button><style>
    a {
        text-decoration: none;
        color: black;
    }
</style>
----------------------------------------------------------------------------------------------------------------------   
<h3>Wilt u uw email verwijderen dan kan dat hier.</h3>"
    . "Deze emailadressen zijn bekend bij ons";
$lzien = "SELECT email.emailadres FROM email WHERE lidnummer=$id";
$result = $mysqli->query($lzien);
if (!$result) die("Database access failed");

$rows = $result->num_rows;
echo "<table><tr><th>Emailadres</th></tr>";
for ($j = 0; $j < $rows; ++$j) {
    $row = $result->fetch_array(MYSQLI_NUM);
    $n = $row[0];

    echo "<tr>";

    for ($k = 0; $k < 1; ++$k) {
        echo "<td>" . htmlspecialchars($row[$k]) . "</td> ";
    }

    echo "<td><button type='submit' value='veranderen' ><a href='verwijder.php?id=$id&email=$n'>Selecteren</a>";
    echo "</tr>";
}

echo "</table>";



echo <<<_END
       
        <form method="post" action="" form="verwijder">
         Lidnummer: $id
        Te verwijderen emailadres <input type="text" name="oemail" value="$temail">
        <input type="hidden" name="ever" value="yes">
        <input type="submit" value="Verwijderen">        
            </form >  
----------------------------------------------------------------------------------------------------------------------    
        <h3>Wilt u uw telefoonnummer verwijderen dan kan dat hier.</h3>
        Deze telefoonnummers zijn bekend bij ons:
_END;
$lzie = "SELECT telefoonnummers.telefoonnummer FROM telefoonnummers WHERE lidnummer=$id";
$result = $mysqli->query($lzie);
if (!$result) die("Database access failed");

$rows = $result->num_rows;
echo "<table><tr><th>Telefoonnumer</th></tr>";
for ($j = 0; $j < $rows; ++$j) {
    $row = $result->fetch_array(MYSQLI_NUM);
    $n = $row[0];

    echo "<tr>";

    for ($k = 0; $k < 1; ++$k) {
        echo "<td>" . htmlspecialchars($row[$k]) . "</td> ";
    }

    echo "<td><button type='submit' value='veranderen' ><a href='verwijder.php?id=$id&telefoon=$n'>Selecteren</a>";
    echo "</tr>";
}

echo "</table>";

echo <<<_END

        <form method="post" action="" form="verwijder">
         Lidnummer: $id
        Telefoonnummer <input type="text" name="telnr" value="$ttelnr">
        <input type="hidden" name="tver" value="yes">
        <input type="submit" value="Verwijderen">        
            </form >      
----------------------------------------------------------------------------------------------------------------------                     
        <h3>Wilt u uw gegevens graag verwijderen uit het systeem vul dan hier uw lidnummer en  achternaam in.
        Zorg dat je het zeker weet want u kunt deze informatie niet meer terughalen.
        U kunt uw lidnummer op de vorige pagina vinden.</h3>

        <form method="post" action="" form="verwijder">
         Lidnummer: $id
        <input type="hidden" name="lver" value="yes">
        <input type="submit" value="Verwijderen">        
            </form >        
        </pre>
    </body>
</html>
_END;
