<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage Members </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>Members (<?php echo $client_count?>)
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<?php
									echo $this->Html->link(
										'Add New Member <i class="fa fa-plus"></i>', [
											'controller' => 'Users', 'action' => 'add', 'prefix' => 'backoffice'
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
							<th>Username</th>
							<th>Coach request</th>
							<th>Coach First Name</th>
							<th>Coach Last Name</th>
							<th>Status</th>
							<th>Profile</th>
							<th>Password</th>
							<th>Account</th>
							<th>Phone</th>
							<th>E-Mail</th>
							<!--
							<th>Gender</th>
							<th>Goal Weight</th>
							<th>Paid</th>
							<th>Leaderboard opt-out</th>
							<th>Is verified</th>
							<th>Date of Birth</th>
							-->
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
    	var cancel_btn_html = '<a href="javascript:;" rel="3" class="btn btn-sm purple"><span class="glyphicon glyphicon-trash"></span> Delete</a>';
    	var dtttable = $('#clientsList').DataTable( {
    		"bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $this->Url->build(array('controller' => 'Users', 'action' => 'ajaxClients')); ?>",
            "columnDefs": [
            	{"targets": -5,"data": null,"defaultContent": edit_btn_html},
            	{"targets": -4,"data": null,"defaultContent": reset_btn_html},
            	{"targets": -3,"data": null,"defaultContent": cancel_btn_html},
            	{"targets": -2,"data": 8},
            	{"targets": -1,"data": 9}
        	],

		    "aoColumns": [
		      null,
		      null,
		      null,
		      null,
		      { 
		      	"render": function (val, type, row) {
                    return val == 0 ? "No" : "Yes";
                }
              },
              { "bSearchable": false, "bSortable": false },
		      { "bSearchable": false, "bSortable": false },
		      { 
		      	"render": function (val, type, row) {
                    return val == 0 ? "No" : "Yes";
                }
              },
		      { "bSearchable": false , "bSortable": false },
		      null,
		      null,
		      null,
		      null,
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

	    $('#clientsList tbody').on( 'click', 'td', function () {
			if(!$(this).find('a').length){
				var rel = $(this).parent('tr').attr('rel');
	    	    var data = dtttable.row( $(this).parent('tr') ).data();
	        	window.location = "<?= $this->Url->build(['controller' => 'users', 'action' => 'edit', 'prefix' => 'backoffice']); ?>/"+data[0];
	    	}
		});
	    
	    
    });
</script>