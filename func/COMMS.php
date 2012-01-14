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

$IDS->db_build($db);
$IDS->db_query($db,$res,"SELECT `FullName` FROM `Facilities` WHERE `FacilityID`='".$_SESSION['facility']."' LIMIT 1");
$row = mysql_fetch_assoc($res);
?>
<center><font color="#ffcc00"><?=$row['FullName']?> Communications Table</font></center>
<?php
$f = $_SESSION['facility'];
if ($f != "ZJX") {
        $IDS->db_query($db,$res,"SELECT `IATA`,`FullName` FROM `Fields` WHERE `FacilityID`='".$_SESSION['facility']."' AND `Uncontrolled` NOT LIKE '1' ORDER BY `IATA`");
} else {
        $IDS->db_query($db,$res,"SELECT `IATA`,`FullName` FROM `Fields` WHERE `Uncontrolled` NOT LIKE '1' ORDER BY `IATA`");
}
?>
<center><table border="0" cellpadding="5" cellspacing="0" style="width: 50%; color: #fff;">
<tr><td colspan="3" style="text-align: center; color: yellow; text-decoration: underline;">En-Route Control</td></tr>
<?php
$roster->db_build($rdb);
$roster->db_query($rdb,$rres, "SELECT `position`,`freq`,`name` FROM `positions` WHERE `facility`='En-' ORDER BY `type`,`pos`");
while ($rrow = mysql_fetch_assoc($rres)) { echo "<tr><td>".$rrow['position']."</td><td>".$rrow['name']."</td><td>".$rrow['freq']."</td></tr>"; }
?>
<tr><td colspan="3">&nbsp;</td></tr>
<?php
while ($row = mysql_fetch_assoc($res))
{
	$roster->db_query($rdb,$rres, "SELECT `position`,`freq`,`name` FROM `positions` WHERE `position` LIKE \"".$row['IATA']."%\" AND `position` NOT LIKE \"%CTR\" ORDER BY `type` DESC,`pos`");
	echo '<tr><td colspan="3" style="text-align: center; color: white; text-decoration: underline;">'.$row['IATA'] . ' - '. $row['FullName']. '</td></tr>';
	if (mysql_num_rows($rres) == 0) { echo '<tr><td colspan="3" style="text-align: center;"><i>No positions found...</i></td></tr>'; }
	while ($rrow = mysql_fetch_assoc($rres))
	{
		echo '<tr><td>'.$rrow['position'].'</td><td>'.$rrow['name'].'</td><td>'.$rrow['freq'].'</td></tr>';
	}
	echo '<tr><td colspan="3">&nbsp;</td></tr>';
}
?>
</table></center>

