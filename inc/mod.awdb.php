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

class AWDB
{
	private $__database_u = "";
	private $__database_p = "";
	private $__database_d = "";
	private $__database_s = "";
	public  $__version = "0.22";

	public function
	__construct($server = null,$user = null,$pass = null,$db = null)
	{
		global $_CONF;

		$this->__database_u = $user;;
		$this->__database_p = $pass;
		$this->__database_d = $db;
		$this->__database_s = $server;
	}

	public function set_db_user($user) { $this->__database_u = $user; }
	public function set_db_pass($pass) { $this->__database_p = $pass; }
	public function set_db_db($db) { $this->__database_d = $db; }
	public function set_db_serv($serv) { $this->__database_s = $serv; }

        public function
        db_build(&$db)
        {
                $db = mysql_connect($this->__database_s, $this->__database_u, $this->__database_p);
                mysql_select_db($this->__database_d);

                if ($db) { return 1; }
                return 0;
        }

        public function
        db_done(&$db)
        {
                mysql_close($db);

                if (!isset($db)) { return 1; }
                return 0;
        }

        public function
        db_execute(&$db, $query)
        {
                mysql_query($query, $db);
		if (mysql_error()) { die($query . "/" . mysql_error()); }
                return mysql_affected_rows($db);
        }

        public function
        db_fetchone(&$db, &$row, $cols, $table, $filter)
        {
                $query = "SELECT $cols FROM $table";
                if (isset($filter)) { $query .= " WHERE $filter"; }
                $query .= " LIMIT 1";

                if (!isset($db)) { $this->db_build($db); }

                if ($db == 0) { return 0; }

                if ($this->db_query($db, $res, $query)) {
                        $row = mysql_fetch_assoc($res);
                } else { return 0; }

                return 1;
        }

        public function
        db_query(&$db, &$res, $query)
        {
                $res = mysql_query($query, $db) or die("Failed : (($query))" . mysql_error());

                if ($res && @mysql_num_rows($res) > 0) { return 1; }
                return 0;
        }

        public function
        db_safe($str)
        {
                $search=array("\\","\0","\n","\r","\x1a","'",'"');
                $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
                return str_replace($search,$replace,$str);
        }
}
?>
