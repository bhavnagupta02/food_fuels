<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage Enquiries </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>Enquiries (<?php echo $contact_count?>)
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-hover table-bordered" id="enquiryList">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>E-Mail</th>
							<th>Phone</th>
							<th>Comments</th>
							<th>Created</th>
							<th>Account</th>
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
    	var cancel_btn_html = '<a href="javascript:;" rel="3" class="btn btn-sm purple"><span class="glyphicon glyphicon-trash"></span> Delete</a>';
    	var dtttable = $('#enquiryList').DataTable( {
    		"bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $this->Url->build(array('controller' => 'Enquiries', 'action' => 'ajaxContacts')); ?>",
            "columnDefs": [
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
		    ]
	    });
		$('#enquiryList tbody').on( 'click', 'a', function () {
			var rel = $(this).attr('rel');
	        var data = dtttable.row( $(this).parents('tr') ).data();
			
	        if(rel == 3){
	        	if(confirm('Are you sure want to delete?')){
	        		window.location = "<?= $this->Url->build(['controller' => 'Enquiries', 'action' => 'delete', 'prefix' => 'backoffice']); ?>/"+data[0];
	        	}
	        }
	    } );
	    
    });
</script>