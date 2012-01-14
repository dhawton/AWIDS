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

var StopFlags=new Array();
var StopSentFlags = new Array();
var FlashFlags = new Array();
var FlashCnt = new Array();
var StopSIA;

function
updateWeather()
{
	if (StopSIA == 1) { return; }
	AjaxRequest.get(
	{
		'url':'/dhtml/wxget.php',
		'onSuccess':function(req) { document.getElementById("weatherbox").innerHTML = req.responseText; var cell = document.getElementById("weatherbox"); var x = cell.getElementsByTagName("script"); for (var i = 0 ; i < x.length ; i++) { eval(x[i].text); } }
	});

	setTimeout("updateWeather()", 10000);
}

function
atisFlash(cellname)
{
	//alert("got flash! /"+FlashCnt[(cellname)]+"/"+FlashFlags[(cellname)]+"/"+StopFlags[(cellname)]);
	if (FlashCnt[(cellname)] == null) { FlashCnt[(cellname)] = 0; }
	if (FlashCnt[(cellname)] >= 20 && StopSentFlags[(cellname)] != 1) { /*alert("Force stopping " + FlashCnt[(cellname)]);*/ var iata = cellname.substr(0,3); SIAAckATIS(iata); StopSentFlags[(cellname)] = 1; }
	if (StopFlags[(cellname)] == 1) { FlashFlags[(cellname)] = FlashCnt[(cellname)] = 0; StopFlags[(cellname)] = StopSentFlags[(cellname)] = null; document.getElementById(cellname).style.color="#000000"; return; }
	if (FlashFlags[(cellname)] == 1) { document.getElementById(cellname).style.color = "#000000"; FlashFlags[(cellname)]=0; }
	else { document.getElementById(cellname).style.color = "#ff9900"; FlashFlags[(cellname)]=1; }
	FlashCnt[(cellname)] = FlashCnt[(cellname)] + 1;
	setTimeout(function(){atisFlash(cellname)}, 1000);
}

function
SIAAckATIS(iata)
{
	var cellname = iata + "ATIS";
	var ATIS = document.getElementById(cellname).innerHTML;
	AjaxRequest.get(
	{
		'url':'/dhtml/ackatis.php?facility='+iata+'&ATIS='+ATIS,
		'onSuccess':function(req) { var ind = iata+"ATIS"; StopFlags[(ind)] = 1; }
	});
}
	

function
updatePIREP()
{
        if (StopSIA == 1) { return; }

        AjaxRequest.get(
        {
                'url':'/dhtml/loadpirep.php?id=-1',
                'onSuccess':function(req) { document.getElementById("pirepbox").innerHTML = req.responseText; }
        });

        setTimeout("updatePIREP()", 30000);

}

function
updateTMU()
{
        if (StopSIA == 1) { return; }

        AjaxRequest.get(
        {
                'url':'/dhtml/loadtmu.php?id=-1',
                'onSuccess':function(req) { document.getElementById("tmubox").innerHTML = req.responseText; }
        });

        setTimeout("updateTMU()", 30000);
}

function
updateCRD()
{
}
