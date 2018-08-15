<?php

//USE THIS:		http://ovsa.njit.edu/flaremon/daily/2018/XSP20180624.png
//ALSO THIS:	http://ovsa.njit.edu/qlookimg_10m/2018/02/05/movie_20180205.html
//AND THIS:		http://ovsa.njit.edu/qlookimg_10m/2018/02/05/eovsa_qlimg_20180205T160320.png

$type = $_POST["type"];
$date = $_POST["date"];
$y = 434/39;

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

	//ADD ERROR: NO GRAPH/MOVIE FOR THIS DATE (LOW PRIORITY)
	
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
	
	//FIX THIS
	if (get_headers($picurl)[0] == "HTTP/1.1 404 Not Found")
		echo "<img src='no_graph.png'>";
	else
		echo "$pic<br>";
	
	if (get_headers($movieurl)[0] == "HTTP/1.1 404 Not Found")
		echo "<img src='no_movie.png'>";
	else
		echo "$movie<br>";
	
}

else if ($type == "time") {
	$time = $_POST["time"];
	$date = date('Y-m-d h:i:s a',strtotime($date."+810 minutes"));
	$time = floor($time * 46800/(868 / $y));
	$date = date("Y-m-d H:i:s", strtotime($date) + $time);
	$date2 = date("Y-m-d H:i:s", strtotime($date) + floor(46800/(868 / $y)));
	
	$year = substr($date, 0, 4);
	$month = substr($date, 5, 2);
	$day = substr($date, 8, 2);
	$hour = substr($date, 11, 2);
	$minute = substr($date, 14, 2);
	$second = substr($date, 17, 2);
	
	$str = file_get_contents("http://ovsa.njit.edu/qlookimg_10m/$year/$month/$day/");
	$pattern = '#(www\.|https?://)?[a-z0-9]+\.[a-z0-9]{2,4}\S*#i';
	preg_match_all($pattern, $str, $matches, PREG_PATTERN_ORDER);
	$c = 0;
	foreach ($matches[0] as $match) {
		if (strpos($match, ".png")) {
			$pic = substr($match, 21, 31);
			$frametime = substr($pic, 21, 2).":".substr($pic, 23, 2).":".substr($pic, 25, 2);
			$frame = "http://ovsa.njit.edu/qlookimg_10m/$year/$month/$day/$pic";
			if (strtotime($frametime) > strtotime(substr($date, 11)) and strtotime($frametime) < strtotime(substr($date2, 11))) {
				echo "<h3>Frame at $frametime:</h3>";
				echo "<img src='$frame'>";
				$c += 1;
			}
		}
	}
	if ($c == 0) {
		echo "<img src='no_frame.png'>";
	}
	
	//$frameurl = "http://ovsa.njit.edu/qlookimg_10m/$year/$month/$day/eovsa_qlimg_$year$month$day"."T$hour$minute$second.png";
	//echo "<img src='$frameurl'><br>";
	//echo "Image: <a href='$frameurl' target='_blank'>$frameurl</a><br>";
	

	
}

?>