<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Pagination {
	function __construct() {

	}
	
	//	pagination (
	function paginate_parameters() {
		$url_parameters='';
		if(isset($_GET)) {
			$l=0;
			foreach($_GET as $key=>$val) {
				if($key!="init" && $key!="page") {
					$url_parameters.=(($l==0)?"?":"&").$key."=".$val;
					$l++;
				}
			}
		}
		if($url_parameters=='') {
			$url_parameters="?";
		} else {
			$url_parameters.="&";
		}
		return $url_parameters;
	}

	function paginate($totalrows,$offset=10,$first='&laquo;',$last='&raquo;',$pre='&lsaquo;',$nxt='&rsaquo;',$qs='',$className='active',$off=5){
		$prev='';$next='';$link='';
		$pages=ceil($totalrows/$offset); $link=''; $links=''; $limits=''; $jj='';
		
		$start=(isset($_REQUEST['page']) && ctype_digit($_REQUEST['page']))?(($_REQUEST['page']-1)*$offset):(0);
		
		$_REQUEST['page']=(isset($_REQUEST['page']) && $_REQUEST['page']<=$pages && $_REQUEST['page']>=1)?($_REQUEST['page']):((isset($_REQUEST['page']) && $_REQUEST['page']>$pages)?$pages:1);
		
		$diff=(($_REQUEST['page']%$off)==0)?($off):($_REQUEST['page']%$off);
		
		$s=($_REQUEST['page']-($diff-1) );
		$e=(($_REQUEST['page']+($off-$diff))>$pages)?($pages):(($_REQUEST['page']+($off-$diff)));
		
		for($i=$s;$i<=$e;$i++){
			$current=($i==$_REQUEST['page'])?('class="'.$className.'"'):('');
			$link.='<li '.$current.'><a href="'.$this->paginate_parameters().'page='.$i.$qs.'" >'.$i.'</a></li>';
		}
		
		if(1<$_REQUEST['page'])
			$prev='<li class="pag-first"><a href="'.$this->paginate_parameters().'page='.($_REQUEST['page']-1).$qs.'">'.$pre.'</a></li>';
		
		if(($pages-1)>=$_REQUEST['page'])
			$next='<li class="pag-last"><a href="'.$this->paginate_parameters().'page='.($_REQUEST['page']+1).$qs.'">'.$nxt.'</a></li>';
		
		$firstlink=($pages>$off && 1<$_REQUEST['page'] && $_REQUEST['page']>$off)?'<li class="pag-first"><a href="'.$this->paginate_parameters().'page=1'.$qs.'">'.$first.'</a></li>':'';
		
		$lastlink=(($pages-($pages%$off))>=$_REQUEST['page'] && $pages>$off)?'<li class="pag-last"><a href="'.$this->paginate_parameters().'page='.$pages.$qs.'">'.$last.'</a></li>':'';
		
		if($s>0 && $totalrows>$offset) {
			$links=$firstlink.$prev.$link.$next.$lastlink;
			$limits=' limit '.$start.','.$offset;
		}
		
		if( $totalrows>$offset)
			$jj=$start;
		
		return array($links,$limits,$jj,'&page='.$_REQUEST['page'],$pages,$totalrows);
	}
	function paginate_seo_parameters() {
		$url_parameters='';
		if(isset($_GET)) {
			$l=0;
			foreach($_GET as $key=>$val) {
				if($l==0 && ($key=="weekly_notice" || $key=="event")) {
					$url_parameters.=$key;
				} else if($key!="init" && $key!="page") {
					if($l==0) $url_parameters.=$key;
					$url_parameters.='/'.$val;
					$l++;
				}
			}
		}
		if($url_parameters=='') {
			$url_parameters="/";
		} else {
			$url_parameters.="/";
		}
		return SITE_PATH.$url_parameters;
	}
	function paginate_seo($totalrows,$offset=10,$first='&laquo;',$last='&raquo;',$pre='&lsaquo;',$nxt='&rsaquo;',$qs='',$className='active',$off=5){
		$pages=ceil($totalrows/$offset); $link=''; $links=''; $limits=''; $jj='';
		
		$start=(ctype_digit($_REQUEST['page']) )?(($_REQUEST['page']-1)*$offset):(0);
		
		$_REQUEST['page']=(isset($_REQUEST['page']) && $_REQUEST['page']<=$pages && $_REQUEST['page']>=1)?($_REQUEST['page']):((isset($_REQUEST['page']) && $_REQUEST['page']>$pages)?$pages:1);
		
		$diff=(($_REQUEST['page']%$off)==0)?($off):($_REQUEST['page']%$off);
		
		$s=($_REQUEST['page']-($diff-1) );
		$e=(($_REQUEST['page']+($off-$diff))>$pages)?($pages):(($_REQUEST['page']+($off-$diff)));
		
		for($i=$s;$i<=$e;$i++){
			$current=($i==$_REQUEST['page'])?('class="'.$className.'"'):('');
			$link.='<li '.$current.'><a href="'.$this->paginate_seo_parameters().$i.$qs.'" >'.$i.'</a></li>';
		}
		
		if(1<$_REQUEST['page'])
			$prev='<li class="pag-first"><a href="'.$this->paginate_seo_parameters().($_REQUEST['page']-1).$qs.'">'.$pre.'</a></li>';
		
		if(($pages-1)>=$_REQUEST['page'])
			$next='<li class="pag-last"><a href="'.$this->paginate_seo_parameters().($_REQUEST['page']+1).$qs.'">'.$nxt.'</a></li>';
		
		$firstlink=($pages>$off && 1<$_REQUEST['page'] && $_REQUEST['page']>$off)?'<li class="pag-first"><a href="'.$this->paginate_seo_parameters().'1'.$qs.'">'.$first.'</a></li>':'';
		
		$lastlink=(($pages-($pages%$off))>=$_REQUEST['page'] && $pages>$off)?'<li class="pag-last"><a href="'.$this->paginate_seo_parameters().$pages.$qs.'">'.$last.'</a></li>':'';
		
		if($s>0 && $totalrows>$offset) {
			$links=$firstlink.$prev.$link.$next.$lastlink;
			$limits=' limit '.$start.','.$offset;
		}
		
		if( $totalrows>$offset)
			$jj=$start;
		
		return array($links,$limits,$jj,$_REQUEST['page'],$pages,$totalrows);
	}
	function paginate_no_li($totalrows,$offset=10,$pre='&lsaquo;',$nxt='&rsaquo;',$qs='',$className='active',$off=5){
		$pages=ceil($totalrows/$offset);$link='';
		
		$start=(ctype_digit($_REQUEST['page']) )?(($_REQUEST['page']-1)*$offset):(0);
		
		$_REQUEST['page']=(isset($_REQUEST['page']) && $_REQUEST['page']<=$pages && $_REQUEST['page']>=1)?($_REQUEST['page']):((isset($_REQUEST['page']) && $_REQUEST['page']>$pages)?$pages:1);
		
		$diff=(($_REQUEST['page']%$off)==0)?($off):($_REQUEST['page']%$off);
		
		$s=($_REQUEST['page']-($diff-1) );
		$e=(($_REQUEST['page']+($off-$diff))>$pages)?($pages):(($_REQUEST['page']+($off-$diff)));
		
		for($i=$s;$i<=$e;$i++){
			$current=($i==$_REQUEST['page'])?('class="'.$className.'"'):('');
			$link.=(($i>1)?" | ":"").'<a href="'.$this->paginate_parameters().'page='.$i.$qs.'" '.$current.' >'.$i.'</a>';
		}
		
		if(1<$_REQUEST['page'])
			$prev='<a href="'.$this->paginate_parameters().'page='.($_REQUEST['page']-1).$qs.'">'.$pre.'</a>';
		
		if(($pages-1)>=$_REQUEST['page'])
			$next='<a href="'.$this->paginate_parameters().'page='.($_REQUEST['page']+1).$qs.'">'.$nxt.'</a>';
		
		if($s>0 && $totalrows>$offset) {
			$links=$firstlink.$prev.$link.$next.$lastlink;
			$limits=' limit '.$start.','.$offset;
		}
		
		if( $totalrows>$offset)
			$jj=$start;
		
		return array($links,$limits,$jj,'&page='.$_REQUEST['page'],$pages,$totalrows);
	}
	//	) pagination
}
$paginationObj = new Pagination;