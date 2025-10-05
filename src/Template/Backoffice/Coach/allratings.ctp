<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage Coaches Rating </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>Reviews
				</div>
			</div>
			
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>S.No.</th>
							<th>User Name</th>
							<th>Coach Name</th>
							<th>Rating</th>
							<th>Review</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($reviews as $record){  ?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td><?php echo $record['user_details'][0]['first_name']." ".$record['user_details'][0]['last_name']; ?></td>
								<td><?php echo $record['coach_details'][0]['first_name']." ".$record['coach_details'][0]['last_name']; ?></td>
								<td><?php echo $record['rating']." Stars"; ?></td>
								<td><?php echo $record['reviews']; ?></td>
								<td><?php if($record['status']==0){ ?><a class="btn btn-sm purple" href="<?= $this->Url->build(['controller' => 'Coach', 'action' => 'allratings', 'prefix' => 'backoffice', 'qt'=>1,'id'=>$record['id']]); ?>">Publish</a><?php } else { ?>  <a class="btn btn-sm purple" href="<?= $this->Url->build(['controller' => 'Coach', 'action' => 'allratings', 'prefix' => 'backoffice','qt'=>0,'id'=>$record['id']]); ?>">Unpublish</a><?php } ?></td>
								<td><a class="btn btn-sm purple" href="<?= $this->Url->build(['controller' => 'Coach', 'action' => 'allratings', 'prefix' => 'backoffice', 'qt'=>'del','id'=>$record['id']]); ?>">Delete</a></td>
							</tr>
						<?php } ?>
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
    		"bProcessing": false,
            "bServerSide": false
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
    });
</script>

