<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage Email Templates </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>Email Templates (<?php echo $templ_count?>)
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
							<th>Subject</th>
							<th>Slug</th>
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
    	var edit_btn_html = '<a href="javascript:;" class="btn btn-sm purple"><span class="glyphicon glyphicon-pencil"></span> Edit</a>';
    	var dtttable = $('#clientsList').DataTable( {
    		"bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $this->Url->build(array('controller' => 'email_templates', 'action' => 'ajaxTemplates')); ?>",
            "columnDefs": [
            	{"targets": -1,"data": null,"defaultContent": edit_btn_html},
        	],
		    "aoColumns": [
		      { "bSearchable": false },
		      null,
		      null,
		      { "bSearchable": false },
		    ]
	    });
	    var edit_url = '<?php echo $this->Url->build(array('controller' => 'email_templates', 'action' => 'edit'))?>';
		$('#clientsList tbody').on( 'click', 'a', function () {
	        var data = dtttable.row( $(this).parents('tr') ).data();
	        window.location = edit_url + '/' + data[0]; 
	    });
	});
</script>