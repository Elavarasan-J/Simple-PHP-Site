<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Path {
	function __construct() {
		if(ENVIRONMENT=='development') {
			$host="lh";		//	if(ip) [local_IP]; else localhost
			if($host=="ip") {
				$localhost = IP;
			} else {
				$localhost = "localhost:8080";
			}

			defined("DOMAIN") OR define("DOMAIN","$localhost/anncares/");
			defined("DOMAIN_NOWWW") OR define("DOMAIN_NOWWW","$localhost/anncares/");
		} else if(ENVIRONMENT=='testing') {
			defined("DOMAIN") OR define("DOMAIN","approvals.gtectsystems.com/anncares/");
			defined("DOMAIN_NOWWW") OR define("DOMAIN_NOWWW","approvals.gtectsystems.com/anncares/");
		} else if(ENVIRONMENT=='production') {
			defined("DOMAIN") OR define("DOMAIN","www.anncaresfoundation.org/");
			defined("DOMAIN_NOWWW") OR define("DOMAIN_NOWWW","anncaresfoundation.org/");
		}
		
		$this->http=(isset($_SERVER['HTTPS']) ? "https" : "http");
		$this->host=$_SERVER['HTTP_HOST'];

		$this->urlNOHTTP=$this->host.$_SERVER['REQUEST_URI'];
		$this->url="$this->http://".$this->urlNOHTTP;

		$this->pathNOHTTP=$this->host.$_SERVER['PHP_SELF'];
		$this->path="$this->http://".$this->pathNOHTTP;
		$this->filename=basename($this->path);

		$this->httpsarray=array();
		$this->httppath=(in_array($this->filename,$this->httpsarray))?'https':'http';

		defined("SITE_PATH") OR define("SITE_PATH",$this->httppath.'://'.DOMAIN);
		defined("ADMIN_PATH") OR define("ADMIN_PATH",SITE_PATH.'admin/');
		defined("PNF_PATH") OR define("PNF_PATH",SITE_PATH.'404/');
		defined("ASSET_PATH") OR define("ASSET_PATH",SITE_PATH.'assets/');

	//	################################################## Upload directories ##################################################
		//	Page
		$this->page_featured_img_path="assets/images/page/";
		$this->page_pdf_path="assets/images/page_pdfs/";
		
		//	Post
		$this->post_category_img_path="assets/images/post_category/";
		$this->post_img_path="assets/images/post/";
		
		//	Slider
		$this->slider_img_path="assets/images/slider/";
		
		//	Board Members
		$this->board_member_img_path="assets/images/board_member/";
		
		//	Featured
		$this->featured_promo_img_path='assets/images/featured_promos/';
		
		//	Media Library
		$this->media_library_files_path='assets/media_library/';
		$this->media_library_thumbphotos_path='assets/media_library/thumb/';
		$this->media_library_mediumphotos_path='assets/media_library/medium/';
		$this->media_library_path='assets/media_library/main/';
		
		//	Gallery
		$this->galleryphotos_path='assets/images/gallery/';
		$this->gallerythumbphotos_path='assets/images/gallery/thumb/';
		$this->gallerymediumphotos_path='assets/images/gallery/medium/';
		$this->gallery_path='assets/images/gallery/main/';
		
		//	Media (
		$this->media_path='assets/images/media/';
		$this->media_path_thumb='assets/images/media/thumb/';
		
		//	Events
		$this->event_img_path="assets/images/events/";
		
		//	Notifications
		$this->notification_img_path="assets/images/notifications/";
	//	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX Upload directories XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	}
}
$pathObj = new Path;
?>