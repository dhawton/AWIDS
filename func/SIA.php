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
?>
<div style="padding-left: 10px; padding-right: 10px;">
<?php
$IDS->db_build($db);
$IDS->db_query($db,$res,"SELECT `FullName` FROM `Facilities` WHERE `FacilityID`='".$_SESSION['facility']."' LIMIT 1");
$row = mysql_fetch_assoc($res);
?>
<center><font color="#ffcc00"><?=$row['FullName']?> Status Information Area</font></center>
<div style="width: 100%; height: 275px; overflow: auto;" id="weatherbox">
<table border="1" cellspacing="0" cellpadding="2" style="width: 100%; background-color: #5c5c5c;">
<?php
$IDS->db_query($db,$res,"SELECT `Fields`.`FieldID`,`Fields`.`IATA`,`Fields`.`ATIS`,`Fields`.`ArrivalRunways`,`Fields`.`DepartureRunways`,`Fields`.`Approach`,`Fields`.`EDCT` FROM `Fields` WHERE `FacilityID`='".$_SESSION['facility']."' ORDER BY IATA");
$roster->db_build($rdb);
while ($row = mysql_fetch_assoc($res))
{
	$roster->db_query($rdb,$res2,"SELECT `wind`,`baro`,`metar` FROM `weather` WHERE `icao`='".$row['FieldID']."'");
	$row2 = mysql_fetch_assoc($res2);
?>
<tr><td style="text-align: center; font-weight: bold; font-size: 14px; height: 20px;"><?=$row['IATA']?></td><td rowspan="3" valign="top"><table border="0" cellspacing="0" width="100%" cellpadding="2"><tr><td><?=$row2['metar']?></td></tr><tr><td valign="bottom"><b><?=$row2['wind']?> <?=$row2['baro']?> <?=$row['Approach']?></b></td></tr></table></td><td rowspan="3"><table border="0" cellspacing="0" cellpadding="2"><tr><td style="width: 100px;">A: <?=$row['ArrivalRunways']?></td></tr><tr><td>D: <?=$row['DepartureRunways']?></td></tr></table></td></tr><tr><td style="height: 30px; text-align: center; font-size: 20pt;"><?=(($row['ATIS'])?$row['ATIS']:'NA')?></td></tr><tr><td style="height:20px; font-size: 10pt; text-align: center;"><?=$row['Approach']?></td></tr>
<?php
}
?>
</table></div>
<div style="height:15px; width:100%;"></div>
<table border="1" cellspacing="0" cellpadding="2" style="width: 100%;">
<tr style="text-align: center; font-weight: bold; font-size: 14pt; color: #fff;"><td style="width 33%;">PIREPs</td><td style="width: 33%;">TMU</td><td style="width: 34%;">CD</td></tr>
<tr style="height: 250px; color: #fff; font-size: 10pt;"><td valign="top"><div id="pirepbox" style="overflow: auto; height: 250px; color: #fff;">&nbsp;</div></td><td valign="top"><div id="tmubox" style="overflow: auto; height: 250px; color: #fff;">&nbsp;</div></td><td valign="top"><div id="crdbox" style="overflow: auto; height: 250px; color: #fff;">&nbsp;</td></tr>
</table>
<script type="text/javascript">
updateWeather();
updatePIREP();
updateTMU();
</script>
