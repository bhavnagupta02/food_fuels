<!-- dashboard title Start -->
<?= $this->element('c_breadcrumb'); ?>
<!-- dashboard title End -->
<div class="main-content row">
	<div class="container">
		<div class="mealplan-container">
			<div class="message-content">
				<div class="row center-align mrg-40">
					<?php echo $this->Html->link(__('Renew Your Membership'), ['controller' => 'users', 'action' => 'select_membership_to_extend'], ['class' => 'green-btn']);?>
				</div>
			</div>
		</div>
	</div>
</div>