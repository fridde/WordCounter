<?php
include "include.php";
$ini_array = parse_ini_file("config.ini");
// to give more words to exclude, we'll show more possible words
$rules = create_rules_from_ini($ini_array);
$title = $ini_array["title"];

$wordsToRemove = explode(",", $ini_array["remove_from_header"]);
$wordsToRemove = array_map("trim", $wordsToRemove);
if (count($wordsToRemove) == 1) {
    $wordsToRemove = array($wordsToRemove);
}

$fileArray = explode(",", $ini_array["includeFile"]);

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
$first = reset($wordArray);
$wordCol = array_keys($first["frequencies"]);
//echo print_r($wordCol) . "<br><br>";
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link type="text/css" rel="stylesheet" href="stylesheet.css"/>
		<script src="jquery-2.0.3.min.js"></script>
		<script src="jquery.dataTables.js"></script>
		<script src="TableTools.js"></script>
		<script src="ZeroClipboard.js"></script>
		<script src="ColReorder.js"></script>
		

		<script>
			$(document).ready(function() {
				$('#sortable').dataTable({
					"bPaginate" : false,
					"aaSorting" : [[1, "desc"]],
					"sDom" : 'R<"sortable"Tf>lrtip',
					"oTableTools" : {
						"sSwfPath" : "copy_csv_xls_pdf.swf"
					}
					

				});
			});
		</script>

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
					<a href="admin/upload.php">Upload</a>
				</li>
				<li>
					<a href="admin/configuration.php">Configuration</a>
				</li>
				<li>
                    <a href="https://github.com/fridde/WordCounter/blob/master/README.md">Documentation</a>
                </li>
			</ul>

		</div>

		<div id="main">
		    <h1><?php echo $title ?></h1>
		    <p>Word frequencies are given in % of total words.</p>
				<table id="sortable">
					<thead>
						<tr>
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
                        foreach ($wordCol as $word) {
                            echo '<tr>';
                            echo "<td>" . $word . "</td>";
                            foreach ($wordArray as $fileName => $content) {
                                echo "<td>" . $content["frequencies"][$word] . "</td>";
                            }
                            echo "</tr>";
                        }
                    ?>
</tbody>
</table>

</div>

<div id="footer"></div>

</body>
</html>