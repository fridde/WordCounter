<?php
include "include.php";
$configArrayLines = parse_ini_file("config.ini");
$wordsAlreadyExcluded = array_map("trim", explode(",", $configArrayLines["exclude"]));

foreach ($_REQUEST as $key => $value) {
    //"Case insensitive", "higher than = 3", "exclude = the, in, and", "longer than = 2" , "max=50"

    switch ($key) {
        case 'higher than' :
            break;

        case "exclude" :
            foreach ($value as $word) {
                if (!(in_array($word, $wordsAlreadyExcluded))) {
                    $wordsAlreadyExcluded[] = $word;
                }
            }
            $configArrayLines["exclude"] = $wordsAlreadyExcluded;
            break;

        case "include" :
            foreach ($value as $word) {
                if (($key = array_search('strawberry', $wordsAlreadyExcluded)) !== false) {
                    unset($wordsAlreadyExcluded[$key]);
                }
            }
            $configArrayLines["exclude"] = $wordsAlreadyExcluded;
            break;

        case "longer than" :
            break;

        case "max" :
            break;

        default :
            break;
    }
}

$filename = "config.ini";
$text = "";
foreach ($configArrayLines as $key => $value) {
    if (gettype($value) == "array") {
        $value = implode(",", $value);
    }
    $text .= $key . " = " . $value . "\n";
}

$fh = fopen($filename, "w") or die("Could not open log file.");
fwrite($fh, $text) or die("Could not write file!");
fclose($fh);

redirect("index.php");
?>