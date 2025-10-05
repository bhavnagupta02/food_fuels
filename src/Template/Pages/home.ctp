<div class="about-sec row" id="drabout">
	<div class="container">
		<div class="img-sec">
			<?= $this->Html->image('img-1.png'); ?>
		</div>
		<div class="cont-sec">
			<h2>About Us</h2>
			<small>Our Story </small>
			<?= $cmsText[1]->description ?>		
			<div class="btn-sec">
				<?= $this->Html->link('Read Full Story',['controller' => 'Cmspages', 'action' => 'index','about-us'],['class' => 'btn']); ?>
				<?= $this->Html->link('Join Food Fuels Now','',['class' => 'btn green-btn signup_popup_open']); ?>
			</div>
					
		</div>	
	</div>
</div>

<div class="stories-sec row" id="successstories">
		<div class="container">
			<div id="tabs">
			    <ul>
			    	<li><?= $this->Html->link('Success','#tabs-1'); ?></li>
					<li><?= $this->Html->link('Stories','#tabs-2'); ?></li>
			  	</ul>
			  <div id="tabs-1">
					<h3>FoodFuels Has Helped Thousands Successfully Lose Weight</h3>
					<?= $cmsText[2]->description ?>
					<div class="title">Check out the photos to see the typical FoodFuels results for yourself</div>
					<div class="panel">
						<?php 
						  	if(isset($featuredClient) && !empty($featuredClient)){
						  		foreach ($featuredClient as $key => $fCvalue) {
							  		if($key < 3)
							  		{
							  			?>
							  			<li class="col-3">
											<div class="img-sec">
												<?php
								                    $BeforeImage = $this->Custom->getProfileImage($fCvalue->before_image,USER_BEFORE);
								                    echo $this->Html->image($BeforeImage);
								                    $AfterImage = $this->Custom->getProfileImage($fCvalue->after_image,USER_AFTER);
								                    echo $this->Html->image($AfterImage);
								                ?>
											</div>
											<!--<p>“Surpassed ALL my expectations and gained so much more than I ever dreamed. This journey has changed and saved my life.”</p>
											<a class="link" href="#"><?= $fCvalue->username ?></a>-->
										</li>
							  			<?php
							  		}
							  	}
							}
						?>
					</div>
				</div>
			  <div id="tabs-2">
					<div class="title">Check out the photos to see the typical FoodFuels results for yourself</div>
					<ul class="panel">
						<?php 
						  	if(isset($featuredClient) && !empty($featuredClient)){
						  		foreach ($featuredClient as $key => $fCvalue) {
							  		if($key > 3)
							  		{
							  			?>
							  			<li class="col-3">
											<div class="img-sec">
												<?php
								                    $BeforeImage = $this->Custom->getProfileImage($fCvalue->before_image,USER_AFTER);
								                    echo $this->Html->image($BeforeImage);
								                    $AfterImage = $this->Custom->getProfileImage($fCvalue->after_image,USER_AFTER);
								                    echo $this->Html->image($AfterImage);
								                ?>
											</div>
											<!--<p>“Surpassed ALL my expectations and gained so much more than I ever dreamed. This journey has changed and saved my life.”</p>-->
											<a class="link" href="#"><?= $fCvalue->username ?></a>
										</li>
							  			<?php
							  		}
							  	}
							}
						?>
					</ul>
			  </div>
			
		</div>	
		</div>
</div>

<div class="banner-sec row" id="thenutrition">
	<div class="container">
	<div class="banner-cont">
		<div class="sec-head">
			<h2>the foodfuels</h2>
			<small>nutrition</small>
		</div>
		<?= $cmsText[3]->description ?>
		<?= $this->Html->link(__('read More'),['controller' => 'Cmspages', 'action' => 'index','nutrition'],['class' => 'btn red-more']); ?>
	</div>	
	</div>
</div>

<div class="testimonail row" id="coaches">
	<div class="container">
		<h2>Meet some of our Coaches</h2>
		
		<ul class="testimonial-slider">
		  <?php 
		  	if(isset($featuredTrainers) && !empty($featuredTrainers)){
		  		foreach ($featuredTrainers as $key => $value) {
			  	?>
			  	<li>
					<div class="slide-thumnail">
						<?php
	                        $proImage = $this->Custom->getProfileImage($value['image']);
	                        echo $this->Html->image($proImage);
	                    ?>
					</div>
					<div class="slide-content">
						<h2><?= $value['first_name']." " ?><small><?= $value['last_name'] ?></small></h2>
						<p><?= '"'.$value['short_description'].'"' ?></p>
						<ul><?= $value['achievements'] ?></ul>
						<?= $this->Html->link('Join '.$value['first_name']." ".$value['last_name'],'javascript:void(0)',['class' => 'btn green-btn join_trainer','rel' => $value['id']]); ?>
						</div>
				  </li>
			  	<?php
			  	}
		  	}
		  ?>
		</ul>
		<div><?= $this->Html->link('View Coaches','coach/all',['class' => 'btn green-btn join_trainer']); ?></div>
	</div>
</div>

<div class="membership-sec row">
	<div class="container">
		<div class="left-sec">
			<h2>Membership</h2>
			<!--<div class="sub-title">Choose Your subscription Plans</div>-->
			<div class="sub-title">CLICK ON YOUR PLAN BELOW TO GET STARTED</div>
			<?php
				$cnt = 1;
				foreach($Subscriptions as $subKey => $subVal){
					?>
					<div class="plan-block signup_popup_open">
						<div class="plan-no"><?php echo $cnt;?></div>
						<div class="membership-plan">
							<h3><?php echo $subVal['s_name'];?></h3>
							<p>
								<?php echo $subVal['description'];?>
							</p>
							<div class="plan-price green-block" style="background-color:#<?php echo $subVal['color'];?>">
								<b><sup>$</sup><?php echo number_format($subVal['amount'], 2);?></b>
								<a href="#"> Learn More</a>
							</div>
						</div>
					</div>
					<?php
					$cnt++;
				}

			?>
		</div>
		<div class="divider">or</div>
		
		<div class="right-sec">
			<h2>Contact us</h2>
			<div class="sub-title">For more Information</div>
			<?= $this->Form->create('Enquiry', array('class' => 'cont-form', 'id' => 'ContactForm', 'url' => array('controller' => 'Enquiries', 'action' => 'add')));
				$this->Form->templates([
				    'label' => false
				]);
			?>	
			<a class="tooltips" id="contactTooltip" style="display:none;" href="#">
				<span>
				</span>
			</a>
			<div style="clear:both;"></div>	
			<?= $this->Form->input('name',array('class' => 'input-field', 'required', 'placeholder' => 'Name','templates' => ['inputContainer' => '<div class="form-control"><i class="user-icon"></i>{{content}}</div>'])); ?>
			
			<?= $this->Form->input('email',array('class' => 'input-field', 'required', 'placeholder'=> 'Email Address','type' => 'email','templates' => ['inputContainer' => '<div class="form-control"><i class="mail-icon"></i>{{content}}</div>'])); ?>
			
			<?= $this->Form->input('phone',array('class' => 'input-field', 'required', 'placeholder'=> 'Phone Number','templates' => ['inputContainer' => '<div class="form-control"><i class="phn-icon"></i>{{content}}</div>'])); ?>

			<?= $this->Form->input('comments',array('class' => 'input-field', 'required', 'type' => 'textarea', 'placeholder'=> 'Write comments here','templates' => ['inputContainer' => '<div class="form-control">{{content}}</div>'])); ?>
			
			<?= $this->Form->submit('Submit Details',array('class' => 'btn-contol')); ?>
			
			<?= $this->Form->end(); ?>
		</div>
	</div>
</div>	
<script>
<?php if(isset($type) && !empty($type)){
	?>
	var actionType = '<?= $type ?>';
	
	$(document).ready(function(){
		if(actionType == "login"){
			$('.login_popup_open').click();
		}
		else if(actionType == "register"){
			$('.signup_popup_open').click();
		} else if(actionType == "reset_pass"){
			
			$('.forget-pass').click();
		}
	});
<?php
} ?>
$(document).ready(function(){
	if($('#ContactForm').length){
		$('#ContactForm').submit(function(){
			event.preventDefault();
			
			$.ajax({
	            'url'		: 	base_url+'users/enquiry',
	            'type'		: 	'post',
	            'dataType'	:   'json',
	            'data'		: 	$(this).serialize(),
	            'success'	: 	function(data){ 
	            	if(data.status == 1){
	            		$('#contactTooltip span').html(data.message);
						$('#contactTooltip').show();
					}
					else{
						$('#contactTooltip span').html(data.message);
						$('#contactTooltip').show();
					}
					$('.tooltips').delay(5000).fadeOut('slow');
		        }
	        });
		});
	}

	if($('#SubscribersForm').length){
		$('#SubscribersForm').submit(function(){
			event.preventDefault();
			
			$.ajax({
	            'url'		: 	base_url+'users/subscribe_me',
	            'type'		: 	'post',
	            'dataType'	:   'json',
	            'data'		: 	$(this).serialize(),
	            'success'	: 	function(data){ 
	            	if(data.status == 1){
	            		$('#subscriberTooltip span').html(data.message);
						$('#subscriberTooltip').show();
					}
					else{
						$('#subscriberTooltip span').html(data.message);
						$('#subscriberTooltip').show();
					}
					$('.tooltips').delay(5000).fadeOut('slow');
		        }
	        });
		});
	}

	if($('.skipVideo').length){
		$('.skipVideo').click(function(){
			$.ajax({
	            'url'		: 	base_url+'users/skipVideo',
	            'dataType'	:   'html',
	            'success'	: 	function(){ 
	            	$('.hdr-video').hide('slow');
			    }
	        });
		});
	}

	$('.join_trainer').click(function(){
		$('#signup_popup').popup('show');
		var trainer_id = $(this).attr('rel'); 
		$('input[name="trainer_id"]').val(trainer_id);
	});
});
</script>	
