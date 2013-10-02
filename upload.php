<?php
        include "include.php";
        $ini_array = parse_ini_file("config.ini");

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

 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" rel="stylesheet" href="stylesheet.css"/>
        
        <title>WordCounter</title>
    </head>
    <body>
        <div id="header">
            <a href="index.php"> <h1>WordCounter</h1> </a>
        </div>

        <div id="navbar">
            <ul>
                <li>    <a href="index.php">Home</a></li>
                <li>    <a href="upload.php">Upload</a></li>
            </ul>

        </div>

        <div id="main">
           
           <h3>File upload</h3>

<p>Accepted files have to be in .txt-format. Use a converter, for example <a href="http://www.zamzar.com/">Zamzar</a></p>
<form name="form1" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_size; ?>">
  <input type="file" multiple="true" name="upload[]" size="3000"><br>
  
  <!-- Add here more file fields if you need. -->
  Replace files? 
  <input type="checkbox" name="replace" value="y">
  <input type="submit" name="Submit" value="Submit">
</form>
<p><?php echo $multi_upload -> show_error_string(); ?></p>


        </div>

        <div id="footer">
            <p>Max. filesize is <?php echo $max_size; ?> bytes. </p>
        </div>
    </body>
</html>
