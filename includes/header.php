<?php defined('BASE_PATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo $seoTitle; ?> | <?php echo SITE_NAME_FULL; ?></title>
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="<?php echo ASSET_PATH; ?>images/favicon.png" type="image/png">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Itim">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700">
        <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>style/bootstrap.css">
        <link rel="stylesheet" href="<?php echo ASSET_PATH; ?>style/style.css">
		<link rel="stylesheet" href="<?php echo ASSET_PATH; ?>style/responsive.css">
    </head>
   <body> 
         
         <div class="backdrop"></div>
         
         <a class="back-to-top">
            <i data-feather="chevron-up"></i>
         </a>
	    
        <!--Header-->
        <nav class="navbar navbar-default custom-navbar">
           <div class="container">
             <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-menu" aria-expanded="false">
                 <span class="sr-only">Toggle navigation</span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand" href="<?php echo SITE_PATH; ?>">
                  <img src="<?php echo ASSET_PATH; ?>images/logo.png" alt="Ann Care Foundation">
               </a>
             </div>

             <div class="collapse navbar-collapse" id="mobile-menu"> 
               <ul class="nav navbar-nav navbar-right">
				 <li<?php echo ($pageInfoObj->isHome())?' class="active"':''; ?>><a href="<?php echo SITE_PATH; ?>">Home</a></li>
                 <li class="<?php echo (($pageInfoObj->get_page_id()) == 1)?'active':''; ?>"><a href="<?php echo $pageInfoObj->get_the_page_link(1); ?>">About Us</a></li>
                 <li class="<?php echo (($pageInfoObj->get_page_id()) == 5)?'active':''; ?>"><a href="<?php echo $pageInfoObj->get_the_page_link(5); ?>">What We do</a></li>
                 <li class="<?php echo (($pageInfoObj->get_page_id()) == 14)?'active':''; ?>"><a href="<?php echo $pageInfoObj->get_the_page_link(14); ?>">Get Involved</a></li>
                 <li class="<?php echo (($pageInfoObj->get_page_id()) == 17 || ($pathObj->filename == 'post.php') || ($pathObj->filename == 'post_category.php'))?'active':''; ?>">
					 <a href="<?php echo $pageInfoObj->get_the_page_link(17); ?>">News</a>
				 </li>
			     <li class="<?php echo (($pageInfoObj->get_page_id()) == 15)?'active':''; ?>"><a href="<?php echo $pageInfoObj->get_the_page_link(15); ?>">Contact Us</a></li>
			     <li class="donate-button"><a href="<?php echo $pageInfoObj->get_the_page_link(16); ?>"><span class="btn btn-primary btn-donate-top outline">Donate Now</span></a></li>
               </ul>																																								
             </div>
           </div>
         </nav>
        <!--/Header-->