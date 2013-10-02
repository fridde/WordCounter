<?php

include "include.php";
$ini_array = parse_ini_file("config.ini");
$otherOptions = explode(",", $ini_array["other_options"]);

if(empty($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Please input"');
    header('HTTP/1.0 401 Unauthorized');
    echo $CANCEL_TEXT;
    exit;
} else {
    echo "Username: ".$_SERVER['PHP_AUTH_USER']."<br>";
    if(($_SERVER['PHP_AUTH_USER'] != $USER) || ($_SERVER['PHP_AUTH_PW'] != $PASSWORD)) {
        echo "Login Failed!";
    } else {
        echo "You're logged in as " . $USER;
    }
}
//--------------------------
// Preamble
//--------------------------
$rules = create_rules_from_ini($ini_array);

$wordsToRemove = explode(",", $ini_array["remove"]);
$wordsToRemove = array_map("trim", $wordsToRemove);
if (count($wordsToRemove) == 1) {
    $wordsToRemove = array($wordsToRemove);
}

$fileArray = array();
$handle = opendir('files');

while (false !== ($entry = readdir($handle))) {
    if (!in_array($entry, array(".", ".."))) {
        $fileArray[] = $entry;
    }

}
closedir($handle);
sort($fileArray);

foreach ($fileArray as $file) {
    $str = file_get_contents("files/" . $file);
    $frequencies = array_count_values(str_word_count($str, 1));
    $word_count = str_word_count($str, 0);

    $wordArray[$file] = array("frequencies" => $frequencies, "wordCount" => $word_count);
}

$normFile = $ini_array["normFile"];

$wordArray[$normFile]["frequencies"] = filter_words($wordArray[$normFile]["frequencies"], $rules);
$wordArray = sort_according_to($wordArray, $normFile);
$wordArray = calculate_frequencies($wordArray);
$excludedWords = explode(",", $ini_array["exclude"]);

$includedFiles = explode(",", $ini_array["includeFile"]);
$allFiles = get_all_files();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link type="text/css" rel="stylesheet" href="stylesheet.css"/>
		<script src="jquery-2.0.3.min.js"></script>
		<script src="jquery.dataTables.js"></script>
		<script src="ColReorder.js"></script>
		<script src="TableTools.js"></script>

		<title>WordCounter</title>
	</head>
	<body>

		<div id="header">
			<a href="index.php"> <h1>Word Frequency Counter</h1> </a>
		</div>

		<div id="navbar">
			<ul>
				<li>
					<a href="index.php">Home</a>
				</li>
				<li>
					<a href="upload.php">Upload</a>
				</li>
				<li>
					<a href="configuration.php">Configuration</a>
				</li>
			</ul>

		</div>

		<div id="main">
			<form action="change_config.php" method="post" >
				<p>
					<input type="submit" value="Send changes"/>
				</p>
				<h1>Choose files</h1>
				<table>
                    <tr>
                         <th>Main file</th><th>Include</th><th>File name</th>
                     </tr>
					<?php

                    foreach ($allFiles as $fileName) {
                        echo '<tr><td>';
                        if (in_array($fileName, $includedFiles)) {
                            echo '<input type="radio" name="normFile" value="' . $fileName . '" ';
                            
                            if ($fileName == $normFile) {
                                echo 'checked';
                            }
                         echo '>'; 
                        }
                        echo '</td>';

                        echo '<td><input type="checkbox" name="includeFile[]" value="' . $fileName . '" ';
                        if (in_array($fileName, $includedFiles)) {
                            echo "checked";
                        }
                        echo '></td>';
                        echo '<td>' . $fileName . '</td>';
                        echo '</tr>';
                    }
					?>
					</tr>
					</table>

					<h1>Exclude words</h1>
					<table>
					<?php
                    $first = reset($wordArray);
                    $wordCol = array_keys($first["frequencies"]);
                    $i = 0;
                    echo '<tr>';
                    foreach ($wordCol as $word) {
                        $i++;
                        echo '<td><input type="checkbox" name="exclude[]" value="' . $word . '"></td>';
                        echo "<td>" . $word . "</td>";
                        if ($i % 8 == 0) {
                            echo "</tr><tr>";
                        }
                    }
                    echo "</tr>";
					?>
					</table>
					<h1>Include words</h1>
					<table>
					<?php
                    $i = 0;
                    echo '<tr>';
                    foreach ($excludedWords as $word) {
                        $i++;
                        echo '<td><input type="checkbox" name="include[]" value="' . $word . '"></td>';
                        echo "<td>" . $word . "</td>";
                        if ($i % 8 == 0) {
                            echo "</tr><tr>";
                        }
                    }
                    echo "</tr>";
					?>
					</table>
					<h1>Other options</h1>
					<table>
					<?php

                    foreach ($otherOptions as $optionName) {
                        echo '<tr>';
                        echo '<td>' . $optionName . '</td>';
                        echo '<td><input type="text" name="' . $optionName . '" value="' . $ini_array[$optionName] . '"></td>';
                        echo '</tr>';
                    }
					?>
				</table>
			</form>

		</div>

		<div id="footer"></div>

	</body>
</html>