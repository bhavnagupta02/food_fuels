<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
<!-- BEGIN PAGE CONTENT-->
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>Edit Weight
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<?=
		$this->Form->create($user,['class' => 'form-horizontal form_met_validate']);
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
					<label class="col-md-3 control-label">Weight</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-home"></i> </span>
							<?php
								echo $this->Form->input(
									'weight', array(
										'class' => 'form-control',
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Date</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'weight_date', array(
										'class' => 'form-control datepicker',
										'type'	=>	'text',
										'value' => Date('m/d/Y',strtotime($user['weight_date']))
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
		</form>
		<!-- END FORM-->
	</div>
</div>
<script type="text/javascript">
    var base_url = '<?= BASE_URL ?>';
</script>
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