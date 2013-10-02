<?php
include "include.php";
$ini_array = parse_ini_file("config.ini");
$rules = create_rules_from_ini($ini_array);
//echo print_r($rules) . "<br><br>";
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

$normFile = "IEA WEO 1999.txt";

$wordArray[$normFile]["frequencies"] = filter_words($wordArray[$normFile]["frequencies"], $rules);
$wordArray = sort_according_to($wordArray, $normFile);
$wordArray = calculate_frequencies($wordArray);
//echo print_r($wordArray) . "<br><br>";
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

		<script>
			$(document).ready(function() {
				$('#sortable').dataTable({
					"bPaginate" : false,
					"sDom" : 'Rlfrtip',
					//"sDom" : 'T<"clear">lfrtip'

				});
			});
		</script>

		<title>WordCounter</title>
	</head>
	<body>

		<div id="header">
			<a href="index.php"> <h1>Table</h1> </a>
		</div>

		<div id="navbar">
			<ul>
				<li>
					<a href="index.php">Home</a>
				</li>
				<li>
					<a href="upload.php">Upload</a>
				</li>
			</ul>

		</div>

		<div id="main">
			<form action="change_config.php" method="post">
				<table id="sortable">
					<thead>
						<tr>
							<th></th>
							<th>Word</th>
							<?php
                            foreach ($wordArray as $file => $content) {
                                echo "<th>" . str_replace($wordsToRemove, array(), $file) . "</th>";
                            }
							?>
						</tr>
					</thead>
					<tbody>

						<?php
                        $first = reset($wordArray);
                        $wordCol = array_keys($first["frequencies"]);
                        foreach ($wordCol as $word) {
                            echo '<tr><td><input type="checkbox" name="exclude[]" value="' . $word . '"></td>';
                            echo "<td>" . $word . "</td>";
                            foreach ($wordArray as $fileName => $content) {
                                if ($fileName == array_keys($first)) {
                                    echo "<td>" . "Bla" . "</td>";
                                } else {
                                    echo "<td>" . $content["frequencies"][$word] . "</td>";
                                }
                            }
                            echo "</tr>";
                        }
						?>
					</tbody>
				</table>

				<input type="submit" />

			</form>

		</div>

		<div id="footer"></div>

	</body>
</html>