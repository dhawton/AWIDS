<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- BEGIN HEADER -->
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
</head>
<body onload="update_clock()">
<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; height: 100%;">
<tr><td style="background-color:#5c5c5c;">
  <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
    <tr><td rowspan="2"><img src="/images/logo.jpg" alt="ZJX IDS"></td><td style="width: 100%;"><img src="/images/atisplus.jpg" /><img src="/images/atisx.jpg"><img src="/images/pirep.jpg"><img src="/images/exit.jpg"></td><td rowspan="2" class="clock"><input type="text" name="theclockbox" id="theclockbox" style="width: 160px; font-size: 24pt; text-align: center; border: 0px solid white; background-color: #000; color: #990000;" /></td></tr>
    <tr><td>SIA | COMMS | WEATHER | RELIEF | CHARTS | REFERENCES | TMU</td></tr>
  </table>
</td></tr>
<tr style="height: 100%;"><td style="background-color: #00163a; color: #fff; height: 100%;" valign="top"><br>
<!-- END HEADER -->
