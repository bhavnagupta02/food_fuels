<div class="main row">
	<div class="container">
		<div class="payment-process">
			<h2>Extend Your Membership</h2>
            <style>
            .planinfo > form {
              float: left;
              width: 50%;
            }
            </style>
             
            <div class="planinfo">
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="seanpcornelius@gmail.com">
            <!--input type="hidden" name="business" value="sanish.kamboj@gmail.com"-->
            <input type="hidden" name="lc" value="BM">
            <input type="hidden" name="item_name" value="<?php echo $Subscriptions['s_name'] ?>">
            <input type="hidden" name="item_number" value="<?php echo $Subscriptions['id'] ?>">
            <input type="hidden" name="amount" id="amount" value="<?php echo $Subscriptions['amount'] ?>">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="button_subtype" value="services">
            <input type="hidden" name="cancel_return" value="https://www.foodfuels.com/users/home">
            <input type="hidden" name="return" value="https://www.foodfuels.com/users/home">
            <input type="hidden" name="no_note" value="0">
            <input name="notify_url" value="https://www.foodfuels.com/users/payment_notification/<?php echo $this->request->session()->read('Auth.User.id'); ?>" type="hidden">
            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_LG.gif:NonHostedGuest">
            <input type="submit" class="green-btn" id="showPaypal" value="Pay with Paypal" name="submit" alt="PayPal - The safer, easier way to pay online!" border="0" />
            </form>
                
                <a onclick="showForm();" class="green-btn">Pay with Credit Card</a>
            </div>
            <div class="planinfo">
				
					<input type="text" name="coupon_code" id="coupon_code" placeholder="Enter Coupon Code here !" style="    width: 300px;
    height: 55px;
    font-size: 20px;"/>
					<button type="button" id="apply_code" class="green-btn">ADD</button>
					<button type="button" id="remove_apply_code" class="green-btn">Remove</button>
				 
            </div>
			<div class="plans-container payment-container" style="display:none;" id="creditCardForm">
				<?= $this->Form->create('Payment', array('class' => 'payment-form', 'id' => 'PaymentForm'));
						
						$monthArray = array(
							"01" => 'January',
							"02" => 'February',
							"03" => 'March',
							"04" => 'April',
							"05" => 'May',
							"06" => 'June',
							"07" => 'July',
							"08" => 'August',
							"09" => 'September',
							"10"=> 'October',
							"11"=> 'November',
							"12"=> 'December'
						);

						$yearArray = range(date("Y"), date("Y",strtotime('+20 years')));
						$yearArray = array_combine($yearArray, $yearArray);
						?>
						<?php 
							echo $this->Flash->render();
						?>
						<?= $this->Form->input('card_number',array('class' => 'form-control card-no numeric', 'maxlength' =>16, 'placeholder'=> 'Card Number','templates' => ['inputContainer' => '<div class="form-row">{{content}}<i class="lock-icon"></i></div>'])); ?>
						<?= $this->Form->input('expires_on_month',array('class' => 'form-control', 'type' => 'select', 'options' => $monthArray, 'templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>
						<?= $this->Form->input('expires_on_year',array('class' => 'form-control', 'type' => 'select', 'options' => $yearArray, 'templates' => ['inputContainer' => '<div class="col-6-2"><div class="form-row">{{content}}</div></div>'])); ?>
						<?= $this->Form->input('card_type',array('class' => 'form-control', 'type' => 'select', 'options'=>array('VISA'=>'Visa', 'MASTERCARD'=>'Mastercard', 'AMEX'=>'Amex','DISCOVER'=>'Discover'), 'templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>
						<?= $this->Form->input('security_code',array('class' => 'form-control numeric', 'maxlength' =>4, 'placeholder'=> 'Code','templates' => ['inputContainer' => '<div class="col-6-2"><div class="form-row">{{content}}<i class="lock-icon"></i></div></div>'])); ?>
						
						<div class="col-6-2">
							<div class="form-row">
								<label>We Accept</label>
								<div class="payment-icon">
									<?= $this->Html->link('', '#', ['class' => 'icon-1']); ?>
									<?= $this->Html->link('', '#', ['class' => 'icon-2']); ?>
									<?= $this->Html->link('', '#', ['class' => 'icon-3']); ?>
									<?= $this->Html->link('', '#', ['class' => 'icon-4']); ?>
								</div>
							</div>
						</div>
					</div>
						
					<div class="planinfo">
						<?php
						$month = $Subscriptions['days'] / 30; // I choose 30.5 for Month (30,31) ;)
						$month = floor($month); // Remove all decimals
						
						if($month == 1)
							$month = $month.' month';
						else
							$month = $month.' months';

						$days = ($Subscriptions['days'] % 365) % 30.5; // the rest of days
						if($days == 0)
							$days = '';
						else{
							if($days == 1)
								$days = $days.' day';
							else
								$days = $days.' days';
						}
						?>
						<strong>PlAN INFO</strong>
						<div class="col-6-1"><?= $Subscriptions['s_name']; ?></div>
						<div class="col-6-2"><span id="offer-price"><?= $Subscriptions['amount']; ?></span><sub> for <?= $month.' '.$days; ?></sub></div>
					</div>
				<div class="row center-align mrg-40">
					<?= $this->Form->submit('Update Your Account',['class' => 'green-btn']); ?>
				</div>
				<?= $this->Form->end(); ?>
			</div>
		</div>
	</div>	
</div>
<script>
              function showForm(){
                    $( "#creditCardForm" ).toggle( "slow", function() {
                        // Animation complete.
                      });
              }
    $( "#apply_code" ).click(function() {
		var coupon_code = $("#coupon_code").val();
		var amount = $("#amount").val();
		if(coupon_code==''){
			alert("Please enter coupon code!!");
			return;
		}
		$.ajax({
			method: "POST",
			url: "https://www.foodfuels.com/users/check_coupon_code",
			data: { coupon_code: coupon_code, amount:amount }
		})
		.done(function( msg ) {
			$("#amount").val(msg);
			$("#offer-price").html(msg);
			alert('Code Applied')
			$("#apply_code").attr("disabled", true);
		});
		
	}); 
	$( "#remove_apply_code" ).click(function() {
		location.reload();
	});
</script>
