<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage Mealplans </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>Mealplans (<?php echo $meal_count?>)
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<?php
									echo $this->Html->link(
										'Add Day Plan <i class="fa fa-plus"></i>', [
											'controller' => 'DailyMealPlans', 'action' => 'add_plan', 'prefix' => 'backoffice'
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
							<th>ID</th>
							<th>Week Number</th>
							<th>Week Day</th>
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
    	var list_btn_html = '<a href="javascript:;" rel="1" class="btn btn-sm purple"><span class="glyphicon glyphicon-list"></span> List Meals</a>';
    	var edit_btn_html = '<a href="javascript:;" rel="2" class="btn btn-sm purple"><span class="glyphicon glyphicon-pencil"></span> Edit </a>';
    	var delete_btn_html = '<a href="javascript:;" rel="3" class="btn btn-sm purple"><span class="glyphicon glyphicon-pencil"></span> Delete </a>';
    	
    	var dtttable = $('#clientsList').DataTable( {
    		"bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $this->Url->build(array('controller' => 'DailyMealPlans', 'action' => 'ajaxDailyplans')); ?>",
            "columnDefs": [
            	{"targets": -1,"data": null,"defaultContent": edit_btn_html + list_btn_html + delete_btn_html},
            ],
		    "aoColumns": [
		      { "bSearchable": false },
		      { "bSearchable": false },
		      null,
		      { "bSearchable": false },
		    ]
	    });
		$('#clientsList tbody').on( 'click', 'a', function () {
	        var rel = $(this).attr('rel');
	        var data = dtttable.row( $(this).parents('tr') ).data();
	        if(rel == 1){
	        	window.location = "<?= $this->Url->build(['controller' => 'DailyMealPlans', 'action' => 'meals', 'prefix' => 'backoffice']); ?>/"+data[0];
	        }
	        else if(rel == 2){
	        	window.location = "<?= $this->Url->build(['controller' => 'DailyMealPlans', 'action' => 'edit_plan', 'prefix' => 'backoffice']); ?>/"+data[0];
	        }
	        else if(rel == 3){
	        	window.location = "<?= $this->Url->build(['controller' => 'DailyMealPlans', 'action' => 'delete', 'prefix' => 'backoffice']); ?>/"+data[0];
	        }
	    } );
	    
	    
    });
</script>