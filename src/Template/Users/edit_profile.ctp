<style type="text/css">
.modal-backdrop.in
{display:none !important;}

.col-6-1.wake-time {
  margin: 0 47px 0px 0px;
  position: relative;
}

</style>
<?php //echo "<pre>"; print_r($this->request->data); ?>
<div class="main row">
	<div class="container">
		<div class="payment-process">
		
			<h2>Edit Your Account</h2>
			
			<?= $this->element('topBarAfterLogin'); ?>
			
			<div class="plans-container payment-container">
				<?= $this->Form->create('User', array('class' => 'payment-form', 'id' => 'UserForm'),['type' => 'file']);
				?>
				<?php 
					echo $this->Flash->render();
				?>
				<?= $this->Form->input('first_name',array('class' => 'form-control', 'required', 'templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>
				
				<?= $this->Form->input('last_name',array('class' => 'form-control', 'required', 'templates' => ['inputContainer' => '<div class="col-6-2"><div class="form-row">{{content}}</div></div>'])); ?>
				
				<?= $this->Form->input('email',array('class' => 'form-control', 'required','readonly', 'templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>
				
				<?= $this->Form->input('username',array('class' => 'form-control', 'required', 'templates' => ['inputContainer' => '<div class="col-6-2"><div class="form-row">{{content}}</div></div>'])); ?>

				<?= $this->Form->input('mobile',array('class' => 'form-control card-no numeric', 'required', 'placeholder'=> 'Contact Number','templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>

				<?= $this->Form->input('dob',array('class' => 'form-control', 'label' => 'Date Of Birth', 'value' => Date('Y-m-d',strtotime($this->request->data['dob'])), 'required', 'templates' => ['inputContainer' => '<div class="col-6-2"><div class="form-row">{{content}}</div></div>'])); ?>
				
				<?= $this->Form->input('goal_weight',array('class' => 'form-control numeric', 'label' => 'Goal Weight', 'required', 'templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>

				<?= $this->Form->input('referred_by',array('class' => 'form-control', 'label' => 'Referred By', 'templates' => ['inputContainer' => '<div class="col-6-2"><div class="form-row">{{content}}</div></div>'])); ?>

				<?= $this->Form->input('gender',array('class' => 'form-control', 'id' => 'select1', 'options' => array('' => '',0 => 'Male',1 => 'Female'), 'required', 'templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>

				<?= $this->Form->input('leaderboard_show',array('class' => 'form-control', 'type' => 'select', 'options' => [1=>'Yes',0=>'No'], 'label' => 'Include Results in Leaderboard', 'templates' => ['inputContainer' => '<div class="col-6-2"><div class="form-row">{{content}}</div></div>'])); ?>

				<?= $this->Form->input('meal_type',array('class' => 'form-control', 'id' => 'select2', 'options' => array(0 => '', 1 => 'Vegetarian', 2 => 'Non-Vegetarian', 3 => 'Other'), 'required', 'templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>

				<?= $this->Form->input('activity_level',array('class' => 'form-control', 'id' => 'select3', 'options' => array(0 => '', 1 => 'Low', 2 => 'Medium', 3 => 'High'), 'required', 'templates' => ['inputContainer' => '<div class="col-6-2"><div class="form-row">{{content}}</div></div>'])); ?>

				<?= $this->Form->input('wakeup_time',array('class' => 'form-control', 'label' => 'Wakeup Time', 'id'=>'timepicker', 'required', 'templates' => ['inputContainer' => '<div class="col-6-1 wake-time"><div class="form-row">{{content}}</div></div>'])); ?>

				<div>
					<div class="form-row">
						<div class="image-upload">
							<div class="container">
								<h2>Upload your picture</h2>
								<div class="one_half">
									<div class="figure">
										<?php
											if(isset($this->request->data['image']))
												$imageName = $this->request->data['image'];
											else
												$imageName = $this->request->session()->read('Auth.User.image');
											
											$proImage = $this->Custom->getProfileImage($imageName,PROFILE_IMAGE);
											echo $this->Html->image($proImage);
										?>
									</div>
									
									<div class="btn-group">
										<div class="upload-file">
											Choose Image
											<div class="input file">
												<input class="<?php if($this->request->data['image']=='') { echo 'required';  } elseif(!file_exists(USER_IMAGE_PATH.$this->request->data['image'])){echo 'required';}?>" id = "hideFile" value="" name = "file" type="file" onchange="profile_img_upload(this);"/><i class="fa fa-upload"></i>
											</div>	
										</div>
									</div>

									<?php
										echo $this->Form->input('id', array('type' => 'hidden'));
										echo $this->Form->input('image_x', array('type' => 'hidden'));
										echo $this->Form->input('image_y', array('type' => 'hidden'));
										echo $this->Form->input('image_height', array('type' => 'hidden'));
										echo $this->Form->input('image_width', array('type' => 'hidden'));
										echo $this->Form->input('image_rotate', array('type' => 'hidden'));
										echo $this->Form->input('image_url', array('type' => 'hidden'));
									?>
								</div>	
							</div>
						</div>
					</div>
				</div>
				<div class="form-row marginTop20 whiteColor">
		         <?= $this->Form->input('terms',array('id' => 'checkbox', 'label' => false,'type' => 'checkbox','templates' => ['inputContainer' => '<div class="checkbox-div">{{content}}<label for="checkbox"></label></div>By updating your profile you are agree to our '.$this->Html->link('Terms and Services',['controller' => 'terms'])])); ?>
		        </div>  	
			</div>
			<div class="row center-align mrg-40">
				<?= $this->Form->submit('Update Your Account',['class' => 'green-btn']); ?>
			</div>
			<?= $this->Form->end(); ?>
			</div>
		</div>
	</div>	
</div>

<?= $this->element('crop_model'); ?>

</section>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css"/> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script> 
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('input#timepicker').timepicker({ timeFormat: 'h:mm:ss p' });
});
	$("#dob").datepicker({
	    dateFormat: "yy-mm-dd",
	    changeMonth: true,
      	changeYear: true,
      	yearRange: '1950:2016',
	});


    function profile_img_upload(obj)
	{
		//$('.loadingimg').css('display','inline-block');
		var site_url = '<?php echo $this->request->webroot; ?>';
		var input = $('#'+$(obj).attr('id'));//console.log(input.attr('id'));return false;
		
		$('<form enctype="multipart/form-data" method="post" id="upload_photo"></form>').append(input).hide().appendTo('body').ajaxForm({
			url: site_url+'users/profile_upload',
			data: { imagename: input.attr('id')},
			success: function (data)
			{
				var data = jQuery.parseJSON(data);
				var file_element = $(document.createElement('input')).attr('type', 'file').attr('name', 'file').attr('id',input.attr('id')).attr('class',input.attr('class')).attr('onchange', 'profile_img_upload(this)');
				$('.inputFile').prepend(file_element);
				if (data.status == 'success')
				{ 
					$('.showCropBox').click();
					$('.img-container img').attr('src',data.image);
					$('.img-preview img').attr('src',data.image);
					$('#image-url').val(data.image);
					//$('.imagecrop').attr('data-pid',data.image).attr('data-id','success');
					//$('.loadingimg').css('display','none');
				}
				else
				{
					//$('.loadingimg').css('display','none');
					alert(data.message);
				}
			},
			complete:function()
			{
				//$('.loadingimg').css('display','none');
			}
		}).submit();
	}

$('document').ready(function()
{
	$('#saveMyPic').click(function(){
		var site_url = '<?php echo $this->request->webroot; ?>';
		var img_path = $('#image-url').val();
		var img_x = $('#image-x').val();
        var img_y = $('#image-y').val();
        var img_heigth = $('#image-height').val();
        var img_width = $('#image-width').val();
        var img_rotate = $('#image-rotate').val();
        var image_name = img_path.substring((img_path.lastIndexOf('/')+1), img_path.length);
        $.ajax(
				{
					url: site_url+'users/cropnrotate',
					data:{ image_name: image_name,img_x: img_x,img_y: img_y,img_heigth: img_heigth,img_width: img_width,img_width: img_width,img_rotate: img_rotate},
					success: function(response)
					{
						var json_response = $.parseJSON(response);
						if(json_response.status == 'success')
						{
							$('.figure img').attr('src',json_response.image+'?rand='+Math.random());
							//$('#imageResponse').hide();
							$('#myModal').modal('hide');
							$('.image').removeClass('required');
							$('#delete_profile').next('label.error').remove();
						}
					}
				});
	});
});
</script>

<script>
$(document).ready(function() {
$('#select2').hide();
$('#select3').hide();

$('#select1').on('change', function () {
    if($('#select1').val() == $(this).val()) {
        $('#select2').show();
    } 
});

$('#select2').on('change', function () {
    if($('#select2').val() == $(this).val()) {
        $('#select3').show();   
    } 
});

});
</script>