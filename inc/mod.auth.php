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

function
authenticate($username, $password, $encrypted = false)
{
	global $roster,$CONF;

	$smf = $CONF['db']['smf_members'];
	$rst = $CONF['db']['roster'];

	// If it is already encrypted, don't re-hash.. otherwise, hash it.
	if ($encrypted == false)
	{
		$hash_pass = sha1($username . $password);
	} else {
		$hash_pass = $password;
	}

	$roster->db_build($db);
	$roster->db_fetchone($db,$row,"`$smf`.`passwd`,`$rst`.`status`","`$smf`,`$rst`","`$smf`.`member_name`='$username' AND `$rst`.`cid`=`$smf`.`member_name`");

	if ($row['passwd'] == $hash_pass && $row['status'] != 4)
	{
		return true;
	} else {
		return false;
	}
}

function
login($username)
{
	global $roster, $CONF;

	$rst = $CONF['db']['roster'];

	$roster->db_build($db);
	$roster->db_fetchone($db,$row,"`fname`,`lname`","`$rst`","`$rst`.`cid`='$username'");

	$_SESSION['loggedin'] = 1;
	$_SESSION['fname'] = $row['fname'];
	$_SESSION['lname'] = $row['lname'];
	$_SESSION['username'] = $username;
}
?>
