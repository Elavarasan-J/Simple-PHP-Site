<script type="text/javascript">
	function Ajax(keyName,keyVal,keyText,tableName,updateDD){
		$("#"+keyName+"_imgloader").show();
		$("#"+updateDD).find('option').remove().end().trigger('chosen:updated');
		url='jscript_phpfunctions.php';
		postdata='keyName='+keyName+'&keyVal='+keyVal+'&keyText='+keyText+'&tableName='+tableName;
		
		$.ajax({
			url: url,
			type: 'GET',
			data: postdata,
			success: function(resp) {
				if(resp=='' || resp=='undefined' || resp==null)	{
					$("#"+updateDD).find('option').remove().end().trigger('chosen:updated');
					$("#"+keyName+"_imgloader").hide();
					return false;
				} else	{
					$("#"+updateDD).find('option').remove().end().append(resp).trigger('chosen:updated');
					$("#"+keyName+"_imgloader").hide();
					return false;
				}
			}
		});
	}
	function updateSlug(value) {
		var slugVal = $("#slug").val();
		if(slugVal=='' || slugVal=='undefined' || slugVal==null) {
			var title = value;
			$("#slug_imgloader").show();
			url='jscript_phpfunctions.php';
			postdata='slugvalue='+encodeURIComponent(title);
			
			$.ajax({
				url: url,
				type: 'POST',
				data: postdata,
				success: function(resp) {
					if(resp=='' || resp=='undefined' || resp==null)	{
						$("#slug").val('');
						$("#slug_imgloader").hide();
						return false;
					} else	{
						$("#slug").val(resp);
						$("#slug_imgloader").hide();
						return false;
					}
				}
			});
		}
	}
	function updateSearchTerms() {
		var search_termsVal = $("#search_terms").val();
		if(search_termsVal=='' || search_termsVal=='undefined' || search_termsVal==null) {
			var section_title = $("#section_id option:selected").text();
			var category_title = $("#category_id option:selected").text();
			var subcategory_title = $("#subcategory_id option:selected").text();
			var title = $("#title").val();
			$("#search_terms_imgloader").show();
			url='jscript_phpfunctions.php';
			postdata='search_termsvalue='+encodeURIComponent(section_title+','+category_title+','+subcategory_title+','+title);
			
			$.ajax({
				url: url,
				type: 'POST',
				data: postdata,
				success: function(resp) {
					if(resp=='' || resp=='undefined' || resp==null)	{
						$("#search_terms").val('');
						$("#search_terms_imgloader").hide();
						return false;
					} else	{
						$("#search_terms").val(resp);
						$("#search_terms_imgloader").hide();
						return false;
					}
				}
			});
		}
	}
	function generate_password() {
		$("#gen_pass_imgloader").show();
		url='<?php echo ADMIN_PATH; ?>jscript_phpfunctions.php';
		postdata='genPass=1';
		$.ajax({
			url: url,
			type: 'POST',
			data: postdata,
			success: function(resp) {
				if(resp=='' || resp=='undefined' || resp==null)	{
					$("#generated_pass").val('');
					$("#gen_pass_imgloader").hide();
					return false;
				} else	{
					$("#generated_pass").val(resp);
					$("#gen_pass_imgloader").hide();
					return false;
				}
			}
		});
	}
	function add_more_accordion(id) {
		$("#accorion_imgloader").show();
		url='jscript_phpfunctions.php';
		postdata='addMoreAccordion=1&editor_id='+id;
		$.ajax({
			url: url,
			type: 'POST',
			data: postdata,
			datatype: 'json',
			success: function(resp) {
				if(resp!='') {
					$("#accordion_main").append(resp);
					$("#accorion_imgloader").hide();
					CKEDITOR.replace('editor_'+id);
					
					var config = {
					  '.chosen'           : {},
					  '.chosen-select-deselect'  : {allow_single_deselect:true},
					  '.chosen-select-no-single' : {disable_search_threshold:10},
					  '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
					  '.chosen-select-width'     : {width:"95%"}
					}
					for (var selector in config) {
					  $(selector).chosen(config[selector]);
					}
					
					return false;
				}
			}
		});
	}
	function add_more_pdf(new_pdf_no) {
		$("#pdf_imgloader").show();
		url='jscript_phpfunctions.php';
		var new_pdf_no_var=(new_pdf_no!='' && new_pdf_no!=undefined)?('&new_pdf_no='+new_pdf_no):'';
		postdata='addMorePDF=1'+new_pdf_no_var;
		$.ajax({
			url: url,
			type: 'POST',
			data: postdata,
			success: function(resp) {
				if(resp!='') {
					$("#pdf_main").append(resp);
					
					var config = {
					  '.chosen'           : {},
					  '.chosen-select-deselect'  : {allow_single_deselect:true},
					  '.chosen-select-no-single' : {disable_search_threshold:10},
					  '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
					  '.chosen-select-width'     : {width:"95%"}
					}
					for (var selector in config) {
					  $(selector).chosen(config[selector]);
					}
					
					$(".delete_tr").click(function(e) {
						$(this).closest("tr").remove();
						return false;
					});
					$("#pdf_imgloader").hide();
					return false;
				}
			}
		});
	}

	function useThis($m) {
		$('.useThis').click(function () {
			var src = $(this).attr("data-src");
			var name = $(this).attr("data-name");
			var title = $(this).attr("data-title");
			var type = $(this).attr("data-type");
			var field_name = $(this).attr("data-input");
		//	console.log($m);
			if($m.closest(".media-wrap").children(".media-file").length) {
				$m.closest(".media-wrap").children(".media-file").remove();
			}
			if(type=='image') {
				$('<div class="form-group media-file"><div class="thumb_preview"><img src="'+src+'" alt="'+title+'"></div><input type="hidden" name="'+field_name+'" value="'+name+'"></div>').insertBefore($m.prev());
			} else if(type=='pdf') {
				$('<div class="form-group media-file"><div class="thumb_preview"><i class="fa fa-file-pdf-o fa-3x"></i>'+title+'</div><input type="hidden" name="'+field_name+'" value="'+name+'"></div>').insertBefore($m.prev());
			}
		});
	}
	function addThis($m) {
		$('.addThis').click(function () {
			var src = $(this).attr("data-src");
			var name = $(this).attr("data-name");
			var title = $(this).attr("data-title");
			var type = $(this).attr("data-type");
			var field_name = $(this).attr("data-input");
		//	console.log($m);
			if($m.closest(".media-wrap").children(".media-file").length) {
			//	$m.closest(".media-wrap").children(".media-file").remove();
			}
			if(type=='image') {
				$('<div class="thumb_preview" style="margin:20px 20px 0 0;"><div style="width:150px;height:150px;display:table-cell;vertical-align:middle;text-align:center;"><img src="'+src+'" alt="'+title+'"><input type="hidden" name="'+field_name+'[]" value="'+name+'"><a onclick="removeThis(this);" class="button1 red delete removeThis" title="remove"><i class="fa fa-remove"></i></a></div></div>').insertBefore($m);
			} else if(type=='pdf') {
				$('<div class="thumb_preview" style="margin:20px 20px 0 0;"><div style="width:150px;height:150px;display:table-cell;vertical-align:middle;text-align:center;"><i class="fa fa-file-pdf-o fa-3x"></i>'+title+'<input type="hidden" name="'+field_name+'[]" value="'+name+'"><a onclick="removeThis(this);" class="button1 red delete removeThis" title="remove"><i class="fa fa-remove"></i></a></div></div>').insertBefore($m);
			}
		});
	}
	function insertThis() {
		$('.insertThis').click(function () {
			var src = $(this).attr("data-src");
			var name = $(this).attr("data-name");
			var title = $(this).attr("data-title");
			var type = $(this).attr("data-type");
			var field_name = $(this).attr("data-input");
		//	console.log(field_name);
			if(type=='image') {
				CKEDITOR.instances[field_name].insertHtml('<img src="<?php echo SITE_PATH.$pathObj->media_library_files_path; ?>'+name+'" alt="'+title+'"/>');
			} else if(type=='pdf') {
				CKEDITOR.instances[field_name].insertHtml('<a href="'+src+'" target="_blank">'+title+'</a>');
			}
		});
	}
	$('.modal').on('shown.bs.modal', function () {
		var $m=$(this);
		$('.copyPrevText').click(function () {
			var $cp=$(this);
			var copyText = $cp.prev().val();
			$cp.prev().select();
			document.execCommand('copy');
			$m.prev().prev().attr("src",copyText).removeClass("hidden");
		});
		$('.copyText').click(function () {
			var $c=$(this);
			var copyText = $c.val();
			$c.select();
			document.execCommand('copy');
			$m.prev().attr("src",copyText).removeClass("hidden");
		});
		useThis($m);
		addThis($m);
		insertThis();
		$(this).off('shown.bs.modal');
	});

	function removeThis($this) {
		confirm('Are you sure that you want to remove?');
		$this.closest(".thumb_preview").remove();
		return false;
	}
</script>