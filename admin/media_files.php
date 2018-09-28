<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

	$type=((isset($_REQUEST['type']) && $_REQUEST['type']!='')?$_REQUEST['type']:'');
	if($type=='image') {
		$WHERE='WHERE file_type<>"application/pdf"';
	} else if($type=='pdf') {
		$WHERE='WHERE file_type="application/pdf"';
	} else {
		$WHERE='';
	}

	$sql="SELECT * FROM $db->TB_media_library $WHERE ORDER BY title ASC";
	$query=mysqli_query($db->conn, $sql);
	$rows=@mysqli_num_rows($query);
	
	if($rows>0) {
?>
															<div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="modalLabel1">Media Library</h4>
                                                                    </div>
                                                                    <div class="modal-body">
																		<div class="clearfix" style="height:400px;overflow:scroll;">
<?php
		while($fetch=mysqli_fetch_array($query)) {
			extract($fetch);
			$field_name=((isset($_REQUEST['field_name']) && $_REQUEST['field_name']!='')?$_REQUEST['field_name']:'');
			$file_class='';
			$fileClass=(isset($_REQUEST['field_class']) && $_REQUEST['field_class']!='')?$_REQUEST['field_class']:'';
			if($fileClass=='use') {
				$file_class=' useThis';
			} else if($fileClass=='add') {
				$file_class=' addThis';
			} else {
				$file_class=' insertThis';
			}

			$media_file=BASE_PATH.$pathObj->media_library_thumbphotos_path.$file_name;
			$media_path=SITE_PATH.$pathObj->media_library_thumbphotos_path.$file_name;
			$medium_path=SITE_PATH.$pathObj->media_library_mediumphotos_path.$file_name;
			$original_path=SITE_PATH.$pathObj->media_library_files_path.$file_name;
			if(isset($file_type) && $file_type!='' && ($file_type=='image/jpeg' || $file_type=='image/png' || $file_type=='image/gif')) {
				
				$thumb='';
				if(is_file($media_file)) {
					$thumb='<img src="'.$media_path.'" alt="" class="img-responsive'.$file_class.'" style="display:inline-block;max-height:150px;" data-src="'.$media_path.'" data-name="'.$file_name.'" data-title="'.$title.'" data-type="image" data-input="'.$field_name.'" />';
				}
			} else if(isset($file_type) && $file_type!='') {
				$file_ext=str_replace('application/','',$file_type);
				$fa_file_type='-'.$file_ext;
				$thumb='<i class="fa fa-file'.$fa_file_type.'-o fa-5x '.$file_class.'" data-src="'.$original_path.'" data-name="'.$file_name.'" data-title="'.$title.'" data-type="pdf" data-input="'.$field_name.'"></i>';
			}
			echo '<div class="col-xs-12 col-sm-6 col-md-4 media_file_wrap_out" style="background:#EEE;border:1px solid #FFF;display:table;"><div class="media_file_wrap_in" style="position:relative;display:table-cell;vertical-align:middle;height:152px;text-align:center;">'.$thumb.'<div style="position:absolute;width:100%;padding:10px 5px;bottom:0;background:rgba(0,0,0,0.5);color:#FFF;">'.$title.'</div></div></div>';
		}
?>
																		</div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        <!--<button type="button" class="btn btn-primary">Insert</button>-->
                                                                    </div>
                                                                </div>
                                                            </div>
<?php
	}
?>
