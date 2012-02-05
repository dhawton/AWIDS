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
} elseif ($_REQUEST['id'] == -1) {
	$IDS->db_build($db);
	if ($_SESSION['facility'] == $CONF['ENRFacility']) {
		$IDS->db_query($db, $res, "SELECT * FROM `TMUGates` ORDER BY `Facility`, `Gate`");
	} else {
		$IDS->db_query($db, $res, "SELECT * FROM `TMUGates` WHERE `Facility`='".$_SESSION['facility']."' ORDER BY `Gate`");
	}
?>
<table border="0" cellspacing="0" cellpadding="2" style="color: #fff;">
<?php
	while ($row = mysql_fetch_assoc($res)) {
?>
<tr><td style="color: yellow;"><?=$row['Facility']?> / <?=$row['Gate']?></td><td>- <?php
		if ($row['Active'] == -1) { echo "<span class='ClosedGate'>CLOSED</span>"; }
		elseif ($row['Active'] == 0) { echo "<span class='InactiveGate'>Inactive</span>"; }
		else { echo "<span class='ActiveGate'>Active</span> - ";
			if ($row['SpdRestriction'] != 0) { echo $row['SpdRestriction'] . " KIAS"; }
			if ($row['SpdRestriction'] != 0 && ($row['MIT'] != "" && $row['MIT'] != 0)) { echo " - "; }
			if ($row['MIT'] != "" && $row['MIT'] != 0) { echo $row['MIT'] . " MIT"; }
		}
	}
?>
</table>
<?php
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
