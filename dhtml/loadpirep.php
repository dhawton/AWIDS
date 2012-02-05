<?php
/*********************************************************************************************
 * Copyright (c) 2012, Daniel A. Hawton
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

require_once("../inc/include.php");
header("Content-type: text/plain");

if (!isset($_REQUEST['id']))
{
?>
<table border="0" cellspacing="0" cellpadding="0"
<tr><td>Report:</td><td><select id="report_type"><option value="UA">UA - Regular</option><option value="UUA">Urgent</option></select></td></tr>
<tr><td>Callsign:<br><i><small>For Searching</small></i></td><td><input type="text" id="callsign"></td></tr>
<tr><td>Location:</td><td><input type="text" id="navaid" size="5"> R/B-<input type="text" id="bearing" size="3">@<input type="text" id="dme" size="3"></td></tr>
<tr><td>Aircraft Type:</td><td><input type="text" id="ac_type" size="4" /> (ICAO form)</td></tr>
<tr><td>Altitude:</td><td><input type="text" id="fl" size="3" /> (3 digit form)</td></tr>
<tr><td>Sky Cover:</td><td><input type="text" id="sk" size="10" maxsize="32"></td></tr>
<tr><td>Visibility/Wx:</td><td><input type="text" id="wx" size="10" maxsize="64"></td></tr>
<tr><td>Temperature:</td><td><input type="text" id="ta" size="3" maxsize="3"> (Prefix below 0 with "-")</td></tr>
<tr><td>Wind:</td><td><input type="text" id="wv_dir" size="3" maxsize="3"> @ <input type="text" id="wv_vel" size="3" maxsize="3"></td></tr>
<tr><td>Turbulence:</td><td><input type="text" id="tb" size="10" maxsize="64"></td></tr>
<tr><td>Icing:</td><td><input type="text" id="ic" size="10" maxsize="64"></td></tr>
<tr><td>Remarks:</td><td><input type="text" id="rm" size="10" maxsize="255"></td></tr>
<tr><td colspan="2"><input type="button" value="Submit" onClick="submitPIREP(); return false;"></td></tr>
</table>
<script type="text/javascript">
useBSNns = true;
//document.write('<script type="text/javascript" src="/js/pirep/autocomplete.js"><\/script>');

var as = new bsn.AutoSuggest('callsign', {script: '/dhtml/pilotlist.php?', varname: 'input', maxresults: 5 });
</script>
<?php
} elseif ($_REQUEST['id'] == -1) {
	$IDS->db_build($db);
	$IDS->db_query($db, $res, "SELECT *, DATE_FORMAT(`PIREPs`.`time`, '%H%i') AS `tm` FROM `PIREPs` WHERE (`PIREPs`.`time` + interval 30 minute) > NOW() ORDER BY `PIREPs`.`time` DESC");
	while ($row = mysql_fetch_assoc($res)) {
?>
<table border="0" cellspacing="0" cellpadding="0" width="100%" style="color: #fff">
<tr><td style="padding-right: 15px;"><?=$row['report_type']?></td><td valign="top"><?php
		if ($row['location']) { echo "OV/" . $row['location'] . " "; }
		if ($row['tm']) { echo "TM/" . $row['tm'] . " "; }
		if ($row['altitude']) { echo "FL/" . $row['altitude'] . " "; }
		if ($row['ac_type']) { echo "TP/" . $row['ac_type'] . " "; }
		if ($row['sky_cover']) { echo "SK/" . $row['sky_cover'] . " "; }
		if ($row['vis_wx']) { echo "WX/" . $row['vis_wx'] . " "; }
		if ($row['temp']) { echo "TA/" . $row['temp'] . " "; }
		if ($row['wind']) { echo "WV/" . $row['wind'] . " "; }
		if ($row['turb']) { echo "TB/" . $row['turb'] . " "; }
		if ($row['icing']) { echo "IC/" . $row['icing'] . " "; }
		if ($row['remarks']) { echo "RM/" . $row['remarks'] . " "; }
?></td></tr>
</table><br>
<?php
	}
} else {
	$IDS->db_build($db);
	$IDS->db_query($db, $res, "SELECT `Pilots`.`lat`,`Pilots`.`long`,`Pilots`.`groundspeed`,`Pilots`.`planned_aircraft`,`Pilots`.`heading`,`Config`.`val` FROM `Pilots`,`Config` WHERE `Config`.`var`='LastPilotUpdate' AND `Pilots`.`callsign`='".$_REQUEST['id']."'");
	$row = mysql_fetch_assoc($res);
	$dist = $row['groundspeed'] * ((time() - $row['val'])/60/60) * 1.852;
	$coor = calc_point_brngdist(array($row['lat'], $row['long']), $dist, $row['heading']);
	$nav = findNearest(2, $coor[0], $coor[1]);
	$aid = $nav[0];
	$bearing = calc_bearing($nav[$aid]['lat'], $nav[$aid]['long'], $coor[0], $coor[1]);
	$d = calc_dist($nav[$aid]['lat'], $nav[$aid]['long'],$coor[0],$coor[1]);
	$dp = round($d, 0);
	if ($dp != 0) { $b = sprintf("%03d",(int)$bearing); $d = sprintf("%03d",(int)$d); }
	else { $b = $d = 0; }
	echo $row['planned_aircraft'] . "%" . $aid . "%" . $b . "%" . $d;
}
?>
