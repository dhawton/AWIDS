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

// Set this to the IDS install directory
$IDS = "/home/zjxartcc/www/ids";
$pilotstable = "Pilots";
$configtable = "Config";
$sources = array('http://www.net-flyer.net/DataFeed/vatsim-data.txt',
                 'http://www.klain.net/sidata/vatsim-data.txt',
                 'http://fsproshop.com/servinfo/vatsim-data.txt',
                 'http://info.vroute.net/vatsim-data.txt',
                 'http://data.vattastic.com/vatsim-data.txt');
// Coming soon
// $airwaystable = "Airways";
// $vortable = "Navaids";
// $fixtable = "Fixes";

// Stop editing here

require($IDS."/inc/config.php");
require($IDS."/inc/mod.awdb.php"); // Load my special DB module

date_default_timezone_set("UTC");

$awdb = new AWDB($CONF['db']['server'],$CONF['db']['user'],$CONF['db']['pass'],$CONF['db']['ids_db']);

$awdb->db_build($db);
$awdb->db_query($db, $res, "SELECT `var`,`val` FROM `$configtable` WHERE `var`='UpdatePilots' OR `var`='LastPilotUpdate' OR `var`='PilotReloadTime'");
while ($row = mysql_fetch_array($res)) { $i = $row[0]; $config[$i] = $row[1]; }

if ($config['UpdatePilots'] == 0) { exit; }
//if (($config['LastPilotUpdate'] + ((int)$config['PilotReloadTime'] * 60)) > time()) { exit; }

$sid = mt_rand(0, count($sources));
$content = file_get_contents($sources[$sid]);

if ($content == false) { exit; }
$lines = explode("\n", $content);

$awdb->db_execute($db, "DELETE FROM `$pilotstable`");

foreach ($lines as $line)
{
	$line = rtrim($line);

	if (preg_match("/^RELOAD = (\d+)/", $line, $matches)) { $awdb->db_execute($db, "INSERT INTO `$configtable` VALUES('PilotReloadTime', '".$matches[0]."') ON DUPLICATE KEY UPDATE `val`='$matches[1]'"); }

	if (preg_match("/^UPDATE = (\d+)/", $line, $matches)) {
		$ds = $matches[1];
		$ts = mktime(substr($ds, 8, 2), substr($ds, 10, 2), substr($ds, 12, 2), substr($ds, 4, 2), substr($ds, 6, 2), substr($ds, 0, 4));
		$awdb->db_execute($db, "INSERT INTO `$configtable` VALUES('LastPilotUpdate', '".$ts."') ON DUPLICATE KEY UPDATE `val`='$ts'");
	}

	if (preg_match("/^!CLIENTS/", $line)) { $inclients = 1; }
	elseif (preg_match("/^!/", $line)) { $inclients = 0; }

	if (preg_match("/^;/", $line) == 0 && $inclients == 1) {
		$data = explode(":", $line);
		if ($data[3] == "PILOT") {
			$awdb->db_query($db, $res, "SELECT callsign FROM `$pilotstable` WHERE `callsign`='".$data[0]."'");
			$ac = preg_replace("/^[A-Z]\//", '', $data[9]);
			$ac = preg_replace("/\/[A-Z]/", '', $ac);
//			if (mysql_num_rows($res)== 1)
//			{
//				@mysql_free_result($res);
//				$awdb->db_execute($db, "UPDATE `$pilotstable` SET `lat`='$data[5]', `long`='$data[6]', `altitude`='$data[7]', `groundspeed`='$data[8]', `planned_aircraft`='$ac', `dep`='$data[11]', `dest`='$data[13]', `heading`='$data[38]' WHERE `callsign`='$data[0]' LIMIT 1");
//			} else {
				$awdb->db_execute($db, "INSERT INTO `$pilotstable` VALUES('$data[0]', '$data[1]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$ac', '$data[11]', '$data[13]', '$data[38]', '')");
//			}
		}
	}
}
?>
