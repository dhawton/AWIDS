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

function
updateTMUGate(facility, gate)
{
	var statusfield = facility + gate + "status";
	var spdfield = facility + gate + "spd";
	var mitfield = facility + gate + "mit";
	document.getElementById(facility + gate + "row").style.backgroundColor = "#ffff00";
	var e = document.getElementById(statusfield);
	var stv = e.options[e.selectedIndex].value;
	if (stv == 1) { var sp = document.getElementById(spdfield).value; var m = document.getElementById(mitfield).value; }
	else { var sp = ""; var m = ""; }
	AjaxRequest.get(
	{
		'url':'/dhtml/tmu.php?f='+facility+'&g='+gate+'&st='+ stv + '&sp=' + sp + '&m=' + m,
		'onSuccess':function(req) {
			if (req.responseText == 1) {
				document.getElementById(facility + gate + "row").style.backgroundColor ="#33ff00";
			}
			else {
				document.getElementById(facility + gate + "row").style.backgroundColor = "#ff0000";
			}
			setTimeout("document.getElementById('"+facility + gate + 'row' + "').style.backgroundColor = ''", 5000);
		}
	});
}

function
checkStatus(facility, gate)
{
	var e = document.getElementById(facility + gate + "status");
	if (e.options[e.selectedIndex].value == "1") {
		document.getElementById(facility + gate + "spd").style.visibility = "visible";
		document.getElementById(facility + gate + "mit").style.visibility = "visible";
	} else {
		document.getElementById(facility + gate + "spd").style.visibility = "hidden";
		document.getElementById(facility + gate + "mit").style.visibility = "hidden";
	}
}

function
updateSpd(facility, gate)
{
        var field = facility + gate + "spd";
	if (!is_int(document.getElementById(field).value)) { alert("Speeds restrictions must be numbers only"); return false; }
        document.getElementById(field).style.backgroundColor = "#ffff00";
        AjaxRequest.get(
        {
                'url':'/dhtml/tmu.php?f='+facility+'&g='+gate+'&sp='+ document.getElementById(field).value,
                'onSuccess':function(req) {
                        if (req.responseText == 1) { document.getElementById(field).style.backgroundColor = "#33ff00"; }
                        else { document.getElementById(field).style.backgroundColor = "#ff0000"; }
                        setTimeout("document.getElementById('"+field+"').style.backgroundColor = '';", 3000);
                }
        });
}

function
updateMIT(facility, gate)
{
        var field = facility + gate + "mit";
        if (!is_int(document.getElementById(field).value)) { alert("MIT restrictions must be numbers only"); return false; }
        document.getElementById(field).style.backgroundColor = "#ffff00";
        AjaxRequest.get(
        {
                'url':'/dhtml/tmu.php?f='+facility+'&g='+gate+'&m='+ document.getElementById(field).value,
                'onSuccess':function(req) {
                        if (req.responseText == 1) { document.getElementById(field).style.backgroundColor = "#33ff00"; }
                        else { document.getElementById(field).style.backgroundColor = "#ff0000"; }
                        setTimeout("document.getElementById('"+field+"').style.backgroundColor = '';", 3000);
                }
        });
}


function
is_int(value)
{
	for (i = 0 ; i < value.length ; i++) { 
		if ((value.charAt(i) < '0') || (value.charAt(i) > '9')) return false 
	}
	return true; 
}
