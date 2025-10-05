<div class="main row">
	<div class="container">
		<div class="payment-process">
		
			<h2>Edit Your Account</h2>
			
			<div class="progress-row">
				<div class="bdr"></div>
				
				<div class="pay-stage active">
					<a href="#" class="circle active"></a>
					<p>Personal Info</p>
				</div>
				<div class="pay-stage active">
					<a href="#" class="circle">2</a>
					<p>Select Coach</p>
				</div>
				<!--
				<div class="pay-stage">
					<a href="#" class="circle active">3</a>
					<p>Payment</p>
				</div>
				-->
			</div>
			
			<div class="plans-container payment-container">
				<?= $this->Form->create('Payment', array('class' => 'payment-form', 'id' => 'PaymentForm'));
						
						$monthArray = array(
							1 => 'January',
							2 => 'February',
							3 => 'March',
							4 => 'April',
							5 => 'May',
							6 => 'June',
							7 => 'July',
							8 => 'July',
							9 => 'September',
							10=> 'October',
							11=> 'November',
							12=> 'December'
						);

						$yearArray = range(date("Y"), date("Y",strtotime('+20 years')));

						?>
						<?php 
							echo $this->Flash->render();
						?>
						<?= $this->Form->input('card_number',array('class' => 'form-control card-no', 'placeholder'=> 'Card Number','templates' => ['inputContainer' => '<div class="form-row">{{content}}<i class="lock-icon"></i></div>'])); ?>
						<?= $this->Form->input('expires_on_month',array('class' => 'form-control', 'type' => 'select', 'options' => $monthArray, 'templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>
						<?= $this->Form->input('expires_on_year',array('class' => 'form-control', 'type' => 'select', 'options' => $yearArray, 'templates' => ['inputContainer' => '<div class="col-6-2"><div class="form-row">{{content}}</div></div>'])); ?>
						<?= $this->Form->input('security_code',array('class' => 'form-control', 'placeholder'=> 'Code','templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}<i class="lock-icon"></i></div></div>'])); ?>
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
						<div class="col-6-2"><?= $Subscriptions['amount']; ?><sub> for <?= $month.' '.$days; ?></sub></div>
					</div>
				<div class="row center-align mrg-40">
					<?= $this->Form->submit('Create Your Account',['class' => 'green-btn']); ?>
				</div>
				<?= $this->Form->end(); ?>
			</div>
		</div>
	</div>	
</div>