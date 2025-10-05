<!-- BEGIN PAGE CONTENT-->
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>Add Shopping List
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<?=
		$this->Form->create($shoppinglist,['class' => 'form-horizontal form_met_validate', 'enctype' => 'multipart/form-data']);
		$this->Form->templates([
		    'label' => false
		]);
		?>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			You have some form errors. Please check below.
		</div>
			<div class="form-body">
				<div class="form-group">
					<label class="col-md-3 control-label">Upload Document</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-document"></i> </span>
							<?php
								echo $this->Form->input(
									'document_name', array(
										'class' => 'form-control',
										'type'	=>	'file',
										'required'
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Week Number</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'week_no', array(
										'class' => 'form-control',
										'min' => 1,
										'max' => 20,
										'required'
									)
								);
								echo "Note: If shopping list with same week number is already exists then old one is replaced with new one."
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