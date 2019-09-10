<script type="text/javascript">
	$(document).ready(function() {
		var Tree=[];
		var getImportTree;
		var type;

		function init_scenary_wt(){
			type = $('#type').val();
			getImportTree = $.get("{{url('getAdvisorTree')}}", { type: type}, function (data) {
				Tree = data;
			});
			init_advisor();
		}

		function init() {
			getImportTree.done(function () {
				$('#treeDuplicateScenary').tree(Tree);
			});
		}
	});
</script>