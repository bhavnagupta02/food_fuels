<?php
echo $this->Html->css('jquery-ui-redmond.min.css');

$username = $this->request->session()->read('Auth.User.first_name')." ".$this->request->session()->read('Auth.User.last_name');

echo $this->Html->script(array('bootstrap.min','cropper','cropmain'));

echo $this->Html->css(array('bootstrap.min','cropper','main'));

//echo  $UserWeightJson; exit;
?>
<!-- dashboard title Start -->
<?= $this->element('c_breadcrumb'); ?>
<!-- dashboard title End -->


<!-- blue banner Start -->
<div class="blue-banner row">
	<div class="container">
		<div class="user-detail">
			<div class="thumb">
				<div class="thumb-inner">
					<?php
                        $proImage = $this->Custom->getProfileImage($userDetails['image']);
                        echo $this->Html->image($proImage);
                    ?>
				</div>
			</div>
			<div class="detail-right">
				<h2><?= $username ?></h2>
				<h3><?= $username."’s Team" ?></h3>
				<ul>
                    <li>
						<?= $this->Html->link(($notiCount!=0)?'<span>'.$notiCount.'</span>':'',['controller' => 'notifications', 'action' => 'index'],['class' => 'n-icon1','escape' => false]); ?>
                    </li>
                    <li><?= $this->Html->link(($msgCount!=0)?'<span>'.$msgCount.'</span>':'',['controller' => 'messages', 'action' => 'inbox'],['class' => 'n-icon3','escape' => false]); ?></li>
                    <li>
                        <?= $this->Html->link('',['controller' => 'trainers', 'action' => 'my_profile'],['class' => 'n-icon2']); ?>
                    </li>
                </ul>
			</div>
		</div>
	</div>
</div>
<!-- blue banner end -->


<!-- group members section start -->
<div class="group-members row">
	<div class="container">
		<h2><?= $username." Group Members"; ?></h2>
		<?PHP
			$i = 0;
			if(isset($userDetails->clients) && !empty($userDetails->clients)){ ?>
			<div class="one-third-outer row" id="trainerTeam">
			<?php foreach ($userDetails->clients as $key => $cValue) {
					if(!empty($cValue)){
						$i++;
					?>
						<div class="one-third">
							<div class="ot-left">
								<div class="icon">
									<?php
				                        $proImage = $this->Custom->getProfileImage($cValue['image'],USER_THUMB);
				                        echo $this->Html->image($proImage);
				                    ?>
								</div>
								<p>Member Since <strong><?= Date('d, F Y',strtotime($cValue['created'])); ?></strong></p>
							</div>
							<div class="ot-right">
								<h3><?= $cValue['first_name']." ".$cValue['last_name'] ?></h3>
								<h4>Weight Loss</h4>
								<ul>
									<li>Total</li>
									<li><span><?= (!empty($cValue['total_weight_loss']))?$cValue['total_weight_loss']."lbs":"--"; ?></span></li>
									<li>This Month</li>
									<li><span><?= (!empty($cValue['month_weight_loss']))?$cValue['month_weight_loss']."lbs":"--"; ?></span></li>
									<li>This Week</li>
									<li><span><?= (!empty($cValue['week_weight_loss']))?$cValue['week_weight_loss']."lbs":"--"; ?></span></li>
									
									<li>No. of days left</li>
									<li>
										<span>
										<?php 
										$lastKey = key( array_slice( $cValue->user_subscriptions, -1, 1, TRUE ) );
										if(isset($cValue->user_subscriptions[$lastKey]) && !empty($cValue->user_subscriptions[$lastKey])){
											$datefrom = strtotime(date('Y-m-d'), 0);
        									$dateto = strtotime($cValue->user_subscriptions[$lastKey]['end_date'], 0);
        									$difference = $dateto - $datefrom;
        									$datediff = floor($difference / (604800/7));
        									echo $datediff;
        								}
        								else{
        									echo "Not Paid";
        								}
										?>
										</span>
									</li>
								</ul>
							</div>
							<div class="days-info">
								<div class="full-row">
								<?php $todayWeight = "--"; ?>
								<?php 
								if(!empty($cValue->user_weights)){
									foreach ($cValue->user_weights as $wkey => $wValue) {
										if(!empty($wValue)){
											if(Date('Y-m-d',strtotime($wValue['weight_date'])) == Date('Y-m-d'))
												$todayWeight = 	$wValue['weight']."lbs";
										?>
										<div class="day"><strong><?=  Date('D',strtotime($wValue['weight_date'])); ?></strong><?=  $wValue['weight'].'lbs'; ?></div>
								<?php 	}
									}			
								}
								else{
									?>
									<div class="day"><strong>--</strong>--</div>
									<?php
								}
								?>	
								</div>
							</div>
							<div class="lower">
								<strong>Today’s Weight</strong>
								<span><?= $todayWeight; ?></span>
								<?= $this->Html->link('Send Message','javascript:void();',['class' => 'send-msg manualPopup', 'relid' => $cValue->id, 'relname' => $cValue->first_name.' '.$cValue->last_name ]); ?>
							</div>
						</div>
			<?php	} 
			} ?>
			</div>
			<?php if($i > 9){ ?>
			<div class="row center-align"><a class="more-btn" href="#">More Members</a></div>
			<?php } ?>
		<?php	} ?>
	</div>
</div>
<!-- group members section end -->

<?php
    echo $this->element('home_community');
    echo $this->Form->create('User', array('class' => 'payment-form', 'id' => 'UserForm','type' => 'file'));
    echo $this->element('home_leaderboard');
    echo $this->element('home_photos');
    echo $this->Form->end();
    echo $this->element('message_model');
?>

<a href="javascript:void(0)" class"message_popup_open hide"></a>
<!-- popup end-->
<script type="text/javascript">
    $(document).ready(function(){
    	$('#filterOptions li a').click(function(){
            if($(this).hasClass('pics')){
                $('#picsHolder').show();
                $('#videosHolder').hide();
            }
            else{
                $('#picsHolder').hide();
                $('#videosHolder').show();
            }
        });
        
        if($('#MessageForm').length){
            $('#MessageForm').submit(function(event){
                event.preventDefault();
                $.ajax({
                    'url'       :   base_url+'messages/send',
                    'type'      :   'post',
                    'dataType'  :   'json',
                    'async'     : 	false,
                    'data'      :   $(this).serialize(),
                    'success'   :   function(data){ 
                        if(data.status == 0)
                        {
                            $('#messageTooltip span').html(data.message);
                            $('#messageTooltip').show();
                            $('.tooltips').delay(5000).fadeOut('slow');
                            $('textarea[name="message"]').val('');
        	            }
                        else{
                            $('#messageTooltip span').html(data.message);
                            $('#messageTooltip').show();
                            $('.tooltips').delay(5000).fadeOut('slow');
                            $('#message_popup').popup("hide");
                            $('textarea[name="message"]').val('');
        	            }
                    }
                });
            });
        }


        $('#message_popup').popup({
          transition: 'all 0.3s',
          scrolllock: true, // optional
        });


        $('.manualPopup').click(function(){
            var userId = $(this).attr('relid');
        	var userName = $(this).attr('relname');
        	$('#send_to').val(userName);
        	$('input[name="receiver_id"]').val(userId);
        	$('textarea[name="message"]').val('');
        	$('#message_popup').popup("show");	
        });
    });
    
</script>