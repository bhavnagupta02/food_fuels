<div class="main row">
	<div class="container">
		<div class="payment-process">
			<div class="plans-container congrats-sec">
				<div class="hand-icon">
					<?= $this->Html->image('hand-icon.png'); ?>
				</div>
				<h4>Congratulations!</h4>
				<p>Your account has been created. <br/>You should recieve an email confirmation with detailed purchase information in a few minutes.</p>
				
				<div class="row center-align mrg-40">
					<?= $this->Html->link('LogIn to your account',['controller' => 'pages', 'action' => 'home', 'actionType' => 'login'],['class' => 'green-btn']); ?>
				</div>
			</div>
		</div>
	</div>	
</div>
