<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
<!-- BEGIN PAGE CONTENT-->
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>Edit Recipe
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<?=
		$this->Form->create($Recipes,['class' => 'form-horizontal form_met_validate']);
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
					<label class="col-md-3 control-label">Title</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon">  </span>
							<?php
								echo $this->Form->input(
									'title', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Description</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> </span>
							<?php
								echo $this->Form->textarea(
									'description', array(
										'class' => 'form-control'
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Notes</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> </span>
							<?php
								echo $this->Form->input(
									'notes', array(
										'class' => 'form-control'
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Serving Size</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> </span>
							<?php
								echo $this->Form->input(
									'serving_size', array(
										'class' => 'form-control'
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Preparation time</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> </span>
							<?php
								echo $this->Form->input(
									'preparation_time', array(
										'class' => 'form-control'
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Ingredients</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> </span>
							<?php
								echo $this->Form->input(
									'ingredients', array(
										'class' => 'form-control ckeditor invalidateput', 'id' => 'content',
										'row' => 5
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Directions</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> </span>
							<?php
								echo $this->Form->input(
									'directions', array(
										'class' => 'form-control ckeditor invalidateput',
										'row' => 5
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