<?php 
header("refresh: 600;");
date_default_timezone_set("Asia/Bangkok");
$date = new DateTime();
$minitime = $date->getTimestamp();
$mysql         = [
    'ip'   => 'localhost',
    'user' => 'root',
    'pass' => '',
    'db'   => 'Yakiniku',
];
try {
    $db = new PDO("mysql:host=" . $mysql['ip'] . ";dbname=" . $mysql['db'] . ";charset=utf8", $mysql['user'], $mysql['pass']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error! ####: " . $e->getMessage() . "<br/>";
    exit();
}
function q($sql = '', $prepare = null)
{
    global $db;
    $result = $db->prepare($sql);
    if (isset($prepare)) {
        $result->execute($prepare);
    } else {
        $result->execute($prepare);
    }
    return $result;
}

//check view

$apikey = ''; // Your youtube api here...

$lines  = @file("array.txt");

foreach($lines  as $line ){

	$data =  json_decode($line, true);

	foreach($data as $item) {

		$vid = $item['uid'];
		$name = $item['name'];

		$link = file_get_contents('https://www.googleapis.com/youtube/v3/videos?key='.$apikey.'&part=statistics&id='.$vid);

		$view =  json_decode($link, true);

		$date = date("Y-m-d");
		$time = date("H:i:s");


		$viewcount = $view['items']['0']['statistics']['viewCount'];
        $likecount = $view['items']['0']['statistics']['likeCount'];
        $dislikecount = $view['items']['0']['statistics']['dislikeCount'];
        $commentcount = $view['items']['0']['statistics']['commentCount'];

		q(
			"INSERT INTO youtube_rank (name,uid,views,likes,dislike,comment,date,time) VALUES (?,?,?,?,?,?,?,?)",
			[
				$name,
				$vid,
				$viewcount,
				$likecount,
				$dislikecount,
				$commentcount,
				$date,
				$time
				
			]
		);
	}
}

