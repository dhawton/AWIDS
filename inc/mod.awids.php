<?php
/*********************************************************************************************
 * Copyright (c) 2011, Daniel A. Hawton
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *    * Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the
 *      documentation and/or other materials provided with the distribution.
 *    * Neither the name of the author nor the names of its contributors may be
 *      used to endorse or promote products derived from this software without
 *      specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL COPYRIGHT HOLDER BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *********************************************************************************************/

function
findNear($type, $lat, $long)
{
	global $IDS;

	if ($type == 1) { $type = "Navaids"; }
	if ($type == 2) { $type = "Navaids"; $ext = "VOR"; }
	if ($type == 3) { $type = "Navaids"; $ext = "NDB"; }
	if ($type == 4) { $type = "Fixes"; }
	if ($type == 5) { $type = "Airports"; }

	$data = array();
	$x = 0;
	$IDS->db_build($db);
	$query = "SELECT `Navaid`,`name`,`lat`,`long`,`type` FROM `Navaids` WHERE `lat` < '";
	$query .= $lat + 2;
	$query .="' AND `lat` > '";
	$query .=  $lat - 2;
	$query .="' AND `long` < '";
	$query .= $long + 2;
	$query .= "' AND `long` > '";
	$query .= $long - 2;
	$query .= "'";
	$query .= ((isset($ext))?' AND `type`=\''.$ext.'\'':'');
	$query .= " ORDER BY `Navaid`";
	$IDS->db_query($db, $res, $query);
	while ($row = mysql_fetch_assoc($res)) {
		$name = $row['name'];
		$id = $row['Navaid'];
		$lat = $row['lat'];
		$long = $row['long'];
		$type = $row['type'];
		$data[$x] = $id;
		$data[$id]['name'] = $name;
		$data[$id]['lat'] = $lat;
		$data[$id]['long'] = $long;
		$data[$id]['type'] = $type;
		$x++;
	}

	return $data;
}

function
findNearest($type, $lat, $long)
{
	global $IDS;

	$data = array();

	$data = findNear($type, $lat, $long);

	$dist = array();

	for ($i = 0 ; $data[$i] ; $i++)
	{
		$point = $data[$i];
		$dist[$point] = round(calc_dist($lat, $long, $data[$point]['lat'], $data[$point]['long']), 2);
	}
	asort($dist);	
	$arkeys = array();
	$arkeys = array_keys($dist);
	$point = $arkeys[0];
	$nar = array();
	$nar[0] = $point;
	$nar[$point]['name'] = $data[$point]['name'];
	$nar[$point]['lat'] = $data[$point]['lat'];
	$nar[$point]['long'] = $data[$point]['long'];
	$nar[$point]['type'] = $data[$point]['type'];

	return $nar;
}

function
calc_dist($lat1,$lon1,$lat2, $lon2)
{
	$theta = $lon1 - $lon2; 
	$dist = sin(toRad($lat1)) * sin(toRad($lat2)) +  cos(toRad($lat1)) * cos(toRad($lat2)) * cos(toRad($theta)); 
	$dist = acos($dist); 
	$dist = toDeg($dist); 
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	return $miles * 0.8684;
}

function
calc_bearing ($lat1,$lon1,$lat2,$lon2)
{
	return (toDeg(atan2(sin(toRad($lon2) - toRad($lon1)) * cos(toRad($lat2)), cos(toRad($lat1)) * sin(toRad($lat2)) - sin(toRad($lat1)) * cos(toRad($lat2)) * cos(toRad($lon2) - toRad($lon1)))) + 360) % 360;
}

function
toRad($deg)
{
        return $deg * pi() / 180;
}

function
toDeg($rad)
{
        return $rad * 180/pi();
}

function
calc_point_brngdist($start, $dist, $brng)
{
        $lat1 = toRad($start[0]);
        $lon1 = toRad($start[1]);
        $dist = $dist / 6371.01;
        $brng = toRad($brng);

        $lat2 = asin(sin($lat1)*cos($dist)+cos($lat1)*sin($dist)*cos($brng));
        $lon2 = $lon1 + atan2(sin($brng)*sin($dist)*cos($lat1),
                          cos($dist)-sin($lat1)*sin($lat2));
        $lon2 = fmod(($lon2+3*pi()),(2*pi())) - pi();
        return array(toDeg($lat2),toDeg($lon2));
}

?>
