<?php

//USE THIS:		http://ovsa.njit.edu/flaremon/daily/2018/XSP20180624.png
//ALSO THIS:	http://ovsa.njit.edu/qlookimg_10m/2018/02/05/movie_20180205.html
//AND THIS:		http://ovsa.njit.edu/qlookimg_10m/2018/02/05/eovsa_qlimg_20180205T160320.png

$type = $_POST["type"];
$date = $_POST["date"];
$y = 434/13;

if ($type == "date") {
	$message = $_POST["message"];

	if ($message == "previous-week") {
		$date = str_replace('-', '/', $date);
		$date = date('Y-m-d',strtotime($date."-7 days"));
	}
	else if ($message == "previous-day") {
		$date = str_replace('-', '/', $date);
		$date = date('Y-m-d',strtotime($date."-1 days"));
	}
	else if ($message == "today") {
		$date = date('Y-m-d');
	}
	else if ($message == "next-day") {
		$date = str_replace('-', '/', $date);
		$date = date('Y-m-d',strtotime($date."+1 days"));
	}
	else if ($message == "next-week") {
		$date = str_replace('-', '/', $date);
		$date = date('Y-m-d',strtotime($date."+7 days"));
	}

	$year = substr($date, 0, 4);
	$month = substr($date, 5, 2);
	$day = substr($date, 8, 2);

	//ADD TIMECLICK (IN PROGRESS)
	//ADD ERROR: NO GRAPH/MOVIE FOR THIS DATE (LOW PRIORITY)
	//WORK ON SPEED PROBLEM (LOW PRIORITY)
	
	$picurl = "http://ovsa.njit.edu/flaremon/daily/$year/XSP$year$month$day.png";
	$pic = "<img src='$picurl' usemap='#workmap'>
			<map name='workmap'>";
	$w = 0;
	for ($x = 57; $x <= 925; $x += $y) {
		$z = $x + $y;
		$pic .= "<area class='time' shape='rect' coords='$x,32,$z,397' name='time' id='$w'>";
		$w += 1;
	}
	$pic .= "</map>";

	$movieurl = "http://ovsa.njit.edu/qlookimg_10m/$year/$month/$day/movie_$year$month$day.html";
	$movie ="<iframe src='$movieurl' id='movie' width=700 height=860></iframe>";

	echo "$date";
	echo "$pic<br>";
	echo "$movie<br>";
}

else if ($type == "time") {
	$time = $_POST["time"];
	$date = date('Y-m-d h:i:s a',strtotime($date."+810 minutes"));
	$time = floor($time * 46800/(868 / $y));
	$date = date("Y-m-d H:i:s", strtotime($date) + $time);
	$date2 = date("Y-m-d H:i:s", strtotime($date) + floor(46800/(868 / $y)));
	echo "Date-time: $date<br>";
	echo "Date-time 2: $date2<br>";
	
	$year = substr($date, 0, 4);
	$month = substr($date, 5, 2);
	$day = substr($date, 8, 2);
	$hour = substr($date, 11, 2);
	$minute = substr($date, 14, 2);
	$second = substr($date, 17, 2);
	
	//PUT CODE FOR GETTING ALL FRAMES HERE
	$str = file_get_contents("http://ovsa.njit.edu/qlookimg_10m/$year/$month/$day/");
	$pattern = '#(www\.|https?://)?[a-z0-9]+\.[a-z0-9]{2,4}\S*#i';
	preg_match_all($pattern, $str, $matches, PREG_PATTERN_ORDER);
	foreach ($matches[0] as $match) {
		if (strpos($match, ".png")) {
			$frame = "http://ovsa.njit.edu/qlookimg_10m/$year/$month/$day/".substr($match, 21, 31);
			echo "Image: <a href='$frame' target='_blank'>$frame</a><br>";
			//echo "$frame<br><br>";
		}
	}
	//END HERE
	
	$frameurl = "http://ovsa.njit.edu/qlookimg_10m/$year/$month/$day/eovsa_qlimg_$year$month$day"."T$hour$minute$second.png";
	echo "<img src='$frameurl'><br>";
	echo "Image: <a href='$frameurl' target='_blank'>$frameurl</a><br>";
	

	
}

?>