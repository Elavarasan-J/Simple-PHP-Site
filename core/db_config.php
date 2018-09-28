<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class DB {
	function __construct() {
		if(ENVIRONMENT=='development') {
			defined('DB_HOST') OR define("DB_HOST",'localhost');
			defined('DB_USER') OR define("DB_USER",'root');
			defined('DB_PASS') OR define("DB_PASS",'');
			defined('DB_NAME') OR define("DB_NAME",'anncares');
		} else if(ENVIRONMENT=='testing') {
			defined('DB_HOST') OR define("DB_HOST",'mysql.gtectsystems.com');
			defined('DB_USER') OR define("DB_USER",'gtectdream');
			defined('DB_PASS') OR define("DB_PASS",'Qyl0O82ilp5f4J');
			defined('DB_NAME') OR define("DB_NAME",'gtectapprovals1');
		} else if(ENVIRONMENT=='production') {
			defined('DB_HOST') OR define("DB_HOST",'localhost');
			defined('DB_USER') OR define("DB_USER",'myrtle_usr');
			defined('DB_PASS') OR define("DB_PASS",'');
			defined('DB_NAME') OR define("DB_NAME",'anncares');
		}
		defined('TABLE_PREFIX') OR define("TABLE_PREFIX", "anncares_");

		//	################################################## table names ##################################################
			// Common
			$this->TB_state=TABLE_PREFIX.'state';
			$this->TB_admin_menu=TABLE_PREFIX.'admin_menu';
			$this->TB_pagination_settings=TABLE_PREFIX.'pagination_settings';
			$this->TB_temp=TABLE_PREFIX.'temp';
			$this->TB_track=TABLE_PREFIX."track_details";
			
			// Users
			$this->TB_department=TABLE_PREFIX.'department';
			$this->TB_security=$this->tablename_user=TABLE_PREFIX.'security';

			// Menus
			$this->TB_menus=TABLE_PREFIX.'menus';
			$this->TB_menu_items=TABLE_PREFIX.'menu_items';
			
			//	Page
			$this->TB_page=TABLE_PREFIX.'page';
			$this->TB_page_accordion=TABLE_PREFIX.'page_accordion';

			//	Custom Posts
			$this->TB_project=TABLE_PREFIX.'project';
			$this->TB_media=TABLE_PREFIX.'media';
			$this->TB_event=TABLE_PREFIX.'event';
			
			//	Post
			$this->TB_post=TABLE_PREFIX.'post';
			$this->TB_post_category=TABLE_PREFIX.'post_category';
			
			//	Slider
			$this->TB_slider=TABLE_PREFIX."slider";
			
			//	Media Library
			$this->TB_media_library=TABLE_PREFIX."media_library";
			
			//	Gallery
			$this->TB_gallery=TABLE_PREFIX."gallery";
			
			//	Executive & Advisory Board
			$this->TB_board_members=TABLE_PREFIX.'board_members';
			
			//	Media
			$this->TB_media=TABLE_PREFIX."media";
		
			//  Newsletter_Signup
			$this->TB_newsletter_signup=TABLE_PREFIX."newsletter_signup";
			
			//	Events
			$this->TB_event=TABLE_PREFIX."event";
		//	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX table names XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

		$this->connection();
	}

	function print_sql($sql) {
		echo "<div style=\"border:1px solid black;background:#FFFFDD;";
		echo "font:small 'Courier new',monospace;padding:5px;\">";
		echo "<b>SQL STATEMENT:</b><br/>$sql</div>";
	}

	function print_result($rcount,$qtm) {
		echo "<div style=\"border:1px solid black;border-top:0;background:#EEFFFF;";
		echo "font:small 'Courier new',monospace;padding:4px;margin-bottom:10px;\">";
		echo "<b>RESULT:</b><br/>".mysqli_info($this->conn)."<br/>";
		echo "<span style=\"color:blue\">$rcount</span> records affected</br>";
		echo "Query runtime: <span style=\"color:blue\">$qtm</span> seconds.</div>"; 
	}

	function connection($db_name=DB_NAME,$db_user=DB_USER,$db_pass=DB_PASS,$db_host=DB_HOST) {
		$this->conn = @mysqli_connect($db_host,$db_user,$db_pass,$db_name) or
		die("<h3 align=\"center\" style=\"color:red\">Unable to Connect to MySQL Server</h3>");
	//	@mysql_select_db($db_name,$this->conn) or 
	//	die("<h3 align=\"center\" style=\"color:red\">Unable to find the database</h3>");
		$this->rs = null;
		$this->recordcount = null;
		$this->totrecs=null;
		$this->lastquery = "";
		$this->debug_mode =false;
		$this->numRows=null;
		$this->affectedRows=null;
	}

	function query($SQL) {
		if(strlen($SQL)==0) return false;
		$SQL = preg_replace('/`(.+?)`/',TABLE_PREFIX.'$1',$SQL);
		if($this->debug_mode)
		{
			$this->print_sql($SQL);		
		 	$t1=microtime(true);
			$this->rs =@mysqli_query($this->conn,$SQL);
			$qtm = microtime(true)-$t1;
		} else
			$this->rs =@mysqli_query($this->conn,$SQL);
		
		$this->recordcount = $this->affectedRows = mysqli_affected_rows($this->conn);
		$this->numRows = @mysqli_num_rows($this->conn);
		$this->lastquery = $SQL;
		if($this->debug_mode)
			$this->print_result($this->recordcount,$qtm);
		return ($this->recordcount);
	}
	
	function page_query($SQL,$pagenum=1,$perpage=10,$totrecs=null) {
	    if($pagenum < 1) $pagenum=1;
		if($perpage < 1) $perpage=10;
		if(is_null($totrecs))
			$this->totrecs = $this->get_field("SELECT COUNT(*) ".strstr($SQL,'FROM'));
		$PSQL = "$SQL LIMIT ".(($pagenum-1)*$perpage).",$perpage";
		return($this->query($PSQL));
	}
	
	/*function getrec() {
		if(!$this->rs) return false;
		return mysqli_fetch_assoc($this->rs);
	}*/
	function getrec($rec_type='assoc') {
		if(!$this->rs) return false;
		switch($rec_type)
		{
			case 'row':
				return mysqli_fetch_row($this->rs);
			case 'array':
				return mysqli_fetch_array($this->rs);
			case 'object':			
				return mysqli_fetch_object($this->rs);
			default:
				return mysqli_fetch_assoc($this->rs);
		}
	}

	function begin_transaction() {
			return(@mysqli_query($this->conn,"BEGIN"));	
	}
	
	function commit_transaction() {
			return(@mysqli_query($this->conn,"COMMIT"));	
	}
	
	function rollback_transaction() {
			return(@mysqli_query($this->conn,"ROLLBACK"));	
	}	
	
	function get_insert_sql($flds,$tblname) {
	   $SQL1 = "INSERT INTO $tblname(";
	   $SQL2 = " VALUES(";
	
	   foreach($flds as $fld=>$val)
	   {
			$SQL1 .= $fld.",";
			$SQL2 .= $this->safestr($val).",";
	   }
	  	  
	   $SQL1[strlen($SQL1)-1]=")";
	   $SQL2[strlen($SQL2)-1]=")";
	   return $SQL1.$SQL2;	
	}
		
	function insert(&$flds,$tblname) {
	   $SQL1 = "INSERT INTO $tblname(";
	   $SQL2 = " VALUES(";
	
	   foreach($flds as $fld=>$val)
	   {
	   		$striptags = !preg_match("/^<\w*>$/",$fld);
			if($striptags) $SQL1 .= $fld.",";
			else $SQL1 .= substr($fld,1,-1).",";
			$SQL2 .= $this->safestr($val,$striptags).",";
	   }
	  	  
	   $SQL1[strlen($SQL1)-1]=")";
	   $SQL2[strlen($SQL2)-1]=")";
	   $this->debug_mode=false;
	   //echo $SQL1.$SQL2; exit;
	   return $this->query($SQL1.$SQL2);	
	}
	function insert_get_id($flds,$tblname) {
		$SQL = $this->get_insert_sql($flds,$tblname);
	//	$this->debug_mode=true;$this->query($SQL);exit;
		if($this->query($SQL))
		{
			//exit;
			return(mysqli_insert_id($this->conn));
		}	
		return false;	
	}
	
	function insert_multiple($strfldnames,$valrows,$tblname) {
	   if(count($valrows)==0) return false;
	   $SQL1 = "INSERT INTO $tblname($strfldnames) VALUES ";
	   $SQL2="";
	   foreach($valrows as $vals)
		 $SQL2 .= "(".implode(",",array_map(array($this,"safestr"),$vals))."),";
	   $SQL2 = substr($SQL2,0,-1);
	   return $this->query($SQL1.$SQL2);
	}	
	
	function update($flds,$WHERE,$tblname) {
	//$db->debug_mode=true;
		$SQL = "UPDATE $tblname SET ";
		
		foreach($flds as $fld=>$val)
		{
	   		$striptags = !preg_match("/^<\w*>$/",$fld);
			if($striptags) $SQL .= "$fld=";
			else $SQL .= substr($fld,1,-1)."=";
			$SQL .= $this->safestr($val,$striptags).",";			
		}
		
		$SQL = substr($SQL,0,-1);
		$SQL .= " WHERE ".$WHERE;
		//echo $SQL; exit;
		return  $this->query($SQL);

	}
	
	function main_update($flds,$WHERE,$tblname) {
		$SQL = "UPDATE $tblname SET ";
		
		$SQL .= $flds;
		$SQL .= " WHERE ".$WHERE;
		//echo $SQL; exit;
		return $this->query($SQL);
	}
	function &get_col_array($sql,$keycol=0,$valcol=null,$pagenum=0,$perpage=0) {
		$arr = Array();
	    
		/*if(is_null($perpage))
		     $this->query($sql); 
		else
			$this->page_query($sql,$pagenum,$perpage);	*/
			
		if($perpage==0)
		   $this->query($sql);	
		else
		  $this->page_query($sql,$pagenum,$perpage);		 
		if($this->recordcount <= 0) return $arr;
		
		if(is_null($valcol)) //is_null because $valcol can also be 0
		{
			if(mysqli_field_type($this->rs,$keycol) == 'int')
				while(($r=mysqli_fetch_row($this->rs)))
					$arr[] = intval($r[$keycol]);
			else
				while(($r=mysqli_fetch_row($this->rs)))
					$arr[] = $r[$keycol];
		}
		else
		{
			if(mysqli_field_type($this->rs,$keycol) == 'int')
				while(($r=mysqli_fetch_row($this->rs)))
					 $arr[$r[$valcol]] = intval($r[$keycol]);
			else
			{
				while(($r=mysqli_fetch_row($this->rs)))
					$arr[$r[$valcol]] = $r[$keycol];
			}		
		}
		
		return $arr;	
	}
	
	function print_records() {
	   if($this->rs && $this->recordcount>0)
	   {	
	   		$fc = mysqli_num_fields($this->rs);
			echo "<table style=\"border:1px solid black;font:11px verdana,arial;\" cellpadding=\"5\" cellspacing=\"1\">\n";
			echo "<caption style=\"padding:2px 5px;text-align:left;background:#eee;font-size:13px;font-weight:bold;\">{$this->lastquery}</caption><tr style=\"background:black;color:white;\">";
			for($i=0;$i<$fc;$i++) echo "<th>".mysqli_field_name($this->rs, $i)."</th>";
	   		echo "</tr>\n";		
 			for($j=0;$j < $this->recordcount; $j++)
			{
				$r = mysqli_fetch_row($this->rs);
				echo "<tr style=\"background:#".(($j % 2)? "EEEEFF":"EEFFFF").";\">";
				for($i=0;$i<$fc;$i++) echo "<td>{$r[$i]}</td>";	echo "</tr>\n";
			}
			echo "</table>";
	   }
	   else echo "[No records!]";
	}

	function get_field($SQL,$col=0,$row=0) {
		$this->query($SQL);
		if($this->recordcount == 0) return false;
		for($r = 0;($flds = @mysqli_fetch_row($this->rs));$r++)
			if(($r == $row) && isset($flds[$col]))
				switch(mysqli_field_type($this->rs,$col))
				{	
					case 'int':
						return intval($flds[$col]);
						
					case 'real':
						return floatval($flds[$col]);
						
					default:
						return $flds[$col];
				}
	
		return false;
	}
	
    function safestr($val,$striptags=true) {
		if(is_null($val)) return "NULL";
		if(is_string($val))
		{
		   if($striptags) $val=strip_tags($val);
		   $val = addslashes($val);		
		   $val = str_replace('\\\\','\\',str_replace('\\\"','\"',str_replace("\\\\\\'","\\'",$val)));
		   $val = "'$val'";
		}
		return $val;
	}
	function safestr_noqt($val,$striptags=true) {
		if(is_null($val)) return "NULL";
		if(is_string($val))
		{
			if($striptags) $val=strip_tags($val);
			$val = addslashes($val);		
			$val = str_replace('\\\\','\\',str_replace('\\\"','\"',str_replace("\\\\\\'","\\'",$val)));
		//	$val = "'$val'";
		}
		return $val;
	}
	/*function delete($Where,$tablename){
		 $SQL=('delete from '.$tablename.' where '.$Where);
		 return  $this->query($SQL);
	}*/

	function delSingleRec($tablename,$where='')	{
		if($where!='')
			$where="where $where";
		
		$query="DELETE  FROM $tablename $where LIMIT 1";
		$this->query($query);
		//$rec= mysqli_affected_rows();
		return ($this->rs)?1:0;
	}

	function getSingleRec($tablename,$where='',$arg='',$printquery='')	{
		$con=(isset($arg) && $arg!='')?$arg:'*';
		if($where!='')
			$where="where $where";
		$query="select $con from $tablename $where LIMIT 1";
		if($printquery==1)
			echo $query;
		$this->query($query);
		if($this->recordcount>0)	{
			return $this->getrec();
		} else
			return false;
	}

	function getMultipleRec($query='',$printquery='') {
		$arr=array();
		if($printquery==1)
			echo $query;
		$i=0;
		$this->query($query);
		if($this->recordcount>0)
			while($fetch=$this->getrec()) {
				$arr[$i++]=$fetch;			
			}
		return $arr;
	}

	function getMultipleRecwithField($query='',$index='',$value='',$printquery='')	{
		$arr=array();
		if($printquery==1)
			echo $query;
		$i=0;
		$this->query($query);
		if($this->recordcount>0)	{
			while($fetch=$this->getrec())	{
				if($index!='')
				$arr[$fetch[$index]].=$fetch[$value];			
			}
			return $arr;
		} else
			return false;
	}

	function getNumRecords($query)	{
		$this->query($query);
		return $this->numRows;
	}
	
	//	get auto increment id (
	function getAutoincrement($table){
		$max_id=1;
		$select_maxid=mysqli_query($this->conn,"SHOW TABLE STATUS LIKE '$table'");
		$fetch=mysqli_fetch_array($select_maxid);
		if(isset($fetch['Auto_increment']) && $fetch['Auto_increment']!='')
			$max_id=$fetch['Auto_increment'];
		return $max_id;
	}
	//	) get auto increment id
}
$db = new DB;
?>