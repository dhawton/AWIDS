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
?>
<table cellspacing="0" cellpadding="0" style="width: 100%; background-color: #5c5c5c; border-width: 1px 1px 1px 1px; border-style: solid; border-color: #ff0000;">
<?php
$IDS->db_build($db);
if ($_SESSION['facility'] == $CONF['ENRFacility']) {
	$IDS->db_query($db, $res, "SELECT `Fields`.`FieldID`,`Fields`.`IATA`,`Fields`.`ATIS`,`Fields`.`ArrivalRunways`,`Fields`.`DepartureRunways`,`Fields`.`Approach`,`Fields`.`EDCT` FROM `Fields` ORDER BY `IATA`");
} else {
	$IDS->db_query($db,$res,"SELECT `Fields`.`FieldID`,`Fields`.`IATA`,`Fields`.`ATIS`,`Fields`.`ArrivalRunways`,`Fields`.`DepartureRunways`,`Fields`.`Approach`,`Fields`.`EDCT` FROM `Fields` WHERE `FacilityID`='".$_SESSION['facility']."' ORDER BY IATA");
}
$wx->db_build($rdb);
while ($row = mysql_fetch_assoc($res))
{
        $wx->db_query($rdb,$res2,"SELECT `wind`,`baro`,`metar` FROM `weather` WHERE `icao`='".$row['FieldID']."'");
        $row2 = mysql_fetch_assoc($res2);
	$atisvar = "ATIS".$row['IATA'];
	$flashvar = "fATIS".$row['IATA'];
	if ($_SESSION[$atisvar] != $row['ATIS'] && $_SESSION[$flashvar] != 1) { $atisflash[] = $row['IATA']; }
//	else { $fp = fopen("wxget.log","a"); fwrite($fp,"Didn't set flash for " . $atisvar . "/" .$_SESSION[$atisvar]."/".$flashvar . "/".$_SESSION[$flashvar]."\n"); }
?>
<tr style="height: 15px;"><td style="width: 70px; text-align: center; font-weight: normal; font-size: 10pt; background-color: #00163a; color: #ffffff;"><?=$row['IATA']?></td><td rowspan="3" valign="top" style="border: 1px solid #00163a;"><table border="0" cellspacing="0" width="100%" cellpadding="0" style="font-size: 10pt; height: 50px;"><tr><td><?=substr($row2['metar'],4)?></td></tr><tr><td valign="bottom"><b><?=$row2['wind']?> <?=$row2['baro']?> <?=$row['Approach']?></b></td></tr></table></td><td rowspan="3" style="border: 1px solid #00163a;"><table border="0" cellspacing="0" cellpadding="2"><tr><td style="width: 100px;">A: <?=$row['ArrivalRunways']?></td></tr><tr><td>D: <?=$row['DepartureRunways']?></td></tr></table></td></tr><tr><td style="height: 30px; text-align: center; font-size: 20pt; border: 1px solid #00163a;" id="<?=$row['IATA']?>ATIS" onClick="SIAAckATIS('<?=$row['IATA']?>')"><?=(($row['ATIS'])?$row['ATIS']:'&nbsp;')?></td></tr><tr><td style="height:15px; font-size: 10pt; text-align: center; border: 1px solid #00163a;"><?=(($row['Approach'])?$row['Approach']:'&nbsp;')?></td></tr>
<?php
}
?>
</table>

<script type="text/javascript">
<?php
for ($i = 0 ; $atisflash[$i] ; $i++) {
?>
atisFlash("<?=$atisflash[$i]?>ATIS");
<?php
	$flashvar = "fATIS".$atisflash[$i];
	$_SESSION[$flashvar] = 1;
}
?>
</script>
