<?php 
	echo $this->Html->script('ckeditor/ckeditor.js'); 

?>
<!-- BEGIN PAGE CONTENT-->
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>Edit Meal Plan
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<?=
		$this->Form->create($plan,['class' => 'form-horizontal form_met_validate']);
		$this->Form->templates([
		    'label' => false
		]);
		?>
			<?php echo $this->Form->input('id'); ?>
			<div class="alert alert-danger display-hide">
				<button class="close" data-close="alert"></button>
				You have some form errors. Please check below.
			</div>
			<div class="form-body">
				<div class="form-group">
					<label class="col-md-3 control-label">Week Number</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'week_no', array(
										'class' => 'form-control',
										'options' => array_combine(range(1,12),range(1,12)),
										'required'
									)
								);
								echo "Note: If meal plan with same week number is already exists then old one is replaced with new one."
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Week Day</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'week_day', array(
										'class' => 'form-control',
										'options' => array_combine(range(1,7),range(1,7)),
										'required'
									)
								);
							?>
						</div>
					</div>
				</div>
				<!--
				<div class="form-group">
					<label class="col-md-3 control-label">From Date</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->text(
									'meal_date', array(
										'class' => 'form-control datepicker',
										'value' => date('m/d/Y',strtotime($plan['meal_date'])),
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">To Date</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->text(
									'to_date', array(
										'class' => 'form-control datepicker',
										'value' => date('m/d/Y',strtotime($plan['meal_date'])),
									)
								);
							?>
						</div>
					</div>
				</div>
				-->
				
				<div class="form-group">
					<label class="col-md-3 control-label">Text on Top</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
							<?php
								echo $this->Form->input(
									'text_highlight', array(
										'rows'=>'5',
										'class' => 'form-control ckeditor invalidateput', 'id' => 'content'
									)
								);
							?>
						</div>
					</div>
				</div>
					
				<div class="form-actions fluid">
					<div class="row">
						<div class="col-md-offset-3 col-md-9">
							<button type="submit" class="btn green">
								Submit
							</button>
						</div>
					</div>
				</div>
			</div>	
		</form>
		<!-- END FORM-->
	</div>
</div>
<script>
	$(document).ready(function (){
        //initialize datepicker
        $('.datepicker').datepicker({
            rtl: Metronic.isRTL(),
            autoclose: true
        });
        $('.datepicker .form-control').change(function() {
            form3.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
        })
	});
</script>
<script>
	$(document).ready(function (){
		$( "#datepicker" ).datepicker();
		$('.validate').validate();
	});
</script>