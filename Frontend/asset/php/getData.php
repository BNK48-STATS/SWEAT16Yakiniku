<?php
    $mysqli = new mysqli('localhost','root','','yakiniku');
    mysqli_set_charset($mysqli,"utf8");
    $myArray = array();
	$date = mysqli_real_escape_string($mysqli, $_POST['date']);
	if($date == null){
		$date = date("Y-m-d");
	}
    if ($result = $mysqli->query("SELECT * FROM youtube_rank WHERE date = '$date' ORDER BY time DESC")) {
    
        while($row = $result->fetch_array(1)) {
                $myArray[] = $row;
        }
        echo json_encode($myArray);
    }
    
    $result->close();
    $mysqli->close();
?>