<!-- TopBarAfterLogin element start -->
<?php 
	$action 		= strtolower($this->request->action);
	$controller 	= strtolower($this->request->controller);
	$communityClass	= $one = $two = $three = $four = '';
	$combiNation 	= $controller."-".$action;

	switch ($combiNation) {
		case 'users-payment_select':
			$one = 'active';
			break;

		case 'users-pay_me':
			$two = 'active';
			$one = 'active';
			break;

		case 'users-coach_assign':
			$three = 'active';
			$two = 'active';
			$one = 'active';
			break;	
	}
?>
<div class="progress-row">
	<div class="bdr"></div>
	<div class="pay-stage <?= $one ?>">
		<?= $this->Html->link('1','#',['class' => 'circle']); ?>
		<p>Basic Info</p>
	</div>
	<div class="pay-stage <?= $two ?>">
		<?= $this->Html->link('2','#',['class' => 'circle active']); ?>
		<p>Select Plan</p>
	</div>
	<div class="pay-stage <?= $three ?>">
		<?= $this->Html->link('3','#',['class' => 'circle active']); ?>
		<p>Payment</p>
	</div>
	<div class="pay-stage <?= $four ?>">
		<?= $this->Html->link('4','#',['class' => 'circle active']); ?>
		<p>Select Coach</p>
	</div>
</div>
<!-- TopBarAfterLogin element ends -->
