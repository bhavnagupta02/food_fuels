<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage Subscriber </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>Subscribers (<?php echo $sub_count?>)
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-hover table-bordered" id="enquiryList">
					<thead>
						<tr>
							<th>ID</th>
							<th>E-Mail</th>
							<th>Created</th>
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
    	var dtttable = $('#enquiryList').DataTable( {
    		"bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $this->Url->build(array('controller' => 'Enquiries', 'action' => 'ajaxSubscription')); ?>",
            "aoColumns": [
		      { "bSearchable": false },
		      { "bSearchable": false },
		      { "bSearchable": false },
		    ]
	    });

		$('#clientsList tbody').on( 'click', 'a', function () {
	        var data = dtttable.row( $(this).parents('tr') ).data();
	        console.log(data)
	    } );
	    
	    
    });
</script>