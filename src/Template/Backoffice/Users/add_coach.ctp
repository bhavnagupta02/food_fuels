<!-- BEGIN PAGE CONTENT-->
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>Add Coach
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<?=
		$this->Form->create($user,['class' => 'form-horizontal form_met_validate']);
		$this->Form->templates([
		    'label' => false
		]);
		echo $this->Form->hidden('group_id', ['value' => USERGROUPID]);
		?>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			You have some form errors. Please check below.
		</div>
			<div class="form-body">
				<div class="form-group">
					<label class="col-md-3 control-label">First Name</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->input(
									'first_name', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Last Name</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->input(
									'last_name', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Email Address</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
							<?php
								echo $this->Form->input(
									'email', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Password</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
							<?php
								echo $this->Form->input(
									'password', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Phone</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
							<?php
								echo $this->Form->input(
									'mobile', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Gender</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'gender', array(
										'options' => array(
											0 => 'Male', 1 => 'Female'
										),
										'class' => 'form-control',
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Date of Birth</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
							<?php
								echo $this->Form->input(
									'dob', array(
										'type' => 'text',
										'class' => 'form-control datepicker',
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Address</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-home"></i> </span>
							<?php
								echo $this->Form->input(
									'address', array(
										'rows' => 2,
										'class' => 'form-control',
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">City</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-home"></i> </span>
							<?php
								echo $this->Form->input(
									'city', array(
										'class' => 'form-control',
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Zip/Post Code</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-home"></i> </span>
							<?php
								echo $this->Form->input(
									'zipcode', array(
										'class' => 'form-control',
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Country</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'country_id', array(
										'class' => 'form-control',
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Status</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'status_id', array(
										'class' => 'form-control',
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