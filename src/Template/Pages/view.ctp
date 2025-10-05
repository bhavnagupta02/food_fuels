<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-55f2b9e8f7984886" async="async"></script>

<?php echo $this->Html->css('jquery.fancybox');
			echo $this->Html->script('jquery.fancybox'); ?>
<?php 
	if(isset($deal->deal['deals_lang_data'][0]['title']) && !empty($deal->deal['deals_lang_data'][0]['title'])){
		$dealTitle = $deal->deal['deals_lang_data'][0]['title'];
		$dealDescription = $deal->deal['deals_lang_data'][0]['description'];
		$dealBody = $deal->deal['deals_lang_data'][0]['bodyHtml'];
		$dealTnc = $deal->deal['deals_lang_data'][0]['tncHtml'];
	}
	else{
		$dealTitle = $deal->deal->title;
		$dealDescription = $deal->description;
		$dealBody = $deal->bodyHtml;
		$dealTnc = $deal->tncHtml;
	}
?>			
<!-----------------Container------------------>
<section class="full-wrapper container-div inner-container">
	<div class="wrapper">
		<div class="row">
			<div class="container-left">
				<div class="flexslider-thumb">
					<ul class="slides">
						<?php
						if(isset($deal->deal['upload_images']) && !empty($deal->deal['upload_images'])){
							foreach ($deal->deal['upload_images'] as $key => $value) {
								if(file_exists(UPLOAD_IMAGE_URL.$value['name'])){
									?>
									<li data-thumb="<?php echo BASE_URL.UPLOAD_IMAGE_URL.$value['name']; ?>">
										<?php 
											$imgName = BASE_URL.UPLOAD_IMAGE_URL.$value['name'];
                      echo $this->Html->image($imgName);
                  	?>
									</li>
									<?php
								}
							}
						}
						?>
					</ul>
				</div>
				
			</div>
			
			<div class="container-right">
				<div class="offer-made">
					<h2><?php echo $dealTitle; ?></h2>
					<span><?php echo __("Valued").": ".$deal->deal->value.__(" THB"); ?></span>
				 		
				 	<div class="hours-div">
						<div class="hour-center">
							<?php
							if($deal->deal->status_id == 1 && $deal->live_status == 1){
								$date = new DateTime(Date('Y-m-d',strtotime($deal->end_date)).' '.Date('H:i:s',strtotime($deal->end_time)));
								
								if(!empty($client_time_zone))
									$date->setTimezone(new DateTimeZone($client_time_zone));
											
						 	?>
							<ul class="viewPage" data-countdown="<?= $date->format('Y/m/d H:i:s'); ?>">
								<li class="remain-time"><h2><?php echo __('Time remaining:'); ?></h2></li>
								<li>00<p><?php echo __('Days'); ?></p></li>
								<li>00<p><?php echo __('Hours'); ?></p></li>
								<li>00<p><?php echo __('Minutes'); ?></p></li>
							</ul>
							<?php
						 	}
						 	else if($deal->live_status == 3 && isset($deal->offers[0]->user_id) && isset($user_id) && $deal->offers[0]->user_id == $user_id && $deal->offers[0]->status_id==2){
						 	?>
						 	<ul class="viewPage">
						 		<?php
				 				if(strtotime($deal->offers[0]->close_date) > strtotime(Date('Y-m-d H:i:s'))){
									echo $this->Html->link(__('Pay Now'), ['controller' => 'deals', 'action' => 'checkout', $deal->offers[0]->user->email, $deal->offers[0]->verification_token],['target'	=>	'blank', 'class'	=>	'offerPayHeader marginLeft10']);
								}
								else{
									echo $this->Html->link(__('Expired'), 'javascript:void(0);',['class'	=>	'offerPayHeader marginLeft10 expiredButton']);		
								}
								?>
						 	</ul>
						 	<?php
						 	}
						 	else{
						 	?>
						 	<ul class="viewPage">
							 	<li class="redColor opacityHalf"><?php echo __('Listing Ended'); ?></li>
						 	</ul>
						 	<?php
						 	} ?>
						</div>
					</div>
					 	
					<?php 
					$offerDetailsClass	=	'borderTop0';
					if($deal->live_status != 3){ ?>
					<div class="place-offer">
						<span>
							<?php
								echo $this->Form->create('Offer', ['class' => 'validate_form dealOffer']);
								?>
								<div class="place_an_offer">
									<?php
										echo __('Place an Offer:');
									?>	
								</div>
								<?php
								echo $this->Form->hidden('service_listing_id',array('value'=>$deal->id));
								echo $this->Form->input('offer_value',array('placeholder'=>__('Enter value'),'class'=>'numeric'));
								if($this->request->session()->read('Auth.User.id')){ ?>
									<input type="submit" class="addOffer" value="submit">
								<?php }
								else
									echo $this->Html->link('submit',['controller'=>'users','action'=>'login_check',$deal->id],['class'=>'gotoLogin']);
								?>
							
							<?php  echo $this->Form->end() ?>
						</span>
						<a class="tooltips hide" style="display:none;" href="#">
							<span>
							<?php echo __('Your offer is in the top five but currently not the highest, try packing another offer.'); ?>
							</span>
						</a>
					</div>
					<?php
					$offerDetailsClass	=	'';
					 } ?>
					<div class="offer-details <?= $offerDetailsClass ?>">
						<?php echo $this->element('offerDetails'); ?>
					</div>
				</div>
			</div>	
		
		</div>
		
		<div class="row">
			<div class="container-left">
				<div class="product-content">
				 	<?php echo $dealBody; ?>
				</div>
				<div class="terms-condition">
					<h2><?php echo __('Terms and conditions');?></h2>
					<p><?php echo $dealTnc; ?></p>
				</div>
				<div class="terms-condition">
					<h2><?php echo __('Share');?></h2>
					<p>
						<!-- Go to www.addthis.com/dashboard to customize your tools -->
						<div class="addthis_sharing_toolbox"></div>
					</p>
				</div>
			</div>
		
			
			<div class="container-right">
				
			  	<?php 
				if(isset($relatedDeals) && !empty($relatedDeals))
				{
					?>
					<div class="box-grid sidebar-grid">
					<?php	
					foreach ($relatedDeals as $key => $rDeal) {
						if(isset($rDeal->deal->deals_lang_data[0]['title']) && !empty($rDeal->deal->deals_lang_data[0]['title'])){
								$dealTitle = $rDeal->deal->deals_lang_data[0]['title'];
								$dealDescription = $rDeal->deal->deals_lang_data[0]['description'];
							}
							else{
								$dealTitle = $rDeal->deal['title'];
								$dealDescription = $rDeal->deal['description'];
							}
						?>
							<div class="box first-box" rel="<?php echo $rDeal->id; ?>">
								<div class="image-caption">
									<span style="max-height:200px; overflow:hidden;">
										<?php 
										if(isset($rDeal->deal->upload_images[0]['name']) && !empty($rDeal->deal->upload_images[0]['name'])){
											if(file_exists(UPLOAD_IMAGE_URL.$rDeal->deal['upload_images'][0]['name'])){
												$imgName = BASE_URL.UPLOAD_IMAGE_URL.$rDeal->deal['upload_images'][0]['name'];
					                            echo $this->Html->image($imgName);
					                    	}
					                    	else{
					                    	?>
					                    		<img src="<?php echo $this->Url->build(['controller' => 'img', 'action' => 'box-1.jpg'])?>" alt="">
					                    	<?php	
					                    	}
										}
										else{
											?>
											<img src="<?php echo $this->Url->build(['controller' => 'img', 'action' => 'box-1.jpg'])?>" alt="">
											<?php
										}

										?>
									</span>
									<?php 
									$date = new DateTime(Date('Y-m-d',strtotime($rDeal->end_date)).' '.Date('H:i:s',strtotime($rDeal->end_time)));
								
									if(!empty($client_time_zone))
										$date->setTimezone(new DateTimeZone($client_time_zone));
									?>
									<div class="hours-div">
										<ul data-countdown="<?= $date->format('Y/m/d H:i:s'); ?>">
											<li>00<p>Days</p></li>
											<li>00<p>Hours</p></li>
											<li>00<p>Minutes</p></li>
										</ul>
									</div>
								</div>
								<h2>
									<?php
										$wordCountCheck = 80;
										if (!ctype_alnum($dealTitle)) // '/[^a-z\d]/i' should also work.
											$wordCountCheck = 200;

										if(strlen($dealTitle) > $wordCountCheck)
											echo substr($dealTitle,0,stripos($dealTitle,' ',$wordCountCheck)).'..';
										else
											echo $rDeal->deal['title']; 
									?>
								</h2>
								<p>
									<?php

										$wordCountCheck = 200;
										if (!ctype_alnum($dealDescription)) // '/[^a-z\d]/i' should also work.
											$wordCountCheck = 400;

										if(strlen($dealDescription) > $wordCountCheck)
											echo substr($dealDescription,0,stripos($dealDescription,' ',$wordCountCheck)).'..';
										else
											echo $dealDescription; 
									?>
								</p>
								<strong><?php echo __("Worth: ").$rDeal->deal['value'].__(" THB"); ?></strong>
								<div class="bttn-center">
									<a href="#" class="offer-bttn"><?php echo $rDeal['offer_count'].__(" Offers"); ?></a>
								</div>
							</div>
						<?php 
					}
					?>
					</div>
					<?php
				}
				?>		
			</div>
		</div>
	</div>	
</section>
<script> 
$(document).ready(function(){
	$('.dealOffer').submit(function(e){
		e.preventDefault();

		var currentOfferVal	=	parseInt($('.offerValue').html());
		
		if(currentOfferVal	>=	$('#offer-value').val()){
			$('.tooltips span').html('<?php echo __("Please enter a higher offer then "); ?>'+currentOfferVal);
      $('.tooltips').show();

      setTimeout(function(){
				$('.tooltips').hide('fade','slow');	
			},2000);
			
			return false;
		}
		$.ajax({
            'url'			: 	'<?php echo $this->Url->build(["controller" => "deals","action" => "addOffer"]); ?>',
            'type'		: 	'post',
            'data'		: 	$('.dealOffer').serialize(),
            'success'	: 	function(data){ 
            		$('.offer-details').html(data);            		
            		var reponseMessage = $('.successResponse').html();
            		$('.successResponse').remove();
            		$('.tooltips span').html(reponseMessage);
					      $('.tooltips').show();

					      //setTimeout(function(){
									//$('.tooltips').hide('fade','slow');	
								//},2000);
            }
        });
	});
	
	
	
});

$(window).on('ready load resize', function(){
	$('.container-left .flexslider-thumb .slides li').each(function(){
		var liheight = $(this).height() - $(this).find('img').height();
		$(this).find('img').css('margin-top', liheight/2);
	});
});	
</script>