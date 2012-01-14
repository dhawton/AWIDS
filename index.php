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

require_once("inc/include.php");

if ($_REQUEST['f'] == "login") {
	require "func/login.php";
}
if ($_REQUEST['f'] == "exit") {
	require "func/exit.php";
}

foreach ($_SESSION as $key => $value)
{
	if (preg_match("/^fATIS/", $key)) { $_SESSION[$key] = 0; }
}

?>
<html>
<head>
  <link rel="stylesheet" href="/css/main.css">
  <title>ZJX IDS</title>
<script type="text/javascript">
function update_clock()
{
	now = new Date();

	hrs = now.getUTCHours();
	hrs = ((hrs<10)?"0"+hrs : hrs);
	mins = now.getUTCMinutes();
	mins = ((mins<10)?"0"+mins : mins);
	secs = now.getUTCSeconds();
	secs = ((secs<10)?"0"+secs : secs);

	document.getElementById("theclockbox").value = hrs + ":" + mins + ":" + secs;

	setTimeout("update_clock()", 1000);
}
</script>
  <script type="text/javascript" src="/js/ajax.js"></script>
  <script type="text/javascript" src="/js/ajaxresponse.js"></script>
  <script type="text/javascript" src="/js/SIA.js"></script>
  <script type="text/javascript" src="/js/TMU.js"></script>
  <script type="text/javascript" src="/js/pirep/autocomplete.js"></script>
</head>
<body onload="update_clock()">
<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; height: 100%;">
<tr><td style="background-color:#5c5c5c;">
  <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
    <tr><td rowspan="2" style="width:90px;"><img src="/images/logo.jpg" alt="ZJX IDS"></td><td id="iconrow1" style="height: 30px;">&nbsp;</td><td rowspan="2" class="clock"><input type="text" name="theclockbox" id="theclockbox" style="width: 160px; font-size: 24pt; text-align: center; border: 0px solid white; background-color: #000; color: #990000;" /></td></tr>
    <tr><td id="iconrow2" style="height: 30px;" valign="middle">&nbsp;</td></tr>
  </table>
</td></tr>
<tr style="height: 100%;"><td style="background-color: #00163a; color: #fff; height: 100%;" valign="center" id="maintd">
<?php if ($_SESSION['loggedin'] != 1 || ($_SESSION['loggedin'] == 1 && !isset($_SESSION['facility']))) { ?>
<center><table border="0" cellspacing="0" cellpadding="0" style="background-color: #5c5c5c; color: #ffffff; width: 260px;">
<tr><td style="width: 20px;"><img src="/images/leftcorner.jpg"/></td><td style="width: 220px; background-color: #ffffff; color: #00163a;">Login to IDS</td><td style="width: 20px;"><img src="/images/rightcorner.jpg" /></td></tr>
<tr><td colspan="3" id="cell_login"  style="border-left: 3px solid white; border-right: 3px solid white; border-bottom: 3px solid white; height: 200px; text-align: center;"><table border="0" cellspacing="0" cellpadding="2" id="loginformtable"><tr id="trMessage" style="display:none"><td colspan="2" style="text-align: center;" id="tdMessage">&nbsp;</td></tr><tr><td style="text-align: right;">CID:</td><td><input type="text" name="cid" id="logincid" /></td></tr><tr><td style="text-align: right;">Password:</td><td><input type="password" name="password" id="loginpassword" /></td></tr><tr><td colspan="2" style="text-align: center"><input type="button" onclick="loginProcess()" value="Login" /></td></tr></table></td></tr>
</table></center>
<?php
} else {
?>
<script type="text/javascript">
initialDisplay();
</script>
<?php } ?>
</td></tr>
</td></tr>
</table>
</body>
</html>
<?php
?>
