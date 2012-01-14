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

require_once("/home/zjxartcc/www/ids/inc/include.php");
header("Content-type: text/plain");

if (!isset($_REQUEST['field']))
{
?>
<table border="0" cellspacing="0" cellpadding="2" style="color: #ffffff">
<tr><td>Airport:</td><td><select id="atisfield"><?php
$IDS->db_build($db);
if ($_SESSION['facility'] == $CONF['ENRFacility']) {
	$IDS->db_query($db,$res,"SELECT `IATA` FROM `Fields` ORDER BY `IATA`");
} else {
	$IDS->db_query($db,$res,"SELECT `IATA` FROM `Fields` WHERE `FacilityID`='".$_SESSION['facility']."' ORDER BY `IATA`");
}
while ($row = mysql_fetch_assoc($res)) { echo '<option value="'.$row['IATA'].'">'.$row['IATA'].'</option>'; }
?></select></td></tr>
<tr><td>&nbsp;</td><td><input type="button" value="Submit" onClick="processATISx()"></td></tr>
</table>
<?php
} else {
	$IDS->db_build($db);
	$IDS->db_execute($db, "UPDATE `Fields` SET `ATIS`='', `Approach`='', `ArrivalRunways`='', `DepartureRunways`='' WHERE `IATA`='".$IDS->db_safe($_REQUEST['field'])."' LIMIT 1");
	$fac = "ATIS".$_REQUEST['field'];
	$_SESSION[$fac] = $_REQUEST['atis'];
	if (mysql_affected_rows($db) == 1) { echo "1"; } else { echo "-1"; }
}
?>
