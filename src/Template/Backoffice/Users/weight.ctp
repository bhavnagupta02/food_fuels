<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage User Weights </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>Total Weigth Entries (<?php echo $weight_count ?>)
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-hover table-bordered" id="clientsList">
					<thead>
						<tr>
							<th>User ID</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Weight</th>
							<th>Date</th>
							<th>Edit</th>
							<th>Delete</th>
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
    	var edit_btn_html = '<a href="javascript:;" rel="1" class="btn btn-sm purple"><span class="glyphicon glyphicon-pencil"></span> Edit</a>';
    	var cancel_btn_html = '<a href="javascript:;" rel="2" class="btn btn-sm purple"><span class="glyphicon glyphicon-trash"></span> Delete</a>';
    	
    	var dtttable = $('#clientsList').DataTable( {
    		"bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $this->Url->build(array('controller' => 'Users', 'action' => 'ajaxWeights')); ?>",
            "columnDefs": [
            	{"targets": -2,"data": null,"defaultContent": edit_btn_html},
            	{"targets": -1,"data": null,"defaultContent": cancel_btn_html},
            ],
            "aoColumns": [
		      { "bSearchable": false },
		      { "bSearchable": false },
		      { "bSearchable": false },
		      null,
		      { "bSearchable": false },
		      { "bSearchable": false },
		      { "bSearchable": false },
		    ]
	    });
		
	    $('#clientsList tbody').on( 'click', 'a', function () {
			var rel = $(this).attr('rel');
	        var data = dtttable.row( $(this).parents('tr') ).data();
	        if(rel == 1){
	        	window.location = "<?= $this->Url->build(['controller' => 'users', 'action' => 'edit_weight', 'prefix' => 'backoffice']); ?>/"+data[0];
	        }
	        else if(rel == 2){
	        	if(confirm('Are you sure want to delete?')){
	        		window.location = "<?= $this->Url->build(['controller' => 'users', 'action' => 'delete_weight', 'prefix' => 'backoffice']); ?>/"+data[0];
	        	}
	        }
	    });
    });
</script>