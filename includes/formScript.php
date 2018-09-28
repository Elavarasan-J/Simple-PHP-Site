<script>
	$("#<?php echo $formName; ?>").submit(function( event ) {
		$('#submit').attr('disabled', true).html('PROCESSING...');
		var postData = $(this).serializeArray();
		
		postData.push({name:"formName", value:"<?php echo $formName; ?>"});
		
		$.post("<?php echo SITE_PATH; ?>forms.php", postData, function(data) {
			console.info(data)
			var response = $.parseJSON(data); 
			 
			if(response.message!='') {
				$('.alert').html(response.message);
			}
			if(response.status==0) {
				$('.alert').removeClass("hide alert-success").addClass("alert-danger");
				if(response.errorFields) {
					$(".form-control").closest(".form-group").removeClass("has-error");
					$("#recaptcha").closest(".form-group").removeClass("has-error");
					$.each(response.errorFields, function (key, id) {
						$("#"+id).closest(".form-group").addClass("has-error");
					});
				}
				$('#submit').attr('disabled', false).html("<?php echo $buttonName; ?>");
			} else if(response.status==1) {
				$('.alert').removeClass("hide alert-danger").addClass("alert-success");
				$(".form-control, .g-recaptcha").closest(".form-group").removeClass("has-error");
				$("#<?php echo $formName; ?>")[0].reset();
				if($('.g-recaptcha').length){
					grecaptcha.reset();	
				}
				$('#submit').attr('disabled', false).html("<?php echo $buttonName; ?>");
			} else {
				//	console.log(data);
				$('#submit').attr('disabled', false).html("<?php echo $buttonName; ?>");
			}
		});
		return false;
	});
</script>