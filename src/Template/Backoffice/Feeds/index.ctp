<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage Feeds </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>Feeds (<?php echo $templ_count?>)
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
							</div>
						</div>
					</div>
				</div>
				<table class="table table-striped table-hover table-bordered" id="clientsList">
					<thead>
						<tr>
							<th>ID</th>
							<th>Title</th>
							<th>User First Name</th>
							<th>User Last Name</th>
							<th>Type</th>
							<th>Comments</th>
							<th>Likes</th>
							<th>Shares</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
    	var delete_btn_html='<a href="javascript:;" rel="2" class="btn btn-sm purple"><span class="glyphicon glyphicon-trash"></span>Delete</a>';
    	
    	var dtttable = $('#clientsList').DataTable({
    		"bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $this->Url->build(array('controller' => 'feeds', 'action' => 'ajaxTemplates')); ?>",
            "columnDefs": [
            	{"targets": -1, "data": null, "defaultContent": delete_btn_html },
        	],
		    "aoColumns": [
		      { "bSearchable": false },
		      null,
		      null,
		      { "bSearchable": false },
		      { 
		      	"render": function (val, type, row) {
                    if(val == 1)
                    	return "Posted a new photo";
                    else if(val == 2)
                    	return "Posted a new video";
                    else if(val == 3)
                    	return "Shared a recipe";
                    else if(val == 4)
                    	return "Shared a post";
                    else
                    	return "";

                }
              },
		      { "bSearchable": false },
		      { "bSearchable": false },
		      { "bSearchable": false },
		      { "bSearchable": false, "bSortable":false },
		    ]
	    });

	    $('#clientsList tbody').on( 'click', 'a', function () {
			var rel = $(this).attr('rel');
	        var data = dtttable.row( $(this).parents('tr') ).data();
	        if(rel == 1){
	        	window.location = "<?= $this->Url->build(['controller' => 'feeds', 'action' => 'edit', 'prefix' => 'backoffice']); ?>/"+data[0];
	        }
	        else if(rel == 2){
	        	if(confirm('Are you sure want to delete?')){
	        		window.location = "<?= $this->Url->build(['controller' => 'feeds', 'action' => 'delete', 'prefix' => 'backoffice']); ?>/"+data[0];
	        	}
	        }
	    });

		$('#clientsList tbody').on( 'click', 'a', function () {
	        var data = dtttable.row( $(this).parents('tr') ).data();
	        window.location = edit_url + '/' + data[0]; 
	    });
	});
</script>