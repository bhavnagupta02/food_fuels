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
		$this->Form->create($Subscriptions,['class' => 'form-horizontal form_met_validate']);
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
					<label class="col-md-3 control-label">Subscription Name</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->input(
									's_name', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Days</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->input(
									'days', array(
										'class' => 'form-control', 'type' => 'number' , 'min' => '0' ,'max' => '500'
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Amount</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
							<?php
								echo $this->Form->input(
									'amount', array(
										'class' => 'form-control', 'type' => 'number'
										
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
							<span class="input-group-addon"> <i class="fa fa-envelope"></i> </span>
							<?php
								echo $this->Form->input(
									'description', array(
										'class' => 'form-control', 'type' => 'textarea'
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
