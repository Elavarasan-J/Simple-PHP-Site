<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class PageInfo {
	function __construct() {
		$this->db = new DB;
		$this->pathObj = new Path;
		$this->utilityObj = new Utility;
		$this->sanitizationObj = new Sanitization;
	}

	//	Page Info (
	function isHome() {
		return ($this->pathObj->filename=='index.php')?true:false;
	}
	function pageRedirect($page_id='') {
		if($page_id=='') {
			$page_id=$this->get_page_id();
		}
		$page_arr=$this->db->getSingleRec($this->db->TB_page,"page_id=$page_id AND status=1 AND trashed=0");
		if($page_id!='' && is_array($page_arr) && count($page_arr)>0) {
			$page_arr['page_template']=($page_arr['page_template']!='')?$page_arr['page_template']:"page-_default_left_sidebar.php";
		//	header("location:".SITE_PATH.$this->pathObj->page_arr['page_template']."?page_id=$page_id"); exit;
			return BASE_PATH.$page_arr['page_template'];
		} else {
			$this->utilityObj->headerLocation(PNF_PATH);
		}
	}
	function pageInfo($id='') {
		$page_type=$this->utilityObj->getPageType();
		if($id=='') {
			if($page_type!="" && $page_type=="post_archive") {
				$id=(isset($_GET['post_category_id']) && $_GET['post_category_id']!='')?$_GET['post_category_id']:'';
			} else if($page_type!="") {
				$id=(isset($_GET[$page_type.'_id']) && $_GET[$page_type.'_id']!='')?$_GET[$page_type.'_id']:'';
			}
		}
		if(is_numeric($id)) {
			return $this->pageInfo_by_id($id);
		} else {
			return $this->pageInfo_by_slug($id);
		}
	}
	function pageInfo_by_id($id='') {
		$page_type=$this->utilityObj->getPageType();

		$img_path=SITE_PATH.$this->pathObj->media_library_files_path;
		$img_path_medium=SITE_PATH.$this->pathObj->media_library_mediumphotos_path;
		$img_path_thumb=SITE_PATH.$this->pathObj->media_library_thumbphotos_path;

		$fields="*, concat('$img_path', featured_img) as featured_img, concat('$img_path_medium', featured_img) as featured_medium, concat('$img_path_thumb', featured_img) as featured_thumb";
		$table=''; $where='';
		if($page_type=="home" && $id=='') {
			$arr["title"]="Home";
		} else if($page_type=="404" && $id=='') {
			$arr["title"]="404: Page Not Found";
		} else if($page_type=="post_category" || $page_type=="post_archive") {
			$id=($id=='' && isset($_GET['post_category_id']) && $_GET['post_category_id']!='')?$_GET['post_category_id']:$id;
			$table=$this->db->TB_post_category;
			$where="post_category_id=$id";
		} else if($page_type!="") {
			$id=($id=='' && isset($_GET[$page_type.'_id']) && $_GET[$page_type.'_id']!='')?$_GET[$page_type.'_id']:$id;
			$page_type=($id!='')?'page':'';
			$table=$this->db->{"TB_".$page_type};
			$where=$page_type."_id=$id AND trashed=0";
		}
		if($table!='') {
			$arr=$this->db->getSingleRec($table,"$where AND status=1",$fields);
		}
		if(is_array($arr) && count($arr)>0) {
			if($page_type=="page") {
				$arr['page_template']=($arr['page_template']!='')?$arr['page_template']:"page-_default_left_sidebar.php";
			}
			return $arr;
		} else {
		//	$this->utilityObj->headerLocation(PNF_PATH);
		}
	}
	function pageInfo_by_slug($slug='') {
		$page_type=$this->utilityObj->getPageType();

		$img_path=SITE_PATH.$this->pathObj->media_library_files_path;
		$img_path_medium=SITE_PATH.$this->pathObj->media_library_mediumphotos_path;
		$img_path_thumb=SITE_PATH.$this->pathObj->media_library_thumbphotos_path;

		$fields="*, concat('$img_path', featured_img) as featured_img, concat('$img_path_medium', featured_img) as featured_medium, concat('$img_path_thumb', featured_img) as featured_thumb";
		$table=''; $where='';
		if($page_type=="home" && $slug=='') {
			$arr["title"]="Home";
		} else if($page_type=="404" && $slug=='') {
			$arr["title"]="404: Page Not Found";
		} else if($page_type=="post_category" || $page_type=="post_archive") {
			$slug=($slug=='' && isset($_GET['post_category_id']) && $_GET['post_category_id']!='')?$_GET['post_category_id']:$slug;
			$table=$this->db->TB_post_category;
			$where="slug='$slug'";
		} else if($page_type!="") {
			$slug=($slug=='' && isset($_GET[$page_type.'_id']) && $_GET[$page_type.'_id']!='')?$_GET[$page_type.'_id']:$slug;
			$page_type=($slug!='')?'page':'';
			$table=$this->db->{"TB_".$page_type};
			$where="slug='$slug' AND trashed=0";
		}
		if($table!='') {
			$arr=$this->db->getSingleRec($table,"$where AND status=1",$fields);
		}
		if(is_array($arr) && count($arr)>0) {
			if($page_type=="page") {
				$arr['page_template']=($arr['page_template']!='')?$arr['page_template']:"page-_default_left_sidebar.php";
			}
			return $arr;
		} else {
		//	$this->utilityObj->headerLocation(PNF_PATH);
		}
	}

	function getPageArr($parent=0,$l=0,$include_parent=0) {
		$img_path=SITE_PATH.$this->pathObj->media_library_files_path;
		$img_path_medium=SITE_PATH.$this->pathObj->media_library_mediumphotos_path;
		$img_path_thumb=SITE_PATH.$this->pathObj->media_library_thumbphotos_path;
		$include_parent_where=($include_parent==1)?"page_id=$parent":"parent_id=$parent";
		$page_rec=$this->db->getMultipleRec("SELECT *, concat('$img_path', featured_img) as featured_img, concat('$img_path_medium', featured_img) as page_featured_medium, concat('$img_path_thumb', featured_img) as page_featured_thumb FROM ".$this->db->TB_page." WHERE $include_parent_where AND status=1 AND trashed=0 ORDER BY sort_order ASC");
		$i=1;
		foreach($page_rec as $key1=>$val1) {
		//	if($l==0 || $i<$l) {
				$children=$this->getPageArr($val1['page_id'],$l);
				if(is_array($children) && count($children)>0) {
					$page_rec[$key1]['children']=$children;
					$l++;
				}
		//	}
			$i++;
		}
		return $page_rec;
	}
	function getActivePageArr($parent=0,$l=0,$include_parent=0) {
		$img_path=SITE_PATH.$page_featured_img_path;
		$icon_path=SITE_PATH.$page_icon_path;
		$include_parent_where=($include_parent==1)?"AND page_id=$parent":"AND parent_id=$parent";
		$page_rec=$this->db->getMultipleRec("SELECT *, concat('$img_path', page_featured_img) as page_featured_img, concat('$img_path', page_featured_medium) as page_featured_medium, concat('$img_path', page_featured_thumb) as page_featured_thumb FROM $this->db->TB_page WHERE status=1 AND trashed=0 $include_parent_where ORDER BY sort_order ASC");
		$i=1;
		foreach($page_rec as $key1=>$val1) {
		//	if($l==0 || $i<$l) {
				$children=$this->getPageArr($val1['page_id'],$l);
				if(is_array($children) && count($children)>0) {
					$page_rec[$key1]['children']=$children;
					$l++;
				}
		//	}
			$i++;
		}
		return $page_rec;
	}
	function getActiveParentId($id='') {
		$parent_rec=$this->db->getSingleRec($this->db->TB_page,"page_id IN(SELECT parent_id FROM $this->db->TB_page WHERE page_id=$id) AND status=1 AND trashed=0 AND remove_link=0","page_id");
		return $parent_rec['page_id'];
	}

	function getPageQueryArr($WHERE='',$parent=0,$l=0) {
		$sWHERE=($WHERE!='')?($WHERE." AND parent_id=$parent"):"WHERE parent_id=$parent";
		$img_path=SITE_PATH.$this->pathObj->media_library_files_path;
		$img_path_medium=SITE_PATH.$this->pathObj->media_library_mediumphotos_path;
		$img_path_thumb=SITE_PATH.$this->pathObj->media_library_thumbphotos_path;
		$page_rec=$this->db->getMultipleRec("SELECT *, concat('$img_path', featured_img) as featured_img, concat('$img_path_medium', featured_img) as page_featured_medium, concat('$img_path_thumb', featured_img) as page_featured_thumb FROM ".$this->db->TB_page." $sWHERE ORDER BY sort_order ASC");
		$i=1;
		foreach($page_rec as $key1=>$val1) {
		//	if($l==0 || $i<$l) {
				$children=$this->getPageQueryArr($WHERE,$val1['page_id'],$l);
				if(is_array($children) && count($children)>0) {
					$page_rec[$key1]['children']=$children;
					$l++;
				}
		//	}
			$i++;
		}
		return $page_rec;
	}

	function listPage($arr,$selectedArr=array(),$depth=0,$type='opt') {
		$pageOpt='';
		static $i=1;
		foreach($arr as $key1=>$val1) {
			$children='';
			extract($val1);
			$indent_char='';
			for($j=0;$j<$level;$j++) {
				$indent_char.="&mdash; &nbsp;";
			}
			if($type=='li') {
				$pageOpt .= '<li><a href="'.$this->get_the_page_link($page_id).'">'.$this->sanitizationObj->revertHTML($title).'</a>';
			} else if($type=='tr') {
				$pageOpt .= '<tr class="'.(($i%2==0)?"even":"no").(($trashed==1)?' trashed':'').'">
									<td>'.($i).'</td>
									<td>'.$indent_char.$this->sanitizationObj->revertHTML($title).'</td>
									<td><span class="tags">'.$slug.'</span></td>
									<td>'.$indent_char.$sort_order.'</td>';
				
				if($trashed==1) {
						$pageOpt.= '<td><span class="Trashed">Trashed</span></td>
									<td>
										<span class="desktop"><a href="page.php?page='.$page_id.'" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i> Edit</a> <a href="class_action.php?action=restore&key=page_id&id='.$page_id.'&table=page&page=manage_page" class="restore"><i aria-hidden="true" class="fa fa-recycle"></i> Restore</a></span>
										<span class="mobile"><a href="page.php?page='.$page_id.'" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i></a> <a href="class_action.php?action=restore&key=page_id&id='.$page_id.'&table=page&page=manage_page" class="restore"><i aria-hidden="true" class="fa fa-recycle"></i></a></span>
									</td>';
				} else {
						$pageOpt.= '<td><a href="class_action.php?action=switchStatus&key=page_id&id='.$page_id.'&table=page&page=manage_page"><span class="'.$this->utilityObj->status_array[$status].'">'.$this->utilityObj->status_array[$status].'</span></a></td>
									<td>
										<span class="desktop"><a href="page.php?page='.$page_id.'" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i> Edit</a> <a href="class_action.php?action=trash&key=page_id&id='.$page_id.'&table=page&page=manage_page" class="delete" onclick="return confirm(\'Are you sure to delete that record?\');"><i aria-hidden="true" class="fa fa-trash"></i> Delete</a></span>
										<span class="mobile"><a href="page.php?page='.$page_id.'" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i></a> <a href="class_action.php?action=trash&key=page_id&id='.$page_id.'&table=page&page=manage_page" class="delete" onclick="return confirm(\'Are you sure to delete that record?\');"><i aria-hidden="true" class="fa fa-trash"></i></a></span>
									</td>';
				}
				$pageOpt .= '</tr>';
			} else {
				$selected=(is_array($selectedArr) && in_array($page_id,$selectedArr))?' selected="selected"':'';
				$pageOpt .= '<option value="'.$page_id.'"'.$selected.'>'.$indent_char.$this->sanitizationObj->revertHTML($title).'</option>';
			}
			$i++;
			if(isset($children) && is_array($children) && count($children)>0 && ($depth==0 || $depth>$level+1)) {
				if($type=='li') { $tr.='<ul>'; }
				$pageOpt.=$this->listPage($children,$selectedArr,$depth,$type);
				if($type=='li') { $tr.='</ul>'; }
			}
			if($type=='li') { $tr.='</li>'; }
		}
		return $pageOpt;
	}

	
	function get_sidebar_submenu($id='') {
		if($id=='') {
			$id=$this->get_page_id();
		}
		$parent_id=$this->get_parent_id($id);
		$grand_id=($parent_id>0)?$this->get_parent_id($parent_id):'';
		$children_arr=$this->getPageArr($id,1);
		if(!is_array($children_arr) || count($children_arr)<1) {
			$children_arr=$this->getPageArr($parent_id,1);
		}
		$parent_title='';
	//	$parent_title=' <span>'.(($grand_id>0)?$this->get_the_title($parent_id):$this->get_the_title()).'</span>';
		$grand_title=($grand_id>0)?$this->get_the_title($grand_id):$this->get_the_title($parent_id);
		$submenu='';
		$submenu= ($grand_title!='' || $parent_title!='')?('<h4>'.$grand_title.$parent_title.'</h4>'):'';
		if(is_array($children_arr) && count($children_arr)>0) {
			$submenu.='<ul class="sidebar-list">';
			foreach($children_arr as $key=>$val) {
				$submenu.='<li'.(($val['page_id']==$this->get_page_id())?' class="active"':'').'><a href="'.$this->get_the_page_link($val['page_id']).'">'.$this->sanitizationObj->revertHTML($val['title']).'</a></li>';
			}
			$submenu.='</ul>';
		}
		echo $submenu;
	}
	function get_header_submenu($id) {
		$page_type=$this->utilityObj->getPageType();
		$parent_active='';
		if($page_type=='page') {
			$parent_active=((($id==$this->get_page_id()) || ($id==$this->get_parent_id($this->get_page_id())))?' active':'');
		}
		$children_arr=$this->getPageArr($id,1);
		
		$submenu= '<li class="dropdown'.$parent_active.'"><a href="'.$this->get_the_page_link($id).'">'.$this->get_the_title($id).'</a>';
		if(is_array($children_arr) && count($children_arr)>0) {
			$submenu.='<ul class="dropdown-menu dm-left">';
			foreach($children_arr as $key=>$val) {
				$child_active='';
				if($page_type=='page') {
					$child_active=(($val['page_id']==$this->get_page_id())?' class="active"':'');
				}
				$submenu.='<li'.$child_active.'><a href="'.$this->get_the_page_link($val['page_id']).'">'.$this->sanitizationObj->revertHTML($val['title']).'</a></li>';
			}
			$submenu.='</ul>';
		}
		$submenu.='</li>';
		echo $submenu;
	}
	
	 
	
	function get_sidebar_submenu_old($id='') {
		if($id=='') {
			$id=$this->get_page_id();
		}
		$parent_id=$this->get_parent_id($id);
		$grand_id=($parent_id>0)?$this->get_parent_id($parent_id):'';
		$children_arr=$this->getPageArr($id,1);
		if(!is_array($children_arr) || count($children_arr)<1) {
			$children_arr=$this->getPageArr($parent_id,1);
		}
		$parent_title=($grand_id>0)?$this->get_the_title($parent_id):$this->get_the_title();
		$grand_title=($grand_id>0)?$this->get_the_title($grand_id):$this->get_the_title($parent_id);
		$submenu= '<ul class="sidebar-list"><li class="active"><a href="'.$this->get_the_page_link($parent_id).'">'.$parent_title.'</a><ul>';
		foreach($children_arr as $key=>$val) {
			$submenu.='<li'.(($val['page_id']==$this->get_page_id())?' class="active"':'').'><a href="'.$this->get_the_page_link($val['page_id']).'">'.$this->sanitizationObj->revertHTML($val['title']).'</a></li>';
		}
		$submenu.='</ul></li></ul>';
		echo $submenu;
	}
	function get_sidebar_submenu_new($id='') {
		if($id=='') {
			$id=$this->get_page_id();
		}
		$parent_id=$this->get_parent_id($id);
		$grand_id=($parent_id>0)?$this->get_parent_id($parent_id):'';
		$children_arr=$this->getActivePageArr($id,1);
		
		$parent_title=($this->get_the_title($parent_id)!='')?$this->get_the_title($parent_id):$this->get_the_title();
		$grand_title=($grand_id>0)?$this->get_the_title($grand_id):$this->get_the_title($parent_id);
		
		$submenu='<h4 class="md-title">'.$parent_title.'</h4>';
		$submenu.='<ul class="sidebar-list"><li><a href="'.$this->get_the_page_link($id).'">'.$this->get_the_title().'</a>';
		if(is_array($children_arr) && count($children_arr)>0) {
			$submenu.='<ul>';
			foreach($children_arr as $key=>$val) {
				$submenu.='<li'.(($val['page_id']==$this->get_page_id())?' class="active"':'').'><a href="'.$this->get_the_page_link($val['page_id']).'">'.$this->sanitizationObj->revertHTML($val['title']).'</a></li>';
			}
			$submenu.='</ul>';
		}
		$submenu.='</ul>';
		echo $submenu;
	}
	function get_sidebar_multilevel_submenu($id='',$l=1) {
		if($id=='') {
			$id=$this->get_page_id();
		}
		$children_arr=$this->getPageArr($id,1);
		$submenu='';
		foreach($children_arr as $key=>$val) {
			$submenu.='<li'.(($val['page_id']==$this->get_page_id())?' class="active"':'').'><a href="'.$this->get_the_page_link($val['page_id']).'">'.$this->sanitizationObj->revertHTML($val['title']).'</a>';
			$childmenu=$this->getPageArr($val['page_id'],1);
			if(is_array($childmenu) && count($childmenu)>0 && $l>$val['level']) {
				$submenu.='<ul>';
				$submenu.=$this->get_sidebar_multilevel_submenu($val['page_id'],$l);
				$submenu.='</ul>';
			}
			$submenu.='</li>';
		}
		return $submenu;
	}
	function sidebar_multilevel_submenu($id='',$l=1) {
		echo $this->get_sidebar_multilevel_submenu($id,$l);
	}
	function get_tab_btns_arr($id='') {
		$info=$this->pageInfo();
		return explode(',',$info['tab_btns']);
	}
	function get_archives($id='') {
		$link=$this->getURL($id,"post_archive");
		return $archive_rec=$this->db->getMultipleRec("SELECT DISTINCT YEAR(published_time) AS year, CONCAT('$link',YEAR(published_time)) as link FROM $this->db->TB_post WHERE post_category_id=$id AND status=1 ORDER BY year DESC");
	}
	function list_archive($id='') {
		$archive_arr=($this->get_archives($id));
		$current_year=(isset($_GET['year']) && $_GET['year']!='')?$_GET['year']:'';
		foreach($archive_arr as $key=>$val) {
			$active_class=($current_year==$val['year'])?' class="active"':'';
			$li.='<li'.$active_class.'><a href="'.$val['link'].'">'.$val['year'].'</a></li>';
		}
		echo $li;
	}
	function get_gallery_archives($id='') {
		$link=$this->getURL($id,"gallery");
		return $archive_rec=$this->db->getMultipleRec("SELECT DATE_FORMAT(published_time, '%Y %M') AS yearMonth, CONCAT('$link',DATE_FORMAT(published_time, '%Y/%M')) as link FROM $this->db->TB_gallery WHERE exclude=0 AND status=1 GROUP BY yearMonth ORDER BY yearMonth DESC");
	}
	function list_gallery_archive($id='') {
		$archive_arr=($this->get_gallery_archives($id));
		foreach($archive_arr as $key=>$val) {
			$li.='<li><a href="'.$val['link'].'">'.$val['yearMonth'].'</a></li>';
		}
		echo $li;
	}
	function get_event_archives($id='') {
		$link=$this->getURL($id,"event_archive");
		return $archive_rec=$this->db->getMultipleRec("SELECT DATE_FORMAT(start_time, '%Y %M') AS yearMonth, CONCAT('$link',DATE_FORMAT(start_time, '%Y/%M')) as link FROM $this->db->TB_event WHERE status=1 GROUP BY yearMonth ORDER BY yearMonth ASC");
	}
	function list_event_archive($id='') {
		$archive_arr=(get_event_archives($id));
		foreach($archive_arr as $key=>$val) {
			$li.='<li><a href="'.$val['link'].'">'.$val['yearMonth'].'</a></li>';
		}
		echo $li;
	}
	function get_event_dates() {
		$events_rec=$this->db->getSingleRec($this->db->TB_event,"status=1 ORDER BY eventDate ASC","GROUP_CONCAT(DATE_FORMAT(start_time, '%m/%d/%Y')) AS eventDate");
		return explode(',',$events_rec['eventDate']);
	//	return $events_rec=$this->db->getMultipleRec("SELECT DATE_FORMAT(start_time, '%m/%d/%Y') AS eventDate FROM $this->db->TB_event WHERE status=1 GROUP BY eventDate ORDER BY eventDate ASC");
	}
	function get_page_id($id='') {
		$info=$this->pageInfo($id);
		return ((isset($info['page_id']))?$info['page_id']:'');
	}
	function page_id($id='') {
		echo $this->get_page_id($id); 
	}
	function get_parent_id($id='') {
		$info=$this->pageInfo($id);
		return ((isset($info['parent_id']))?$info['parent_id']:'');
	}
	function parent_id($id='') {
		echo $this->get_parent_id($id); 
	}
	function get_post_category_id($id='') {
		$info=$this->pageInfo($id);
		return $info['post_category_id'];
	}
	function post_category_id($id='') {
		echo $this->get_post_category_id($id); 
	}
	function get_post_id($id='') {
		$info=$this->pageInfo($id);
		return $info['post_id'];
	}
	function post_id($id='') {
		echo $this->get_post_id($id); 
	}
	function get_experiences($limit=0) {
		$limit=($limit>0)?" LIMIT $limit":"";
		
		$posts_arr=$this->db->getMultipleRec("SELECT * FROM ".$this->db->TB_experience." WHERE status=1 ORDER BY published_time DESC".$limit);
		
		return $posts_arr;
	}
	function get_posts($id,$type="cat",$limit=0) {
		$img_path=get_site_info('path').$this->pathInfoObj->post_img_path;
		$limit=($limit>0)?" LIMIT $limit":"";
		if($type=="single") {
			$where="WHERE a.post_id=$id AND a.post_category_id=b.post_category_id AND a.status=1";
		} else {
			$where="WHERE a.post_category_id=$id AND a.post_category_id=b.post_category_id AND a.status=1";
		}
		$posts_arr=$this->db->getMultipleRec("SELECT a.*, concat('$img_path', a.post_img) as post_img, concat('$img_path', a.post_img_medium) as post_img_medium, concat('$img_path', a.post_img_thumb) as post_img_thumb, b.title as cat_title FROM $this->db->TB_post a, $this->db->TB_post_category b $where ORDER BY a.published_time DESC".$limit);
		
		return $posts_arr;
	}
	function get_paginated_posts_sql($id,$type="cat") {
		$img_path=get_site_info('path').$this->pathInfoObj->post_img_path;
		$limit=($limit>0)?" LIMIT $limit":"";
		$archive_year=getArchiveYear();
		$archive_where=($archive_year!='')?("YEAR(a.published_time)=".$archive_year." AND "):"";
		
		if($type=="search") {
			$search_term=$_REQUEST['search_term'];
			$where="WHERE a.post_category_id=$id AND a.post_category_id=b.post_category_id AND (a.title LIKE '%$search_term%' OR a.slug LIKE '%$search_term%' OR a.short_description LIKE '%$search_term%' OR a.description LIKE '%$search_term%') AND a.status=1";
		} else if($type=="single") {
			$where="WHERE a.post_id=$id AND a.post_category_id=b.post_category_id AND a.status=1";
		} else if($type=="archive") {
			$where="WHERE $archive_where a.post_category_id=$id AND a.post_category_id=b.post_category_id AND a.status=1";
		} else {
			$where="WHERE a.post_category_id=$id AND a.post_category_id=b.post_category_id AND a.status=1";
		}
		$sql="SELECT a.*, concat('$img_path', a.post_img) as post_img, concat('$img_path', a.post_img_medium) as post_img_medium, concat('$img_path', a.post_img_thumb) as post_img_thumb, b.title as cat_title FROM $this->db->TB_post a, $this->db->TB_post_category b $where ORDER BY a.published_time DESC";
		
		return $sql;
	}
	function get_paginated_events_sql($type='') {
		$img_path=get_site_info('path').$event_img_path;
		$limit=($limit>0)?" LIMIT $limit":"";
		$archive_yearMonth=$_GET['year'].'-'.$_GET['month'].((isset($_GET['date']))?("-".$_GET['date']):"");
		$archive_yearMonthCond='%Y-%M'.((isset($_GET['date']))?'-%d':'');
		$archive_where=($archive_yearMonth!='')?("DATE_FORMAT(start_time, '$archive_yearMonthCond')='$archive_yearMonth' AND "):"";
		if($type=="single") {
			$slug=$_GET['event'];
			$where="WHERE slug='$slug' AND status=1";
			$orderBy="";
		} else if($type=="archive") {
			$where="WHERE $archive_where status=1";
			$orderBy=" ORDER BY start_time ASC";
		} else {
			$where="WHERE status=1";
			$orderBy=" ORDER BY start_time ASC";
		}
		$sql="SELECT *, concat('$img_path', event_img) as event_img, concat('$img_path', event_img_medium) as event_img_medium, concat('$img_path', event_img_thumb) as event_img_thumb FROM $this->db->TB_event $where $orderBy";
		
		return $sql;
	}
	function getArchiveYear() {
		if(isset($_GET['post_category_id']) && $_GET['post_category_id']!='' && isset($_GET['year']) && $_GET['year']!='') {
			return $_GET['year'];
		}
	}
	function update_view_count($id='') {
		if($id>0) {
			$flds="view_count=view_count+1";
			$where="post_id=$id";
			$this->db->main_update($flds,$where,$this->db->TB_post);
		}
	}
	function get_the_title($id='') {
		$info=$this->pageInfo($id);
		return $this->sanitizationObj->revertHTML($info['title']);
	}
	function the_title($id='') {
		echo $this->get_the_title($id); 
	}
	function get_the_subtitle($id='') {
		$info=$this->pageInfo($id);
		return $info['subtitle']; 
	}
	function the_subtitle($id='') {
		echo $this->get_the_subtitle($id); 
	}
	function get_the_content($id='') {
		$info=$this->pageInfo($id);
	//	return $this->sanitizationObj->revertHTML($info['description']);
		$content=$this->sanitizationObj->revertHTML($info['description']);
		$content=$this->do_gid_short_codes($content);
		$content=$this->do_BoardMembers_short_codes($content);
		$content=$this->do_Projects_short_codes($content);
		$content=$this->do_Media_short_codes($content);
		return $content;
	}
	function the_content($id='') {
		echo $this->get_the_content($id);
	}
	function get_the_excerpt($id='') {
		$info=$this->pageInfo($id);
		return $this->sanitizationObj->revertHTML($info['short_description']);
	}
	function the_excerpt($id='') {
		echo $this->get_the_excerpt($id);
	}
	function get_page_accordion($id='') {
		$id=($id!='')?$id:$this->get_page_id();
		$accordion_arr=$this->db->getMultipleRec("SELECT * FROM ".$this->db->TB_page_accordion." WHERE page_id=$id AND accordion_status=1 ORDER BY accordion_sort_order ASC");
		if(is_array($accordion_arr) && count($accordion_arr)>0) {
			return $accordion_arr;
		} else {
			return false;
		}
	}
	function the_page_accordion($id='') {
		$accordion='';
		$accordion_arr=$this->get_page_accordion($id);
		if(is_array($accordion_arr) && count($accordion_arr)>0) {
			$accordion='<ul class="accordian">';
			$i=0;
			foreach($accordion_arr as $key=>$arr) {
				$content=$this->sanitizationObj->revertHTML($arr['accordion_desc']);
				$content=$this->do_gid_short_codes($content);
				$content=$this->do_BoardMembers_short_codes($content);
				$content=$this->do_Projects_short_codes($content);
				$content=$this->do_Media_short_codes($content);

				$accordion.='<li'.(($i==0)?' class="active"':'').'>
                                    <a class="collapse-link">'.$arr['accordion_title'].' <i class="fa fa-angle-down"></i></a>
                                    <div class="collapse-content collapse'.(($i==0)?' in':'').'" aria-expanded="true">
                                        <div class="collapse-inner">
											'.$content.'
                                        </div>
                                    </div>
                                </li>';
				$i++;
			}
			$accordion.='</ul>';
		}
		echo $accordion;
	}
	function get_the_featured_img($id='') {
		$info=$this->pageInfo($id);
		return (isset($info['featured_img']))?($info['featured_img']):"";
	}
	function the_featured_img($id='') {
		$info=$this->pageInfo($id);
		$url=$this->get_the_featured_img($id);
		echo ($url!='')?('<img src="'.get_the_featured_img($id).'" alt="'.$info['title'].'" class="img-responsive" />'):'';
	}
	function get_the_cat_img($id='') {
		$info=$this->pageInfo($id);
		return ($info['category_img']!='')?($info['category_img']):"";
	}
	function get_the_page_link($id='') {
		return $this->getURL($id,'page');
	}
	function the_page_link($id='') {
		echo $this->get_the_page_link($id);
	}
	function get_the_post_link($id='') {
		return $this->getURL($id,'post');
	}
	function the_post_link($id='') {
		echo $this->get_the_post_link($id);
	}
	function get_the_cat_link($id='') {
		return $this->getURL($id,'post_category');
	}
	function the_cat_link($id='') {
		echo $this->get_the_cat_link($id);
	}
	function get_the_event_link($id='') {
		return $this->getURL($id,'event_single');
	}
	function the_event_link($id='') {
		echo $this->get_the_event_link($id);
	}
	function get_the_cat_title($id='') {
		if($id!='') {
			$cat_rec=$this->db->getSingleRec($this->db->TB_post_category,"post_category_id=$id","title");
			return $cat_rec['title'];
		} return false;
	}
	function the_cat_title($id='') {
		echo $this->get_the_cat_title($id);
	}
	function get_the_cat_content($id='') {
		if($id!='') {
			$cat_rec=$this->db->getSingleRec($this->db->TB_post_category,"post_category_id=$id","content");
			return $cat_rec['content'];
		} return false;
	}
	function the_cat_content($id='') {
		echo $this->get_the_content($id);
	}

	function getURL($id='',$type,$current='',$dont_remove_link=0) {
		if($type=="page") {
			
			
			
			$home_link='';
			$pageArr = $this->db->getSingleRec($this->db->TB_page,"page_id = $id AND status=1");
			if($pageArr['remove_link']==1 && $dont_remove_link==0) {
				$childrenArr=$this->getPageArr($id);
				return $this->get_the_page_link($childrenArr[0]['page_id']);
			} else if($pageArr['parent_id'] == 0) {
				$slug = $pageArr['slug'];
				return SITE_PATH."page/".$slug;
			} else {
				$slug = $pageArr['slug'];
				return $this->getURL($pageArr['parent_id'], $type, $id, 1). "/".$slug;
			}
		} else if($type=="post_category") {
			$pageArr = $this->db->getSingleRec($this->db->TB_post_category,"post_category_id = $id AND status=1");
			
			$slug = $pageArr['slug'];
			return SITE_PATH."post_category/".$slug;
		} else if($type=="post_archive") {
			$pageArr = $this->db->getSingleRec($this->db->TB_post_category,"post_category_id = $id AND status=1");
			
			$slug = $pageArr['slug'];
			return SITE_PATH."post_archive/".$slug."/";
		} else if($type=="post") {
			$pageArr = $this->db->getSingleRec($this->db->TB_post,"post_id = $id AND status=1");
			$catArr = $this->db->getSingleRec($this->db->TB_post_category,"post_category_id=$pageArr[post_category_id]");
			$cat_link="post/".$catArr['slug'];
			$slug = $pageArr['slug'];
			return SITE_PATH.$cat_link."/".$slug;
		} else if($type=="gallery") {
			$galleryArr = $this->db->getSingleRec($this->db->TB_gallery,"gallery_id=$id");
			$gallery_link="gallery/".$galleryArr['slug'];
			return SITE_PATH.$gallery_link;
		} else if($type=="event") {
			$event_link="event";
			return SITE_PATH.$event_link;
		} else if($type=="event_archive") {
			$eventArr = $this->db->getSingleRec($this->db->TB_event,"event_id=$id");
			$event_link="event_archive/".$eventArr['slug'];
			return SITE_PATH.$event_link;
		} else if($type=="event_single") {
			$eventArr = $this->db->getSingleRec($this->db->TB_event,"event_id=$id");
			$event_link="event_single/".$eventArr['slug'];
			return SITE_PATH.$event_link;
		}
	}
	function theURL($id, $type, $current='') {
		echo $this->getURL($id, $type, $current);
	}

	/* Gallery */
	function get_gallery_link($gallery_id='') {
		return SITE_PATH.'gallery'.(($gallery_id!='')?('/'.$gallery_id):'');
	}
	function gallery_link($gallery_id='') {
		echo $this->get_gallery_link($gallery_id);
	}
	function getGalleryThumbsArr($gallery_id) {
		$galleries=false;
		return $this->db->getMultipleRec("SELECT * FROM ".$this->db->TB_gallery_photos." WHERE gallery_id=$gallery_id AND status=1 ORDER BY gallery_id DESC");
	}
	function get_gallery_title($gallery_id='') {
		$gallery_rec=$this->db->getSingleRec($this->db->TB_gallery,"gallery_id=$gallery_id", 'title');
		return ($gallery_rec['title']!='')?$gallery_rec['title']:'Gallery';
	}
	function gallery_title($gallery_id='') {
		echo $this->get_gallery_title($gallery_id);
	}
	function getGalleryList() {
		$galleries=false;
		$sql="SELECT a.gallery_id,a.title,a.long_title,a.description,a.gallery_img,(SELECT COUNT(photo_id) FROM $this->db->TB_gallery_photos WHERE gallery_id=a.gallery_id) AS num_photos FROM $this->db->TB_gallery a WHERE exclude!=1 AND status=1 ORDER BY gallery_id DESC";
		$query=mysqli_query($this->db->conn, $sql);
		$num_sql=@mysqli_num_rows($query);
		$i=0;
		if($num_sql>0) {
			while($fetch=mysqli_fetch_assoc($query)) {
				extract($fetch);
				$gallery_img_path=BASE_PATH.$gallery_path.$gallery_img;
				$gallery_images=SITE_PATH.$gallery_path.$gallery_img;
				$link=$this->get_gallery_link($gallery_id);
				$image=($gallery_img!='' && is_file($gallery_img_path))?('<a href="'.$link.'"><img src="'.$gallery_images.'" alt="" /></a>'):'';
				$gallery_info=('<div class="gallery-info">
									<h4>'.stripslashes($title).'</h4>
									<span>'.$num_photos.' images</span>
								</div>');
				if($num_photos>0) {
					$galleries.='
						<div class="col-sm-4 col-md-3">
							<div class="each-gallery">
								'.$image.$gallery_info.'
							</div>
						</div>';
				}
				$i++;
			}
		}
		if($galleries==true && count($galleries)>0)
			return $galleries;
	}
	function getGalleryThumbs($gallery_id) {
		$galleries=false;
		$sql="SELECT * FROM ".$this->db->TB_gallery_photos." WHERE gallery_id=$gallery_id AND status=1 ORDER BY gallery_id DESC";
		$query=mysqli_query($this->db->conn, $sql);
		$num_sql=@mysqli_num_rows($query);
		$i=0;
		if($num_sql>0) {
			while($fetch=mysqli_fetch_assoc($query)) {
				extract($fetch);
				$gallery_img_path=BASE_PATH.$galleryphotos_path.$img_name;
				$gallery_images=SITE_PATH.$galleryphotos_path.$img_name;
				$gallery_medium=SITE_PATH.$gallerymediumphotos_path.$img_name;
				$link=$gallery_images;
				$image=($img_name!='' && is_file($gallery_img_path))?sprintf('<a href="%s" class="gallery" rel="gallery1"><img src="%s" alt="" /></a>',$link,$gallery_medium):'';
				if($image!='') {
					$galleries.='
						<div class="col-sm-4 col-md-3">
							<div class="each-gallery">
								'.$image.'
							</div>
						</div>';
				}
				$i++;
			}
		}
		if($galleries==true && count($galleries)>0)
			return $galleries;
	}
	function getGalleryThumbsSlider($gallery_id) {
		$galleries=false;
		$sql="SELECT * FROM ".$this->db->TB_photoalbum." WHERE album_id=$gallery_id AND status=1 ORDER BY album_id DESC";
		$query=mysqli_query($this->db->conn, $sql);
		$num_sql=@mysqli_num_rows($query);
		$i=0;
		if($num_sql>0) {
			while($fetch=mysqli_fetch_assoc($query)) {
				extract($fetch);
				$gallery_img_path=BASE_PATH.$albumphotos_path.$img_name;
				$gallery_images=SITE_PATH.$albumphotos_path.$img_name;
				$gallery_medium=SITE_PATH.$albummediumphotos_path.$img_name;
				$link=$gallery_images;
				$image=($img_name!='' && is_file($gallery_img_path))?sprintf('<a href="%s" class="gallery" rel="gallery1"><img src="%s" alt="" /></a>',$link,$gallery_medium):'';
				if($image!='') {
					$limg.='<li>
						<img src="'.$link.'" alt="Slider '.$gallery_id.'">
						</li>';
					$simg.='<li>
						<img src="'.$gallery_medium.'" alt="Slider '.$gallery_id.'">
						</li>';
				}
				$i++;
			}
			$galleries=' <div id="slider'.$gallery_id.'" class="flexslider">
			<ul class="slides">
				'.$limg.'
			</ul>
			</div>
			<div id="carousel'.$gallery_id.'" class="flexslider thumb">
			<ul class="slides">
				'.$simg.'
			</ul>
			</div>';
		}
		if($galleries!='')
			return $galleries;
	}
	function getGalleryThumbsList($gallery_id) {
		$thumbList='';
		$gallery_rec=$this->db->getSingleRec($this->db->TB_gallery,"gallery_id=$gallery_id AND status=1");
		$i=0;
		if(is_array($gallery_rec) && count($gallery_rec)>0) {
			extract($gallery_rec);

			$gallery_items_arr=((isset($gallery_items) && $gallery_items!='')?explode(',',$gallery_items):'');
			if(is_array($gallery_items_arr) && count($gallery_items_arr)>0) {
				$thumbList.='<ul class="partner-list">';
				foreach($gallery_items_arr as $key=>$img) {
					$media_file=BASE_PATH.$this->pathObj->media_library_thumbphotos_path.$img;
					$thumb_path=SITE_PATH.$this->pathObj->media_library_thumbphotos_path.$img;
					$medium_path=SITE_PATH.$this->pathObj->media_library_mediumphotos_path.$img;
					$original_path=SITE_PATH.$this->pathObj->media_library_files_path.$img;
					$thumbList.='<li><img src="'.$medium_path.'" alt=""></li>';
				}
				$thumbList.='</ul>';
			}
		}
		return $thumbList;
	}
	function getGalleryArchives($year='',$month='') {
		$galleries=false;
		$yearMonth="'$year-$month'";
		$sql="SELECT a.gallery_id,a.title,a.long_title,a.description,a.gallery_img,(SELECT COUNT(photo_id) FROM $this->db->TB_gallery_photos WHERE gallery_id=a.gallery_id) AS num_photos FROM $this->db->TB_gallery a WHERE DATE_FORMAT(published_time, '%Y-%M')=$yearMonth AND exclude!=1 AND status=1 ORDER BY gallery_id DESC";
		$query=mysqli_query($this->db->conn, $sql);
		$num_sql=@mysqli_num_rows($query);
		$i=0;
		if($num_sql>0) {
			while($fetch=mysqli_fetch_assoc($query)) {
				extract($fetch);
				$gallery_img_path=BASE_PATH.$gallery_path.$gallery_img;
				$gallery_images=SITE_PATH.$gallery_path.$gallery_img;
				$link=$this->get_gallery_link($gallery_id);
				$image=($gallery_img!='' && is_file($gallery_img_path))?('<a href="'.$link.'"><img src="'.$gallery_images.'" alt="" /></a>'):'';
				$gallery_info=('<div class="gallery-info">
									<h4>'.stripslashes($title).'</h4>
									<span>'.$num_photos.' images</span>
								</div>');
				if($num_photos>0) {
					$galleries.='
						<div class="col-sm-4 col-md-3">
							<div class="each-gallery">
								'.$image.$gallery_info.'
							</div>
						</div>';
				}
				$i++;
			}
		}
		if($galleries==true && count($galleries)>0)
			return $galleries;
	}
	function get_gid_thumb_slider_short_codes($content) {
		$regex = "/(\[GID_THUMB_SLIDER=(.*?)\])|(\<p\>\[GID_THUMB_SLIDER=(.*?)\]\<\/p\>)/";
		preg_match_all($regex, $content, $matches);
		return $matches;
	}
	function get_gid_thumb_list_short_codes($content) {
		$regex = "/(\[GID_THUMB_LIST=(.*?)\])|(\<p\>\[GID_THUMB_LIST=(.*?)\]\<\/p\>)/";
		preg_match_all($regex, $content, $matches);
		return $matches;
	}
	function do_gid_short_codes($content) {
		// Thumb Slider
		$matches=$this->get_gid_thumb_slider_short_codes($content);
		$i=0;
		foreach($matches[1] as $key=>$gid) {
			$gallery=$this->getGalleryThumbsSlider($gid);
			$content = str_replace($matches[0][$i], $gallery, $content);
			$i++;
		}
		// /Thumb Slider
		
		// Thumb List
		$matches=$this->get_gid_thumb_list_short_codes($content);
		$i=0;
		foreach($matches[4] as $key=>$gid) {
			$gallery=$this->getGalleryThumbsList($gid);
			$content = str_replace($matches[0][$i], $gallery, $content);
			$i++;
		}
		// /Thumb List

		return $content;
	}
	function get_multi_gallery_script() {
		$info=$this->pageInfo($id);
		$content=$this->sanitizationObj->revertHTML($info['description']);
		$matches=$this->get_gid_thumb_slider_short_codes($content);
		$script='';
		$i=1;
		foreach($matches[1] as $key=>$val) {
			$script.='
					//	Script '.$i.'
						$("#carousel'.$val.'").flexslider({
							animation: "slide",
							controlNav: false,
							animationLoop: false,
							slideshow: false,
							itemWidth: 210,
							itemMargin: 5,
							asNavFor: "#slider'.$val.'"
						});
						$("#slider'.$val.'").flexslider({
							animation: "slide",
							controlNav: false,
							animationLoop: false,
							slideshow: false,
							sync: "#carousel'.$val.'"
						});
					//	Script '.$i.'
			';
			$i++;
		}
		return $script;
	}
	/* /Gallery */

	/* Board Members */
	function get_BoardMembers_short_codes($content) {
		$regex = "/(\[board_members\])|(\<p\>\[board_members\]\<\/p\>)/";
		preg_match_all($regex, $content, $matches);
		return $matches;
	}
	function getBoardMembers() {
		$boardMembers_arr=$this->db->getMultipleRec("SELECT * FROM ".$this->db->TB_board_members." WHERE status=1 ORDER BY board_type ASC, sort_order ASC");
		if(is_array($boardMembers_arr) && count($boardMembers_arr)>0) {
			$boardMembers_title=$boardMembers=array(1=>'',2=>'');
			foreach($boardMembers_arr as $key=>$bmArr) {
				$boardType=$this->utilityObj->boardType_arr[$bmArr['board_type']];
				$boardMembers_title[$bmArr['board_type']]='<h2 class="team-title">'.((isset($bmArr['board_type']) && $bmArr['board_type']==1)?'Myrtle '.$boardType:$boardType).'</h2>';
				$boardMembers[$bmArr['board_type']].='
					<div class="col-sm-4 col-md-4">
						<div class="team-member">
							<img class="img-responsive" src="'.(SITE_PATH.$this->pathObj->media_library_files_path.$bmArr['featured_img']).'" alt="'.$bmArr['title'].'">
							<h4>'.$bmArr['title'].'</h4>
							<span>'.$bmArr['position'].'</span>
						</div>
					</div>
				';
			}
			$boardMembers_html='';
			foreach($this->utilityObj->boardType_arr as $key=>$val) {
				if(isset($boardMembers_title[$key],$boardMembers[$key]) && $boardMembers_title[$key]!='' && $boardMembers[$key]!='') {
					$boardMembers_html.=$boardMembers_title[$key].'<div class="row">'.$boardMembers[$key].'</div>';
				}
			}
			return $boardMembers_html;
		}
		return NULL;
	}
	function do_BoardMembers_short_codes($content) {
		$matches=$this->get_BoardMembers_short_codes($content);
		$i=0;
		foreach($matches[0] as $shortCode) {
			$boardMembers=$this->getBoardMembers();
			$content = str_replace($matches[0][$i], $boardMembers, $content);
			$i++;
		}
		return $content;
	}
	/* /Board Members */

	/* Projects */
	function get_Projects_short_codes($content) {
		$regex = "/(\[projects\])|(\<p\>\[projects\]\<\/p\>)/";
		preg_match_all($regex, $content, $matches);
		return $matches;
	}
	function getProjects() {
		$event_status_where = '';
        if(isset($_POST['event_status']) && $_POST['event_status']!='') {
            if($_POST['event_status']==0) {
                // Upcoming
                $event_status_where = ' and from_date>CURDATE()';
            } else if($_POST['event_status']==1) {
                // Current
                $event_status_where = ' and CURDATE() BETWEEN from_date AND to_date';
            } else if($_POST['event_status']==2) {
                // Completed
                $event_status_where = ' and to_date<CURDATE()';
            }
        }
		$projects_arr=$this->db->getMultipleRec("SELECT * FROM ".$this->db->TB_project." WHERE status=1".$event_status_where." ORDER BY from_date ASC, to_date DESC");
		if(is_array($projects_arr) && count($projects_arr)>0) {
			$projects_html='';
			foreach($projects_arr as $key=>$arr) {
				$event_status = '';
		        if(isset($arr['from_date'],$arr['to_date']) && $arr['from_date']!='' && $arr['to_date']!='') {
		            if($arr['from_date']>DATE("Y-m-d")) {
		                // Upcoming
		                $event_status = 'Upcoming';
		            } else if($arr['from_date']<=DATE("Y-m-d") && $arr['to_date']>=DATE("Y-m-d")) {
		                // Current
		                $event_status = 'Current';
		            } else if($arr['to_date']<DATE("Y-m-d")) {
		                // Completed
		                $event_status = 'Completed';
		            }
		        }
                $target=((isset($arr['external_link']) && $arr['external_link']==1)?' target="_blank"':'');
				$projects_html.='
                            <div class="project-row">
                                <div class="row">
                                    <div class="col-sm-5 col-md-5">
                                        <div class="project-img">
                                            <img class="img-responsive" src="'.(SITE_PATH.$this->pathObj->media_library_files_path.$arr['featured_img']).'" alt="'.$arr['title'].'">
                                        </div>
                                    </div>
                                    <div class="col-sm-7 col-md-7">
                                        <div class="project-info">
                                            <h3><a href="'.$arr['link_url'].'"'.$target.'>'.$arr['title'].'</a></h3>
                                            <div class="project-info-inner">                                               
                                                <strong>Status :</strong> '.$event_status.' <br>
                                                <strong>Place :</strong> '.$arr['place'].'<br>
                                                <strong>Year :</strong> '.(DATE("M Y", strtotime($arr['from_date'])).' - '.DATE("M Y", strtotime($arr['to_date']))).'<br>
                                                <strong>Supported By :</strong> '.$arr['supported_by'].' <br>
                                                <strong>Funded By :</strong> '.$arr['funded_by'].' <br>
                                            </div>
                                            <a href="'.$arr['link_url'].'"'.$target.' class="btn btn-primary btn-sm">'.$arr['link_name'].'</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
				';
			}
			return $projects_html;
		}
		return NULL;
	}
	function do_Projects_short_codes($content) {
		$matches=$this->get_Projects_short_codes($content);
		$i=0;
		foreach($matches[0] as $shortCode) {
			$projects=$this->getProjects();
			$content = str_replace($matches[0][$i], $projects, $content);
			$i++;
		}
		return $content;
	}
	/* /Projects */
	
	/* Media */
	function get_Media_short_codes($content) {
		$regex = "/(\[media\])|(\<p\>\[media\]\<\/p\>)/";
		preg_match_all($regex, $content, $matches);
		return $matches;
	}
	function getMedia() {
		$media_arr=$this->db->getMultipleRec("SELECT * FROM ".$this->db->TB_media." WHERE status=1 ORDER BY sort_order ASC");
		if(is_array($media_arr) && count($media_arr)>0) {
			$media_html='';
			foreach($media_arr as $key=>$arr) {
                $target=((isset($arr['external_link']) && $arr['external_link']==1)?' target="_blank"':'');
				$media_html.='
                                <a href="'.(SITE_PATH.$this->pathObj->media_library_files_path.$arr['featured_img']).'">
                                    <img src="'.(SITE_PATH.$this->pathObj->media_library_files_path.$arr['featured_img']).'" alt="'.$arr['title'].'">
                                    <span class="press-title">'.$arr['title'].'</span>
                                </a>
				';
			}
			return ($media_html!='')?('<div class="press-list">'.$media_html.'</div>'):'';
		}
		return NULL;
	}
	function do_Media_short_codes($content) {
		$matches=$this->get_Media_short_codes($content);
		$i=0;
		foreach($matches[0] as $shortCode) {
			$media=$this->getMedia();
			$content = str_replace($matches[0][$i], $media, $content);
			$i++;
		}
		return $content;
	}
	/* /Media */

	/* Events */
	function getEvents() {
		$WHERE='';
		$WHERE_ARRAY=array();
	
		if(isset($_POST['searchtext'])) {
			$searchtext=addslashes(trim($_POST['searchtext']));
			if(isset($_REQUEST['searchtext']) && $searchtext!='')	{
				$WHERE_ARRAY[]="(title LIKE '%$searchtext%' OR location LIKE '%$searchtext%' OR description LIKE '%$searchtext%')";
			}
		}
		if(isset($_REQUEST['date'])) {
			$date=date('Y-m-d',$_REQUEST['date']);
	
			if($date!='')	{
				$WHERE_ARRAY[]="date='$date'";
			}
		} else {
		//	$WHERE_ARRAY[]="date=".date("Y-m-d");
		}
		if(is_array($WHERE_ARRAY) && count($WHERE_ARRAY)>0)
			$WHERE=$this->utilityObj->array_implode_notnull($WHERE_ARRAY,'AND');
		
		//echo $WHERE;
	
		if($WHERE!='')
			$WHERE=" AND $WHERE";
	
		$sql="SELECT * FROM ".$this->db->TB_event." WHERE status=1 AND date>=CURDATE() $WHERE ORDER BY date ASC";
		//echo $sql;
	
		$query=mysqli_query($this->db->conn, $sql);
		$num_sql=@mysqli_num_rows($query);
	
		$eventsCount=0;
		$eventRow='';
		if($num_sql>0)	{
            $eventRow.='<ul class="event-list">';
			while($fetch=mysqli_fetch_array($query))		{
				extract($fetch);
				
				$description=stripslashes(html_entity_decode($description));
				$description=(($description==strip_tags($description))?('<p>'.$description.'</p>'):$description);
	
				$eventRow.='
								<li class="event-row">
                                    <a class="event-popup" href="'.SITE_PATH.'event-info.php?id='.$event_id.'">'.$title.'</a>
                                    <p>'.DATE("l, F d, Y", strtotime($date)).'</p>
                                    <p class="ev-time">'.$time.'</p>
                                    '.$this->utilityObj->truncateChar($description, 250).'
								</li>
							';
				$eventsCount++;
			}
            $eventRow.='</ul>
			';
		}
		$eventRow.='
						<p class="ev-count">Total Events : <span>'.$eventsCount.'</span></p>
		';
		return $eventRow;
	}
	/* /Events */
	
	/* Events Grid */
	function getEventsGrid() {
		$year=(isset($_POST['year']) && $_POST['year']!='')?$_POST['year']:date("Y");
		$month=(isset($_POST['month']) && $_POST['month']!='')?$_POST['month']:date("n");
		
	//	return $year.' '.$month;
		$firstDate = $year.'-'.$month.'-01';
		$etime = strtotime($firstDate);
		$monthStart=date("N", $etime);

		$k=1;$l=1;
		$rows='';
		for($i=1;$i<=6,$k<=date("t", $etime);$i++) {
			$rows.='<tr>';
			for($j=1;$j<=7;$j++) {
				$rows.='<td'.((date($year."-".$month."-".$k)==date("Y-n-j"))?' class="current"':'').'>';
				if($l<$monthStart) {
					$rows.='</td>';
				} else if($k<=date("t", $etime)) {
					$rows.='<span class="date">'.$k.'</span>';
					
					$WHERE=" AND date='".date("Y-m-d", strtotime($year.'-'.$month.'-'.$k))."'";
					$sql="SELECT * FROM ".$this->db->TB_event." WHERE status=1 AND date>=CURDATE() $WHERE ORDER BY date ASC";
					//$rows.=$sql;
				
					$query=mysqli_query($this->db->conn, $sql);
					$num_sql=@mysqli_num_rows($query);
					
					if($num_sql>0)	{
						$i=0;
						while($fetch=mysqli_fetch_array($query)) {
							extract($fetch);

							$rows.='<p'.(($i==0)?' class="first"':'').'><a class="event-popup" href="'.SITE_PATH.'event-info.php?id='.$event_id.'">'.$title.'</a>'.$time.'</p>';
							$i++;
						}
					}
					$rows.='</td>';
					$k++;
				} else {
					$rows.='</td>';
				}
				$l++;
			}
			$rows.='</tr>';
		}
		return $rows;
	}
	/* /Events Grid */
}
$pageInfoObj = new PageInfo;