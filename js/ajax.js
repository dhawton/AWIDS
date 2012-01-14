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

var curPage = "SIA";

function ajaxRequestBuild()
{
	var ajaxRequest;

	if (window.XMLHttpRequest)
	{
		ajaxRequest = new XMLHttpRequest();
	}
	else
	{
		ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
	}

	return ajaxRequest;
}

function
loginProcess()
{
	var ajaxRequest = ajaxRequestBuild();

	document.getElementById("trMessage").style.display="";
	document.getElementById("tdMessage").innerHTML='<img src="/images/wait.gif"> Attempting login...';

	ajaxRequest.onreadystatechange=function()
	{
		if (ajaxRequest.readyState == 4 && ajaxRequest.status==200)
		{
			if (ajaxRequest.responseText == "-1")
			{
				document.getElementById("tdMessage").innerHTML="<font color='#ff0000'>Login failed.</font>";
			} else {
				document.getElementById("cell_login").innerHTML = ajaxRequest.responseText;
			}
		}
	}

	ajaxRequest.open("POST","/dhtml/login.php?cid="+document.getElementById('logincid').value+"&password="+document.getElementById('loginpassword').value, true);
	ajaxRequest.send();
}

function
processFacility()
{
	var ajaxRequest = ajaxRequestBuild();

	var facility = document.getElementById("facility").value;

	document.getElementById("cell_login").innerHTML = '<img src="/images/wait.gif"> Loading the IDS';

	ajaxRequest.onreadystatechange=function()
	{
		if (ajaxRequest.readyState == 4 && ajaxRequest.status==200)
		{
			initialDisplay();
		}
	}

	ajaxRequest.open("POST","/dhtml/facility.php?facility="+facility, true);
	ajaxRequest.send();
}

function
initialDisplay()
{
	AjaxRequest.get(
	{
		'url':'/dhtml/loadrow.php?bar=1',
		'onSuccess':function(req) { document.getElementById("iconrow1").innerHTML = req.responseText; }
	});

	AjaxRequest.get(
	{
		'url':'/dhtml/loadrow.php?bar=2',
		'onSuccess':function(req) { document.getElementById("iconrow2").innerHTML = req.responseText; document.getElementById("btnbarSIA").className="opened"; }
	});

	AjaxRequest.get(
	{
		'url':'/dhtml/loadpage.php?p=SIA',
		'onSuccess':function(req) { document.getElementById("maintd").innerHTML = req.responseText; document.getElementById("maintd").style.verticalAlign="top"; var cell = document.getElementById("maintd"); var x = cell.getElementsByTagName("script"); for (var i = 0 ; i < x.length ; i++) { eval(x[i].text); } }
	});
}

function
drawPIREP()
{
	drawCRDLoading();
	AjaxRequest.get(
	{
		'url':'/dhtml/loadpirep.php',
		'onSuccess':function(req) { document.getElementById("crdbox").innerHTML = req.responseText;
			var cell = document.getElementById("crdbox");
                        var x = cell.getElementsByTagName("script");
                        for (var i = 0 ; i < x.length ; i++) { eval(x[i].text); }
 }
	});
}

function
drawATIS()
{
	drawCRDLoading();
	AjaxRequest.get(
	{
		'url':'/dhtml/loadatis.php',
		'onSuccess':function(req) { document.getElementById("crdbox").innerHTML = req.responseText; }
	});
}

function
processATIS()
{
	var field = document.getElementById("atisfield").value;
	var arrival = document.getElementById("atisarrival").value;
	var departure = document.getElementById("atisdeparture").value;
	var approach = document.getElementById("atisapproach").value;
	var atis = document.getElementById("atisatis").value;
	drawCRDLoading();
	AjaxRequest.get(
	{
		'url':'/dhtml/loadatis.php?field='+field+'&arrival='+arrival+'&departure='+departure+'&approach='+approach+'&atis='+atis,
		'onSuccess':function(req) {
			if (req.responseText == "1") {
				document.getElementById("crdbox").innerHTML = "Done";
			} else {
				document.getElementById("crdbox").innerHTML = "Error occured."; document.getElementById("crdbox").style.color="#ff0000";
			}
		}
	});
}

function
drawATISx()
{
	drawCRDLoading();
	AjaxRequest.get(
	{
		'url':'/dhtml/loadatisx.php',
		'onSuccess':function(req) { document.getElementById("crdbox").innerHTML = req.responseText; }
	});
}

function
processATISx()
{
	var field = document.getElementById("atisfield").value;
	drawCRDLoading();
	AjaxRequest.get(
	{
		'url':'/dhtml/loadatisx.php?field='+field,
		'onSuccess':function(req) {
			if (req.responseText == "1") { document.getElementById("crdbox").innerHTML = "Done"; }
			else { document.getElementById("crdbox").innerHTML = '<font color="#ff0000">An error occured.</font>'; }
		}
		
	});
}

function
drawCRDLoading()
{
	document.getElementById("crdbox").style.color="#ffffff";
	document.getElementById("crdbox").innerHTML = "<img src='/images/wait.gif'> Processing request...";
}

function
drawPage(page)
{
	if (page == "SIA") { StopSIA = 0; }
	if (page != "SIA") { StopSIA = 1; }

	document.getElementById("maintd").innerHTML = "<img src='/images/wait.gif'> Processing request...";
	document.getElementById("maintd").style.verticalAlign = "middle";
	document.getElementById("maintd").style.textAlign = "center";
	document.getElementById("btnbar" + curPage).className = "pendingclose";
	document.getElementById("btnbar" + page).className = "pendingopen";
	AjaxRequest.get(
	{
		'url':'/dhtml/loadpage.php?p='+page,
		'onSuccess':function(req) {
			document.getElementById("maintd").innerHTML = req.responseText;
			document.getElementById("maintd").style.verticalAlign = "top";
			document.getElementById("maintd").style.textAlign="left";
			document.getElementById("btnbar" + page).className = "opened";
			document.getElementById("btnbar" + curPage).className = "closed";
			curPage = page;
			var cell = document.getElementById("maintd");
			var x = cell.getElementsByTagName("script");
			for (var i = 0 ; i < x.length ; i++) { eval(x[i].text); }
		}
	});
}

function
loadPDF(link)
{
	StopSIA = 1;

	document.getElementById("maintd").innerHTML = "<img src='/images/wait.gif'> Processing request...";
	document.getElementById("maintd").style.verticalAlign = "middle";
	document.getElementById("maintd").style.textAlign = "center";
        AjaxRequest.get(
        {
                'url':'/dhtml/loadpdf.php?p='+link,
                'onSuccess':function(req) {
                        document.getElementById("maintd").innerHTML = req.responseText;
                        document.getElementById("maintd").style.verticalAlign = "top";
                        document.getElementById("maintd").style.textAlign="left";
                }
        });
	return false;
}

function
getATISData()
{
	document.getElementById("atisWaiting").innerHTML = "<img src='/images/wait.gif'> Processing request...";
	document.getElementById("atisWaiting").style.display = "block";
	document.getElementById("atisWaiting").style.textAlign = "center";

	var In = document.getElementById("atisfield").selectedIndex;
	var Apt = document.getElementById("atisfield").options[In].value;

	AjaxRequest.get(
	{
		'url':'/dhtml/getatisinfo.php?a='+Apt,
		'onSuccess':function(req) {
			document.getElementById("atisWaiting").style.display="none";
			var dta = req.responseText;
			var dtasp = dta.split("%");
			if (dtasp[0] != "-1") { document.getElementById("atisarrival").value = dtasp[0]; }
			if (dtasp[1] != "-1") {	document.getElementById("atisdeparture").value = dtasp[1]; }
			if (dtasp[2] != "-1") {
				for (var i = 0 ; i < document.getElementById("atisapproach").options.length ; i++)
				{
					if (document.getElementById("atisapproach").options[i].value == dtasp[2]) { document.getElementById("atisapproach").options[i].selected = true; }
				}
			}
			if (dtasp[3] != "-1") {
				for (var z = 0 ; z < document.getElementById("atisatis").options.length ; z++)
				{
					if (document.getElementById("atisatis").options[z].value == dtasp[3]) {
						document.getElementById("atisatis").options[z].selected = true;
					}
				}
			}
		},
		'onError':function(req) { alert('Error!!\nStatusText='+req.statusText+'\nContents='+req.responseText); }
	});
}

function
submitPIREP()
{
	var report = document.getElementById("report_type").value;
	var callsign = document.getElementById("callsign").value;
	var navaid = document.getElementById("navaid").value;
	var bearing = document.getElementById("bearing").value;
	var dme = document.getElementById("dme").value;
	var actype = document.getElementById("ac_type").value;
	var fl = document.getElementById("fl").value;
	var sk = document.getElementById("sk").value;
	var wx = document.getElementById("wx").value;
	var ta = document.getElementById("ta").value;
	var wv_dir = document.getElementById("wv_dir").value;
	var wv_vel = document.getElementById("wv_vel").value;
	var tb = document.getElementById("tb").value;
	var ic = document.getElementById("ic").value;
	var rm = document.getElementById("rm").value;

	drawCRDLoading();

	AjaxRequest.get(
	{
		'url':'/dhtml/savepirep.php?rp='+report+'&cs='+callsign+'&nv='+navaid+'&br='+bearing+'&dm='+dme+'&ac='+actype+'&fl='+fl+'&sk='+sk+'&wx='+wx+'&ta='+ta+'&wvd='+wv_dir+'&wvv='+wv_vel+'&tb='+tb+'&ic='+ic+'&rm='+rm,
		'onSuccess':function(req) {
			document.getElementById("crdbox").innerHTML = "Submitted.";
		}
	});
}
