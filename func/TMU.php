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
?>
<div id="tmugates" style="width: 100%; height: 100%; overflow: auto;">
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr><td style="width: 200px;"><b>Facility / Gate</b></td><td><b>- Status - Spd Restriction - MIT Restriction</b></td></tr>
<?php
$IDS->db_build($db);
$IDS->db_query($db, $res, "SELECT * FROM `TMUGates` ORDER BY `Facility`,`Gate`");

while ($row = mysql_fetch_assoc($res))
{
	if ($row['SpdRestriction'] == 0) { $row['SpdRestriction'] = ""; }
	if ($row['MIT'] == 0) { $row['MIT'] = ""; }
?>
<tr id="<?=$row['Facility'] . $row['Gate']?>row"><td><?=$row['Facility']?> / <?=$row['Gate']?></td><td>- <select id="<?=$row['Facility']?><?=$row['Gate']?>status" onChange="checkStatus('<?=$row['Facility']?>', '<?=$row['Gate']?>')"><option value="-1"<?=(($row['Active']==-1)?' selected="true"':'')?>>Closed</option><option value="0"<?=(($row['Active']==0)?' selected="true"':'')?>>Inactive</option><option value="1"<?=(($row['Active']==1)?' selected="true"':'')?>>Active</option></select> - <input type="text" id="<?=$row['Facility']?><?=$row['Gate']?>spd"<?php if ($row['Active'] != 1) { ?> style="visibility: hidden;"<?php } ?> value="<?=$row['SpdRestriction']?>" size="3" maxsize="3"> - <input type="text" id="<?=$row['Facility']?><?=$row['Gate']?>mit"<?php if ($row['Active'] != 1) { ?> style="visibility: hidden;"<?php } ?> value="<?=$row['MIT']?>" size="3" maxsize="3"> <input type="button" value="Apply" onClick="updateTMUGate('<?=$row['Facility']?>', '<?=$row['Gate']?>');" /></td></tr>
<?php
	$gates[] = $row['Facility'] . "/" . $row['Gate'];
}
?>
</table>
</div>
