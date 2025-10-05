<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage Coaches </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>Coaches (<?php echo $trainer_count?>)
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<?php
									echo $this->Html->link(
										'Add New Coach <i class="fa fa-plus"></i>', [
											'controller' => 'Users', 'action' => 'add_coach', 'prefix' => 'backoffice'
										], ['escape' => false, 'class' => 'btn green']
										);
								?>
							</div>
						</div>
					</div>
				</div>
				<table class="table table-striped table-hover table-bordered" id="clientsList">
					<thead>
						<tr>
							<th>User ID</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>E-Mail</th>
							<th>Created</th>
							<th> Profile </th>
							<th> Password </th>
							<th> Account </th>
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
    	var reset_btn_html = '<a href="javascript:;" rel="2" class="btn btn-sm purple"><span class="glyphicon glyphicon-link"></span> Reset</a>';
    	var cancel_btn_html = '<a href="javascript:;" rel="3" class="btn btn-sm purple"><span class="glyphicon glyphicon-trash"></span> Delete </a>';
    	var dtttable = $('#clientsList').DataTable( {
    		"bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $this->Url->build(array('controller' => 'Users', 'action' => 'ajaxTrainers')); ?>",
            "columnDefs": [
            	{"targets": -3,"data": null,"defaultContent": edit_btn_html},
            	{"targets": -2,"data": null,"defaultContent": reset_btn_html},
            	{"targets": -1,"data": null,"defaultContent": cancel_btn_html}
        	],
		    "aoColumns": [
		      { "bSearchable": false },
		      null,
		      null,
		      { "bSearchable": false },
		      { "bSearchable": false },
		      { "bSearchable": false },
		      { "bSearchable": false },
		      { "bSearchable": false },
		    ]
	    });
		$('#clientsList tbody').on( 'click', 'a', function () {
	        var rel = $(this).attr('rel');
	        var data = dtttable.row( $(this).parents('tr') ).data();
	        if(rel == 1){
	        	window.location = "<?= $this->Url->build(['controller' => 'users', 'action' => 'edit', 'prefix' => 'backoffice']); ?>/"+data[0];
	        }
	        else if(rel == 2){
	        	window.location = "<?= $this->Url->build(['controller' => 'users', 'action' => 'change_password', 'prefix' => 'backoffice']); ?>/"+data[0];
	        }
	        else if(rel == 3){
	        	if(confirm('Are you sure want to delete?')){
	        		window.location = "<?= $this->Url->build(['controller' => 'users', 'action' => 'delete', 'prefix' => 'backoffice']); ?>/"+data[0];
	        	}
	        }
	    } );
	    
	    
    });
</script>