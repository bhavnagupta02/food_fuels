<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
<!-- BEGIN PAGE CONTENT-->
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>Edit Email Template
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<?=
		$this->Form->create($EmailTemplates,['class' => 'form-horizontal form_met_validate']);
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
					<label class="col-md-3 control-label">Subject</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->input(
									'subject', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">From Email</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->input(
									'from_email', array(
										'class' => 'form-control', 'type' => 'email'
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Reply To</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
							<?php
								echo $this->Form->input(
									'reply_to', array(
										'class' => 'form-control', 'type' => 'email'
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Email Content</label>
					<div class="col-md-8">
						The Following Tags will be replaed -<br>
						{{user_name}} = Name of the registered user<br>
						{{user_email}} = Email address of registerd user<br>
						{{activation_link}} = Account activation link with "click here" taxt<br>
						{{activation_url}} = Account activation URL<br>
						{{contact_link}} = Contact link with "click here" taxt<br>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
							<?php
								echo $this->Form->input(
									'content', array(
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