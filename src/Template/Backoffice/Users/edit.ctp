<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
<!-- BEGIN PAGE CONTENT-->
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>Edit Member
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<?=
		$this->Form->create($user,['class' => 'form-horizontal form_met_validate']);
		$this->Form->templates([
		    'label' => false
		]);
		echo $this->Form->input('user_subscriptions_id',['type' => 'hidden']);
		
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
				<?php 
				if($user['group_id'] == CLIENTGROUPID){ ?>
				<?php
				if($user['end_date']!=''){
				 ?>
				<!-- 
				<div class="form-group">
					<label class="col-md-3 control-label">Subscription</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'subscription_id', array(
										'class' => 'form-control',
										'readonly'
									)
								);
							?>
						</div>
					</div>
				</div>
				--> 
				<div class="form-group">
					<label class="col-md-3 control-label">End Date</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'end_date', array(
										'class' => 'form-control datepicker',
										'type'	=>	'text',
										'value' => Date('m/d/Y',strtotime($user['end_date']))
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Registration Date</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'reg_date', array(
										'class' => 'form-control',
										'type'	=>	'text',
										'readonly'=>true,
										'value' => Date('m/d/Y',strtotime($user['created']))
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Payment Date</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'reg_date', array(
										'class' => 'form-control',
										'type'	=>	'text',
										'readonly'=>true,
										'value' => Date('m/d/Y',strtotime($user['paid_date']))
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Meal Start Date</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'start_date', array(
										'class' => 'form-control datepicker',
										'type'	=>	'text',
										'value' => Date('m/d/Y',strtotime($user['start_date']))
									)
								);
							?>
						</div>
					</div>
				</div>
				<?php
				}
				else{
				?>
				<div class="form-group">
					<label class="col-md-3 control-label">Subscription</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'subscription_id', array(
										'class' => 'form-control',
										'empty' => 'No change'
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Registration Date</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'reg_date', array(
										'class' => 'form-control',
										'type'	=>	'text',
										'readonly'=>true,
										'value' => Date('m/d/Y',strtotime($user['created']))
									)
								);
							?>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-3 control-label">End Date</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'end_date', array(
										'class' => 'form-control datepicker',
										'type'	=>	'text',
										
										'value' => Date('m/d/Y')
									)
								);
							?>
						</div>
					</div>
				</div>

				<?php	
				}
				?>
				

				<div class="form-group">
					<label class="col-md-3 control-label">Trainer</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								/*
								echo $this->Form->input(
									'trainer_name', array(
										'class' => 'form-control',
										'value' => (isset($user['trainer']['first_name']))?$user['trainer']['first_name']." ".$user['trainer']['last_name']:""
									)
								);
								*/
							?>
							<?= $this->Form->input('trainer_id',
									array(
										'empty' => 'No Change',
										'class' => 'form-control',
										)
								); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">After Image</label>
					<div class="col-md-4">
						<div class="input-group">
							<div class="figure">
								<?php
				                    $proImage = $this->Custom->getProfileImage($user->after_image,USER_AFTER);
				                    echo $this->Html->image($proImage);
				                ?>
				            </div>
				        </div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Before Image</label>
					<div class="col-md-4">
						<div class="input-group">
						    <div class="figure">
								<?php
				                    $proImage = $this->Custom->getProfileImage($user->before_image,USER_BEFORE);
				                    echo $this->Html->image($proImage);
				                ?>
				            </div>
				        </div>
					</div>
				</div>
				<?php }
				else if($user['group_id'] == USERGROUPID){ ?>
				<div class="form-group">
					<label class="col-md-3 control-label">Short Description</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-home"></i> </span>
							<?php
								echo $this->Form->input(
									'short_description', array(
										'rows' => 2,
										'class' => 'form-control',
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Achievements</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-home"></i> </span>
							<?php
								echo $this->Form->input(
									'achievements', array(
										'rows' => 5,
										'class' => 'form-control ckeditor',
									)
								);
							?>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="form-group">
					<label class="col-md-3 control-label">Profile Image</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
			                    $proImage = $this->Custom->getProfileImage($user->image,PROFILE_IMAGE);
			                    echo $this->Html->image($proImage);
			                ?>
			        	</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Is Featured</label>
					<div class="col-md-4">
						<div class="input-group">
							<?php
								echo $this->Form->input(
									'is_featured', array(
										'class' => 'form-control',
										'options' => [0 => 'No', 1 => 'Yes'],
										'type' => 'select'
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

        /*
        $('#trainer-name').autocomplete({
		  source: base_url+'users/get_trainer?term='+$('#trainer-name').val(),
	      minLength: 2,
	      select: function( event, ui ) {
	      	console.log(ui);
	        $('input[name="trainer_id"]').val(ui.item.id);
	      }
	    });
	    */  
	});
</script>
