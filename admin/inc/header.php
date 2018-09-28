<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

$current_page_file=$pathObj->filename;
$active_menu_arr=array();
$leaf_menu_rec=$db->getSingleRec($db->TB_admin_menu,"active_page LIKE '%$current_page_file%' and menu_status=1");
if($leaf_menu_rec==TRUE) {
	if($leaf_menu_rec['menu_id']>0) {
		$active_menu_arr[0]=$leaf_menu_rec['menu_id'];
	}
	if($leaf_menu_rec['parent_menu_id']>0) {
		$first_level_parent_rec=$db->getSingleRec($db->TB_admin_menu,"menu_id=".$leaf_menu_rec['parent_menu_id']." and menu_status=1");
	}
}
if(isset($first_level_parent_rec) && $first_level_parent_rec==TRUE) {
	if($first_level_parent_rec['menu_id']>0) {
		$active_menu_arr[1]=$first_level_parent_rec['menu_id'];
	}
	if($first_level_parent_rec['parent_menu_id']>0) {
		$second_level_parent_rec=$db->getSingleRec($db->TB_admin_menu,"menu_id=".$first_level_parent_rec['parent_menu_id']." and menu_status=1");
	}
}
if(isset($second_level_parent_rec) && $second_level_parent_rec==TRUE) {
	if($second_level_parent_rec['menu_id']>0) {
		$active_menu_arr[2]=$second_level_parent_rec['menu_id'];
	}
}
$user_modules_list='';

if(isset($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']) && is_array($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']) && is_array($active_menu_arr)) {
	$user_modules_arr=array_intersect($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST'],$active_menu_arr);
	$user_modules_list=implode(',',$_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']);
	if(count($user_modules_arr)<1) {
		$utilityObj->headerLocation(ADMIN_PATH."index.php");
	}
} else if(isset($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']) && ($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']=='' || !is_array($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST'])) && $active_menu_arr[0]!=1) {
	$utilityObj->headerLocation(ADMIN_PATH."index.php");
}
//printR($_SESSION);
//$utilityObj->printAny($active_menu_arr);
//printr($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
		<meta http-equiv="content-type" content="text/html;" charset="utf-8" />	
		<meta name="Robots" content="index/follow" />
		<meta name="Distribution" content="Global" />
		<meta name="ROBOTS" content="ALL" />
		<meta name="resource-type" content="Document" />
		<meta name="rating" content="General" />
		<meta name="revisit" content="7 days" />
        <link rel="shortcut icon" href="<?php echo ASSET_PATH.'images/'; ?>favicon.png">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Admin | <?php echo SITE_NAME_FULL; ?></title>
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PATH.'style/'; ?>style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PATH.'style/'; ?>responsive.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" />
        <link rel="stylesheet" media="screen" href="<?php echo ADMIN_PATH.'style/'; ?>jquery.classyscroll.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PATH.'style/'; ?>chosen.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo ADMIN_PATH.'style/'; ?>uniform.aristo.css" />
        <!--[if lte IE 7]>
            <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PATH.'style/'; ?>ie.css" />
        <![endif]-->
        <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption&subset=latin,latin-ext,cyrillic' rel='stylesheet' type='text/css'>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<script type="text/javascript">
			function changeData(obj){
				document.forms[0].submit();
			}
			
			$(document).ready(function(e) {
				$('.copyPrevText').click(function () {
					var copyText = $(this).prev().val();
					$(this).prev().select();
					document.execCommand('copy');
				});
				$('.copyText').click(function () {
					var copyText = $(this).val();
					$(this).select();
					document.execCommand('copy');
				});
				
                $(".select_all").click(function(e) {
                    $(this).select();
                });
				
				$(".delete_tr").click(function(e) {
                    $(this).closest("tr").remove();
					return false;
                });
				$(".delete_fieldset").click(function(e) {
                    $(this).closest("fieldset").remove();
					return false;
                });
            });
        </script>
<?php if(isset($address) && $address == 'address') { ?>
        <script type="text/javascript">
			$(function(){	
				if($(':input:checkbox[id=shw]').get(0).checked)
					$('#ship').show();
				else
			   		$('#ship').hide();
				$(':input:checkbox[id=shw]').click(function(){
					if(this.checked){
						$('#ship').slideDown(500);
						s=setInterval(function(){window.scrollBy(0,15);},10);
						setTimeout(function(){ clearTimeout(s); },500);
					} else
						$('#ship').slideUp(500);
				});
			});
			function validate(frm){
				pass=frm.password.value;
				cpass=frm.confirm_password.value;
				text='Please enter a valid ';
				option='Please select a valid ';
				errors='';
				$('#server').hide();
					
				if($.trim(pass).length!=0){
					if(pass!=cpass)
						 errors+='<li>Password and Confirm Password doesn\'t match </li>';
				}
				$(':input[msg],:select[msg]').each(function(){
					msg=$(this).attr('msg');
					
					$(this).val($.trim($(this).val()));
					 
					if(!$.trim($(this).val()).length){
						if(this.type=='text')
							errors+='<li>'+text+msg+'</li>';
						else if(this.type=='password') 
							errors+='<li>'+text+msg+'</li>';
						else if(this.type=='select-one') 
							errors+='<li>'+option+msg+'</li>';
					}
				});
				if(errors.length>0){
					$('#errorInner').html(errors);
					$('#record').hide();
					$('#error,#errorInner').fadeIn(500);
					return false;
				} else {
					$('#error,#errorInner').hide();
					return true;
				}
			}
			state='';
			function changeState(obj,name,elm) {
				if(obj.value!=225){
					 state=($('#'+elm+'').html());
					$('#'+elm+'').html('<input type="text" name="'+name+'" class="c" />');
				} else if(obj.value==225) {
					$('#'+elm+'').html(state);
				}
			}
		</script>
<?php } ?>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript"></script>
		<script type="text/javascript">//&lt;![CDATA[ 
			$(document).ready(function () {
				/*
				* For some reason '.tasks-list tr' doesn't work. I have to use
				* either '&gt; *' or .children() - in case you were wondering
				*/
				$('#table > tbody').sortable({
					helper: fixHelper,
					axis: 'y',
					start: function(event, ui) {
						if(ui.helper.hasClass('second-level')){
							ui.placeholder.removeClass('placeholder');
							ui.placeholder.addClass('placeholder-sub');
						}
						else{ 
							ui.placeholder.removeClass('placeholder-sub');
							ui.placeholder.addClass('placeholder');
						}
					},
					sort: function(event, ui) {
						var pos;
						if(ui.helper.hasClass('second-level')){
							pos = ui.position.left+20; 
							$('#cursor').text(ui.position.left+20);
						}
						else{
							pos = ui.position.left; 
							$('#cursor').text(ui.position.left);    
						}
						if(pos >= 32 && !ui.helper.hasClass('second-level')){
							ui.placeholder.removeClass('placeholder');
							ui.placeholder.addClass('placeholder-sub');
							ui.helper.addClass('second-level');
						}
						else if(pos < 25 && ui.helper.hasClass('second-level')){
							ui.placeholder.removeClass('placeholder-sub');
							ui.placeholder.addClass('placeholder');
							ui.helper.removeClass('second-level');
						}
					}
				}).disableSelection();
				
				/*
				* This helper just prevents the columns from collapsing when
				* dragging the rows.
				*/
				var fixHelper = function(e, ui) {
					ui.children().each(function() {
						$(this).width($(this).width());
					});
					return ui;
				};
			});//]]&gt;
        </script>
<?php if(isset($res[4]) && $res[4]!='') { ?>
		<script type="text/javascript">
			$(document).ready(function(e) {
				$(".go_to_page_form").submit(function(e) {
					var page_no=$(this).find(".go_to_page_input").val();
					page_no=(page_no>=1 && page_no<=<?php echo $res[4]; ?>)?page_no:((page_no><?php echo $res[4]; ?>)?<?php echo $res[4]; ?>:1);
                    var go_to_page="<?php echo $paginationObj->paginate_parameters(); ?>page="+page_no;
					$(this).attr("action", go_to_page);
					return true;
                });
            });
		</script>
<?php } ?>

<?php
if($active_menu_arr[0]==66) {
	include_once("westin_reg_jscript.php");
	if($status=='1') {
		include_once("signature_includes.php");
	}
}
if($active_menu_arr[0]==68) {
	include_once("waiver_reg_jscript.php");
}
?>

<?php if(isset($gen_pass) && $gen_pass==1) { ?>
		<script type="text/javascript">
			$(document).ready(function(e) {
				$(".gen_pass").click(function(e) {
					generate_password();
					return false;
				});
				$("#usePass").click(function(e) {
					var pass = $("#generated_pass").val();
					$("input[name='pass']").val(pass);
					$("input[name='confirm_pass']").val(pass);
					return false;
				});
			});
		</script>
<?php } ?>

<?php if(isset($editor) && $editor == 'editor') { ?>
        <script type="text/javascript" src="<?php echo ADMIN_PATH; ?>ckeditor/ckeditor.js"></script>
        <script type="text/javascript">
			window.onload = function()
			{		
				CKEDITOR.env.isCompatible = true;
				CKEDITOR.replace( 'editor1',
				{
					extraPlugins: 'imageuploader'
				});
			};
		</script>
<?php } ?>

<?php if((isset($script) && ($script == 'colorbox' || $script == 'datecombo')) || (isset($colorbox) && $colorbox == 'colorbox')) { ?>
		<link rel="stylesheet" href="<?php echo ADMIN_PATH.'style/'; ?>colorbox.css" />
		<script src="<?php echo ADMIN_PATH.'js/'; ?>jquery.colorbox.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				//Examples of how to assign the ColorBox event to elements
				$(".group1").colorbox({rel:'group1'});
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
		</script>
<?php } ?>

<?php if(isset($slugedit) && $slugedit == 'slugedit') { ?>
		<script type="text/javascript">
			$(document).ready(function(){
				var showPass=false;
				$('#edit_slug').change(function(){
					showPass = ($('#edit_slug:checked').length>0);
					if (showPass){
						$('#slug_tag').hide();
						$('#slug').show();
						$("#edit_cancel a").html("Close");
					}else{
						$('#slug').hide();
						$('#slug_tag').show();
						$("#edit_cancel a").html("Edit");
					}
				});
			});
        </script>
<?php } ?>

<?php if(isset($accordions) && $accordions==1) { ?>
		<script type="text/javascript">
			$(document).ready(function(e) {
				$("#add_more_accordion").click(function(e) {
					var editor_count = ($("#accordion_main .ckeditor").length)+1;
					add_more_accordion(editor_count);
					return false;
				});
			});
		</script>
<?php } ?>
<?php if(isset($pdfs) && $pdfs==1) { ?>
		<script type="text/javascript">
			$(document).ready(function(e) {
				$("#add_more_pdf").click(function(e) {
					var new_pdf_no=($("input[name='pdf_title[]']").length);
					add_more_pdf(new_pdf_no);
					return false;
				});
			});
		</script>
<?php } ?>

<?php if((isset($script) && $script == 'datepicker') || (isset($datecombo) && $datecombo == 'datecombo')) { ?>
		<link href="<?php echo ADMIN_PATH.'js/'; ?>datepicker/datepicker.css" rel="stylesheet">
		<script src="<?php echo ADMIN_PATH.'js/'; ?>datepicker/bootstrap-datepicker.js"></script>
		<script>
			// When the document is ready
            $(document).ready(function () {
                $('.datepicker').datepicker({
					format:'dd-mm-yyyy',
					weekStart:1
				});
                $('.input-daterange').datepicker();
            });
		</script>
<?php } ?>

        <script type="text/javascript" src="<?php echo ADMIN_PATH.'js/'; ?>jquery.mousewheel.js"></script>
        <script type="text/javascript" src="<?php echo ADMIN_PATH.'js/'; ?>jquery.classyscroll.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.scrollbars').ClassyScroll();
            });
        </script>
        
        <script type="text/javascript" src="<?php echo ADMIN_PATH.'js/'; ?>bootstrap.min.js"></script> 
        <script type="text/javascript" src="<?php echo ADMIN_PATH.'js/'; ?>metisMenu.js"></script> 
        <script type="text/javascript">
            $(function () {
                $('#menu').metisMenu();
            });
        </script>
        
		<script type="text/javascript" src="<?php echo ADMIN_PATH.'js/'; ?>jquery.uniform.js"></script>
        <script type="text/javascript" src="<?php echo ADMIN_PATH.'js/'; ?>scripts.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function() {       
                // initiate layout and plugins
                App.init();
            });
        </script>
        
<?php if(isset($script) && ($script == 'combo' || $script == 'datecombo')) { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PATH.'js/'; ?>datepicker/combo/jquery.multiSelect.css" />
		<script src="<?php echo ADMIN_PATH.'js/'; ?>datepicker/combo/jquery-1.3.2.min.js" type="text/javascript"></script>
		<script src="<?php echo ADMIN_PATH.'js/'; ?>datepicker/combo/jquery.multiSelect.js" type="text/javascript"></script>
		<script type="text/javascript">
			var $m = jQuery.noConflict(true);
			$m( function() {
				// Options displayed in comma-separated list
				$m(".multi").multiSelect({ oneOrMoreSelected: '*' });
			});
		</script>
<?php } ?>

		<script type="text/javascript">
			function MenuChange(id){
				obj=(window.ActiveXObject)?(new ActiveXObject('Microsoft.xmlhttp')):(new XMLHttpRequest());
				document.prod_menuform.menu_name.options.length=0;
				document.prod_menuform.menu_name.options[0]=new Option('Please select the Page','');
				url='DD_change.php?menucatid='+id;
				obj.open('get',url,true);
				
				obj.onreadystatechange=function(){
					if(obj.readyState==4){
						//alert(obj.responseText);
                    	$("#imgloader").show();
						if(obj.responseText!='') {
							jsonobj=eval('('+obj.responseText+')');
							i=1;
							for(var json in jsonobj) {
								document.prod_menuform.menu_name.options[i]=new Option(jsonobj[json],json);
								i++;
							}
						} else {
							document.prod_menuform.menu_name.options.length=0;
							document.prod_menuform.menu_name.options[0]=new Option('Please select the Page','');
						}
						$("#menu_name").trigger("liszt:updated");
                        $("#imgloader").hide(300);
					}
				} 
				obj.send(null); 
			}
			function loadMenu(menuid) {
				catmenu=document.prod_menuform.category_menu.value;
				if(menuid!='' && catmenu!='') {
					window.location.href='page_content.php?menu='+menuid;
				}
			}
		</script>
	</head>
	<body>
<!-- start header -->
        <div id="topbar">
            <div class="wrap">
                <div class="top-lf">
					<div style="background:#283a97;width:97%;border-right: 1px solid #151b22;">
						<img style="height:75px;border:0;display:block;margin:0 auto;" src="<?php echo ADMIN_PATH.'images/logo/logo.png'; ?>" alt="<?php echo SITE_NAME; ?>" />
					</div>
                </div>	
                <div class="top-mid">
                
                    <ul>
                <?php if(isset($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']) && is_array($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']) && in_array(8, $_SESSION[ADMIN_SESSION]['USER_MODULE_LIST'])) { ?>
                        <li <?php if(isset($active_menu_arr[1]) && $active_menu_arr[1]=="8") { echo 'class="active"'; } ?> >
                            <a href="manage_media_library.php?init=1"><i aria-hidden="true" class="fa fa-film"></i> Media Library</a>
                        </li>
                <?php } ?>
                <?php if(isset($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']) && is_array($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']) && in_array(11, $_SESSION[ADMIN_SESSION]['USER_MODULE_LIST'])) { ?>
                        <li <?php if(isset($active_menu_arr[1]) && $active_menu_arr[1]=="11") { echo 'class="active"'; } ?> >
                            <a href="manage_page.php?init=1"><i aria-hidden="true" class="fa fa-file"></i> Pages</a>
                        </li>
                <?php } ?>
                <?php if(isset($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']) && is_array($_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']) && in_array(17, $_SESSION[ADMIN_SESSION]['USER_MODULE_LIST'])) { ?>
                        <li <?php if(isset($active_menu_arr[1]) && $active_menu_arr[1]=="17") { echo 'class="active"'; } ?> >
                            <a href="manage_post.php?init=1"><i aria-hidden="true" class="fa fa-thumb-tack"></i> Posts</a>
                        </li>
                <?php } ?>
                    </ul>
                </div>	
                <div class="top-rt">
                    <ul>
                        <li><i aria-hidden="true" class="fa fa-male"></i> Welcome <b><?php echo $_SESSION[ADMIN_SESSION]['full_name']; ?></b></li>
                        <li> <a href="login.php?logout=success"><i aria-hidden="true" class="fa fa-power-off"></i> Logout</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>
<!-- #end header -->
		<div class="wrap"> 