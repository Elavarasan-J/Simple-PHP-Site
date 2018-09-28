<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
?>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	
		<script src="<?php echo ADMIN_PATH.'js/'; ?>chosen.jquery.js" type="text/javascript"></script>
		<script type="text/javascript">
			var config = {
				'.chosen'					 : {},
				'.chosen-select-deselect'	: {allow_single_deselect:true},
				'.chosen-select-no-single' : {disable_search_threshold:10},
				'.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
				'.chosen-select-width'		 : {width:"95%"}
			}
			for (var selector in config) {
				$(selector).chosen(config[selector]);
			}

			$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
				var url = $(this).attr("href"); // the remote url for content
				var target = $(this).data("target"); // the target pane
				var tab = $(this); // this tab
				
				// ajax load from data-url
				$(target).load(url,function(result){      
					tab.tab('show');
				});
			});
		</script>
	</body>
</html>
