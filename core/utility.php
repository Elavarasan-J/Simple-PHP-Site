<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Utility {
	function __construct() {
		$this->db = new DB;
		$this->pathObj = new Path;
		$this->trackInfoObj = new TrackInfo;

		defined("SITE_NAME") OR define("SITE_NAME","Ann Cares");
		defined("SITE_NAME_MEDIUM") OR define("SITE_NAME_MEDIUM","Ann Cares");
		defined("SITE_NAME_FULL") OR define("SITE_NAME_FULL","Ann Cares Foundation");
		
		$this->status_array=array('In-Active','Active');
		$this->event_status_array=array('Upcoming','Current','Completed');
		$this->yesno_array=array('No','Yes');
		$this->target_array=array(1=>"_self",2=>"_blank",3=>"_new",4=>"_parent",5=>"_top");
		$this->salutation_arr=array("mr"=>"Mr","mrs"=>"Mrs","miss"=>"Miss");
		$this->boardType_arr=array(1=>'Executive Board', 2=>'Advisory Board');

		//	status array (
		$this->status_arr=array(
			"1"=>"Active",
			"2"=>"In-Active"
		);
		//	) status array

		//	Yes No array (
		$this->yesno_arr=array(
			"1"=>"Yes",
			"2"=>"No"
		);
		//	) status array

		//	card type array (
		$this->card_type_arr=array(
			"visa"=>"Visa",
			"mastercard"=>"Mastercard",
			"discover"=>"Discover",
			"amex"=>"Amex"
		);
		//	) card type array

		//	months array (
		$this->months_arr=array(
			"01"=>"Jan",
			"02"=>"Feb",
			"03"=>"Mar",
			"04"=>"Apr",
			"05"=>"May",
			"06"=>"Jun",
			"07"=>"Jul",
			"08"=>"Aug",
			"09"=>"Sep",
			"10"=>"Oct",
			"11"=>"Nov",
			"12"=>"Dec"
		);
		//	) months array

		//	years arr (
		$start_year=date("Y");
		$this->years_arr=array();
		for($i=$start_year;$i<=$start_year+10;$i++) {
			$this->years_arr[$i]=$i;
		}
		//	)
		
		$this->title_colors=array(
			0=>"light-red",
			1=>"light-green",
			2=>"light-blue",
			3=>"orange"
		);
	}
	
	function title_color() {
		$rand=rand(0,(count($this->title_colors)-1));
		echo $this->title_colors[$rand];
	}
	function get_title_color() {
		$rand=rand(0,(count($this->title_colors)-1));
		return $this->title_colors[$rand];
	}

	function getArray2List($arr,$default=0,$start=0) {
		$default=(is_array($default))?$default:explode(',',$default);
		$start=(int)$start;
		$opt='';
		if(is_array($arr) && count($arr)>0) {
			foreach($arr as $key=>$value) {
				$key=($start>0)?($start++):$key;
				$opt.='<option value="'.$key.'"';
				if(in_array($key,$default)) $opt.='selected="selected"';
					$opt.='>'.$value.'</option>';
			}
		}
		return $opt;
	}
	
	//	even class (
	function evenClass(){
		static $i=0;
		$i++;
		$class=($i%2==0)?(' even'):('');
		return $class;
	}
	//	) even class

	//	array implode (
	function array_implode_notnull($arr=array(),$con='AND')	{
		$join=''; $arrays=array();
		if(count($arr)>0)
			foreach($arr as $val)
				if(trim($val)!='')	{
					$arrays[].=$val;
					$join=implode(' '.$con.' ',$arrays);
				}
		return $join;
	}
	//	) array implode

	########## Print ##########
	private function returnVar($var) {
		return $var;
	}
	private function returnR($arr) {
		return '<pre>'.print_r($arr,true).'</pre>';
	}
	function returnAny($val) {
		if(is_array($val) && count($val)>0) {
			return $this->returnR($val);
		} else if($val!='') {
			return $this->returnVar($val);
		}
	}
	function printAny($val) {
		echo $this->returnAny($val);
	}
	########## /Print ##########

	########## Actions ##########
	function trashRec($key, $id, $mainTable) {
		$WHERE="$key=$id";
		$msg='fail';
		$rec=$this->db->getSingleRec($mainTable,$WHERE,$key);
		if($rec==true && is_array($rec)) {
			$arr['trashed']=1;
			$msg=($this->db->update($arr,$WHERE,$mainTable))?'success':$msg;
			($msg=='success')?$this->trackInfoObj->trashTrack($id, $mainTable):'';
		}
		return $msg;
	}
	function restoreRec($key, $id, $mainTable) {
		$WHERE="$key=$id";
		$msg='fail';
		$rec=$this->db->getSingleRec($mainTable,$WHERE,$key);
		if($rec==true && is_array($rec)) {
			$arr['trashed']=0;
			$msg=($this->db->update($arr,$WHERE,$mainTable))?'success':$msg;
			($msg=='success')?$this->trackInfoObj->restoreTrack($id, $mainTable):'';
		}
		return $msg;
	}
	########## /Actions ##########

	########## String Handling ##########
	function char_limit($str='',$limit=20) {
		if($str!='' && strlen($str)>$limit) {
			$str=trim(substr($str,0,$limit)).'&hellip;';
		}
		return $str;
	}
	########## /String Handling ##########

	########## Check record by value/where ##########
	function checkVal($field, $val, $table) {
		$this->db->query("SELECT $field FROM $table WHERE $field=$val");
		return ($this->db->numRows>0)?true:false;
	}
	function checkWhereDynamic($tablename,$where)	{
		$this->db->query("SELECT * FROM $tablename WHERE $where");
		return ($this->db->numRows>0)?true:false;
	}
	########## /Check record by value/where ##########

	function get_referrer() {
		$referrence['HTTP_HOST']='HTTP_HOST:'.$_SERVER['HTTP_HOST'];
		$referrence['HTTP_USER_AGENT']='HTTP_USER_AGENT:'.$_SERVER['HTTP_USER_AGENT'];
		$referrence['REMOTE_PORT']='REMOTE_PORT:'.$_SERVER['REMOTE_PORT'];
		$referrence['QUERY_STRING']='QUERY_STRING:'.$_SERVER['QUERY_STRING'];
		$referrence['REQUEST_URI']='REQUEST_URI:'.$_SERVER['REQUEST_URI'];
		$referrer=addslashes(implode(',',$referrence));
		return $referrer;
	}

	function trims($str) {
		return (trim($str)!='')?preg_replace('/\s+/', ' ',trim($str)):'';
	}

	########## Templates ##########
	function getTemplates() {
		$files = preg_grep('~^page-.*\.(php)$~', scandir(BASE_PATH));
		$templates = array();
		$i=0;
		foreach($files as $key=>$file) {
			$tokens = token_get_all(file_get_contents(BASE_PATH.'\\'.$file));
			$comments = array();
			foreach($tokens as $token) {
				if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
					$template_name_arr=explode("#Template Name:",$token[1]);
					if($template_name_arr[1]!='') {
						$templates[$i]['file'] = $file;
						$templates[$i]['template'] = trim($template_name_arr[1]);
						$i++;
					}
				}
			}
		}
		return $templates;
	}
	function getTemplatesForOpts() {
		$files = preg_grep('~^page-.*\.(php)$~', scandir(BASE_PATH));
		$templates = array();
		$i=0;
		foreach($files as $key=>$file) {
			$tokens = token_get_all(file_get_contents(BASE_PATH.$file));
			$comments = array();
			foreach($tokens as $token) {
				if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
					$template_name_arr=explode("#Template Name:",$token[1]);
					if(isset($template_name_arr[1]) && $template_name_arr[1]!='') {
						$templates[$file] = trim($template_name_arr[1]);
						$i++;
					}
				}
			}
		}
		return $templates;
	}
	########## /Templates ##########

	function getPageType() {
		if($this->pathObj->filename=='index.php') {
			return 'home';
		} else {
			$page_path=str_replace(SITE_PATH,'',$this->pathObj->url);
			$page_path_arr=explode('/',$page_path);
			return $page_path_arr[0];
		}
	}

	########## Header Location ##########
	function headerLocation($path) {
		header("location:".$path); exit;
	}
	########## /Header Location ##########

	########## Delete File ##########
	function unlinkfile($foldername,$filename) {
		// IF another file upload,then delete the old file
		if(is_file($foldername.$filename) && $filename!="") {
			$file=$foldername.$filename;
			unlink($file);
			return true;
		}
		return false;
	}
	########## /Delete FIle ##########

	########## Excerpt ##########
	function charExcerpt($str,$length) {
		$excerpt = substr($str,0,$length);
		if(strlen($str)>$length)
			$excerpt .= '...';
		return $excerpt;
	}
	function wordExcerpt($str,$length) {
		if (str_word_count($str, 0) > $length) {
			$words = str_word_count($str, 2);
			$pos = array_keys($words);
			$str = substr($str, 0, $pos[$length]) . '...';
		}
		return $str;
	}
	########## /Excerpt ##########

	########## Slider ##########
	function get_slider() {
		$img_path=SITE_PATH.$this->pathObj->media_library_files_path;
		$img_path_medium=SITE_PATH.$this->pathObj->media_library_mediumphotos_path;
		$img_path_thumb=SITE_PATH.$this->pathObj->media_library_thumbphotos_path;

		$slider_arr=$this->db->getMultipleRec("SELECT *, concat('$img_path', featured_img) as featured_img, concat('$img_path_medium', featured_img) as featured_medium, concat('$img_path_thumb', featured_img) as featured_thumb FROM ".$this->db->TB_slider." WHERE slider_img!='' AND status=1 ORDER BY sort_order ASC");
		if(is_array($slider_arr) && count($slider_arr)>0) {
			return $slider_arr;
		} else {
			return false;
		}
	}
	########## /Slider ##########
	
	########## Post ##########
	function get_post($limit=0,$cat_id='') {
		$limit = ($limit>0)? (' LIMIT '.$limit):'';
		$cat_id = ($cat_id>0)? ('a.post_category_id='.$cat_id.' AND'):'';
		$img_path=SITE_PATH.$this->pathObj->media_library_files_path;
		$img_path_medium=SITE_PATH.$this->pathObj->media_library_mediumphotos_path;
		$img_path_thumb=SITE_PATH.$this->pathObj->media_library_thumbphotos_path;

		$post_arr=$this->db->getMultipleRec("SELECT a.*,(SELECT slug FROM ".$this->db->TB_post_category." WHERE post_category_id=a.post_category_id) as cat_slug, concat('$img_path', a.featured_img) as featured_img, concat('$img_path_medium', a.featured_img) as featured_medium, concat('$img_path_thumb', a.featured_img) as featured_thumb FROM ".$this->db->TB_post." a WHERE $cat_id a.status=1 ORDER BY a.published_time DESC".$limit);
		if(is_array($post_arr) && count($post_arr)>0) {
			return $post_arr;
		} else {
			return false;
		}
	}
	########## /Post ##########
	
	########## Post Category ##########
	function get_post_category() {
		$post_cat_arr=$this->db->getMultipleRec("SELECT * FROM ".$this->db->TB_post_category." WHERE status=1 ORDER BY post_category_id");
		if(is_array($post_cat_arr) && count($post_cat_arr)>0) {
			return $post_cat_arr;
		} else {
			return false;
		}
	}
	########## /Post Category ##########
	 
	

	########## Get Options ##########
	// Get options for all tables
	function getOptions($sql='',$text=0,$value=0,$sel=NULL,$all=1,$default='All Websites'){
		$query=mysqli_query($this->db->conn, $sql);

		$select=(!$all)?'<option value="0">'.$default.'</option>':'';
		
		if(@mysqli_num_rows($query)>0)
		while($r=mysqli_fetch_row($query)){
			$selected=(strtolower(trim($sel))==strtolower(trim($r[$value])))?('selected="selected"'):('');
			
			$select.='<option value="'.$r[$value].'" '.$selected.' >'.html_entity_decode(trim($r[$text])).'</option>';
		}
		echo  $select;
	}
	// Return options for all tables
	function returnOptions($sql='',$text=0,$value=0,$sel=NULL,$all=1,$default='All Websites'){
		$query=mysqli_query($this->db->conn, $sql);

		$select=(!$all)?'<option value="0">'.$default.'</option>':'';
		
		if(@mysqli_num_rows($query)>0)
		while($r=mysqli_fetch_row($query)){
			$selected=(strtolower(trim($sel))==strtolower(trim($r[$value])))?('selected="selected"'):('');
			
			$select.='<option value="'.$r[$value].'" '.$selected.' >'.html_entity_decode(trim($r[$text])).'</option>';
		}
		return $select;
	}
	// Get options for all tables
	function getOptionsList($sql='',$text=0,$value=0,$sel=NULL,$all=1,$default='All Records'){
		$query=mysqli_query($this->db->conn, $sql);
		$select='';
		$sel=(is_array($sel))?$sel:explode(',',$sel);

		$select=(!$all)?'<option value="0">'.$default.'</option>':'';
		if(@mysqli_num_rows($query)>0)
		while($r=mysqli_fetch_row($query)){
			$selected=(in_array($r[$value],$sel))?('selected="selected"'):('');
			$select.='<option value="'.$r[$value].'" '.$selected.' >'.html_entity_decode(trim($r[$text])).'</option>';
		}
		echo  $select;
	}

	//	select options (
	function setOptionsDB($res_arr=array(),$value,$text=array(),$sel=array(),$prefix=array(),$suffix=array(),$default=false,$default_text='') {
		$text=(!is_array($text))?explode(',',$text):$text;
		$sel=(!is_array($sel))?explode(',',$sel):$sel;
		$options_list='';
		if(is_array($res_arr) && count($res_arr)>0) {
			foreach($res_arr as $key=>$val) {
				$option_text='';
				if(is_array($text) && count($text)>0) {
					foreach($text as $tkey=>$tval) {
						$option_text.=($option_text!='')?', ':'';
						$option_text.=(($prefix[$tkey]!='')?$prefix[$tkey]:'').trim($val[$tval]).(($suffix[$tkey]!='')?$suffix[$tkey]:'');
					}
				}
				$options_list.="<option value='".$val[$value]."'".((in_array($val[$value],$sel))?' selected="selected"':'').">$option_text</option>";
			}
		}
		$default_option=($default==true)?"<option value=''>$default_text</option>":"";
		return $default_option.$options_list;
	}
	function getOptionsDB($table,$value,$text=array(),$sel=array(),$prefix=array(),$suffix=array(),$default=false,$default_text='',$where='',$fields="*") {
		global $db2;
		$res_arr = $db2->select($table,$where,$fields);
		return setOptionsDB($res_arr,$value,$text,$sel,$prefix,$suffix,$default,$default_text);
	}
	function getOptionsArr($arr,$sel=array(),$default=false,$default_text='') {
		$sel=(!is_array($sel))?explode(',',$sel):$sel;
		$options_list='';
		if(is_array($arr) && count($arr)>0) {
			foreach($arr as $key=>$val) {
				$options_list.="<option value='".$key."'".((in_array($key,$sel))?' selected="selected"':'').">$val</option>";
			}
		}
		$default_option=($default==true)?"<option value=''>$default_text</option>":"";
		return $default_option.$options_list;
	}
	//	) select options
	########## /Get Options ##########

	########## Is Empty Fields ##########
	function isEmptyfield($str) {
		$returnstring='';
		if(count($str)>0)
			foreach($str as $fieldname=>$fieldvalue)
				if(trim($fieldvalue)==NULL)
					$returnstring.='<strong>&bull; '.$fieldname.':</strong> You must enter a valid '.$fieldname.'. <br/>';
				else
					$returnstring.='';
		return $returnstring;
	}
	function isEmptyfieldLi($str) {
		$returnstring='';
		if(count($str)>0)
			foreach($str as $fieldname=>$fieldvalue)
				if(trim($fieldvalue)==NULL)
					$returnstring.='<li>  '.$fieldname.':&nbsp;You must enter a valid '.$fieldname.'. </li>';
				else
					$returnstring.='';
		return $returnstring;
	}
	########## /Is Empty Fields ##########

	// array to comma ( 	Function returns values seperated by comma array(1,2,3,4)=>1,2,3,4
	function arrayToComma($fieldname) {
		$val='';
		if(count($fieldname)>0) {
			$val=implode(',',$fieldname);
		}
		return $val;
	}
	// ) array to comma

	// comma to array ( 	Function returns an array from a comma separated string 1,2,3,4=>array(1,2,3,4)
	function commaToArray($commaString) {
		$arr='';
		if($commaString!='') {
			$arr=explode(',',$commaString);
		}
		return $arr;
	}
	// ) array to comma

	function to_slug($string){
		return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', strip_tags(trim($string))));
	}

	function add_more_accordion($editor_id) {
		return '<fieldset>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
						<tr>
							<td>
								<label for="accordion_title">Accordion Title:</label>
								<input type="text" name="accordion_title['.$editor_id.']" style="width:100%;" value="" onblur="updateSlug(this.value);" class="uniform-input text" /><i class="fa fa-spinner fa-spin" id="slug_imgloader" style="display:none;font-size:22px;line-height:32px;vertical-align:top;animation-duration:1s;"></i>
							</td>
						</tr>
						<tr>
							<td>
								<label for="accordion_desc">Accordion Description:</label>
								<a href="media_files.php?field_name=accordion_desc['.$editor_id.']" class="btn btn-primary" data-toggle="modal" data-target="#modal_'.$editor_id.'"><i class="fa fa-image"></i> Select Media File </a><div class="modal fade" id="modal_'.$editor_id.'" tabindex="-1" role="dialog" aria-labelledby="modalLabel1"></div>
								<textarea name="accordion_desc['.$editor_id.']" class="ckeditor" style="width:650px;" id="editor_'.$editor_id.'"></textarea>
							</td>
						</tr>
						<tr>
							<td><label for="accordion_sort_order">Sort Order#:</label><input type="number" step="any" min="0" class="c" value="" name="accordion_sort_order['.$editor_id.']" style="width:85px"></td>
						</tr>
						<tr>
							<td>
								<label for="accordion_status">Status</label>
								<select name="accordion_status['.$editor_id.']" class="chosen" >
									'.$this->getArray2List($this->status_array,1).'
								</select>
							</td>
						</tr>
					</table>
				</fieldset>';
	}
	function add_more_pdf($add_more_pdf='') {
		if(is_array($this->application_forms_type_arr) && count($this->application_forms_type_arr)>0) {
				$app_form='<label for="application_form_type">Application Form Type</label>
				<select name="application_form_type['.$add_more_pdf.']" class="chosen" style="width:100%;" data-placeholder="Select Application Form Type">
					<option value=""></option>
					<option value="">-- None --</option>';
				foreach($this->application_forms_type_arr as $type_key=>$type_val) {
					$selected=($type_key==$val['application_form_type'])?' selected="selected"':'';
					$app_form.='<option value="'.$type_key.'"'.$selected.'>'.$type_val['title'].'</option>';
				}
				$app_form.='</select><br /><br />';
		}
		return '
			<tr>
				<td>
					<div class="remove_circle_wrap thumb_preview full_w">
						'.$app_form.'
						<label for="pdf_title">File Title:</label><input type="text" name="pdf_title['.$add_more_pdf.']" class="uniform-input text" style="width:100%;" value="" />
						<label for="pdf_file">Upload the PDF file:</label><input type="file" size="40" name="pdf_file['.$add_more_pdf.']"  />
						<label for="pdf_sort_order">Sort Order#:</label><input type="number" step="any" min="0" class="c" value="" name="pdf_sort_order['.$add_more_pdf.']" style="width:85px">
						<label for="pdf_status">Status</label>
						<select name="pdf_status['.$add_more_pdf.']" class="chosen" >
							'.$this->getArray2List($this->status_array,1).'
						</select>
						<a href="#" class="button1 red remove_circle delete_tr" title="remove"><i class="fa fa-remove"></i></a>
					</div>
				</td>
			</tr>
		';
	}


	function multiLevelTr($menuSortedArr, $level=0) {
		global $utilityObj;
		static $s_no=0;
		$resArray='';
		if(is_array($menuSortedArr) && count($menuSortedArr)>0) {
			$indent='';
			for($i=0;$i<$level;$i++)
				$indent.='&mdash;&nbsp;';
			foreach($menuSortedArr as $key=>$arr) {
				$s_no++;
				$resArray.='<tr>';
				$resArray.='<td>'.$s_no.'</td>';
				$resArray.='<td>'.$indent.' <i aria-hidden="true" class="fa fa-'.$arr['class'].'"></i>&nbsp; '.$arr['title'].'</td>';
				$resArray.='<td>'.'<a href="'.$arr['link'].'">'.$arr['link'].'</a></td>';
				$resArray.='<td>'.$arr['active'].'</td>';
				$resArray.='<td>'.$indent.$arr['orderNum'].'</td>';
				$resArray.='<td>'.'<a href="ClassAdminMenu.php?action=switchStatus&menu='.$arr['id'].'">'."<span class='".$utilityObj->status_array[$arr['status']]."'>".$utilityObj->status_array[$arr['status']]."</span></a>".'</td>';
				$resArray.='<td>'.'<a href="manage-admin-menu.php?menu='.$arr['id'].'" class="edit"><i class="fa fa-pencil"></i> Edit</a> <a href="ClassAdminMenu.php?action=delete_menu&menu='.$arr['id'].'" class="delete" onclick="return confirm(\'Are you sure to delete that record?\');"><i class="fa fa-trash"></i> Delete</a>'.'</td>';
				$resArray.='</tr>';
				if(isset($arr['children']) && count($arr['children'])>0) {
					$resArray.=$this->multiLevelTr($arr['children'], ($level+1));
				}
			}
		}
		return $resArray;
	}
	
	function truncateChar($text, $length=250, $suffix = '&hellip;', $isHTML = true) {
	    $i = 0;
	    $simpleTags=array('br'=>true,'hr'=>true,'input'=>true,'image'=>true,'link'=>true,'meta'=>true);
	    $tags = array();
	    if($isHTML){
	        preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
	        foreach($m as $o){
	            if($o[0][1] - $i >= $length)
	                break;
	            $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
	            // test if the tag is unpaired, then we mustn't save them
	            if($t[0] != '/' && (!isset($simpleTags[$t])))
	                $tags[] = $t;
	            elseif(end($tags) == substr($t, 1))
	                array_pop($tags);
	            $i += $o[1][1] - $o[0][1];
	        }
	    }
	
	    // output without closing tags
	    $output = substr($text, 0, $length = min(strlen($text),  $length + $i));
	    // closing tags
	    $output2 = (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
	
	    // Find last space or HTML tag (solving problem with last space in HTML tag eg. <span class="new">)
		$splitted = preg_split('/<.*>| /', $output, -1, PREG_SPLIT_OFFSET_CAPTURE);
		$last_item = end($splitted);
		$very_last_item = end($last_item);
	    $pos = (int)$very_last_item;
	    // Append closing tags to output
	    $output.=$output2;
	
	    // Get everything until last space
	    $one = substr($output, 0, $pos);
	    // Get the rest
	    $two = substr($output, $pos, (strlen($output) - $pos));
	    // Extract all tags from the last bit
	    preg_match_all('/<(.*?)>/s', $two, $tags);
	    // Add suffix if needed
	    if (strlen($text) > $length) { $one = trim($one).$suffix; }
	    // Re-attach tags
	    $output = $one . implode($tags[0]);
	
	    //added to remove  unnecessary closure
	    $output = str_replace('</!-->','',$output); 
	
	    return $output;
	}
}
$utilityObj = new Utility;
?>