<div class="main row">
	<div class="container">
		<?php echo $this->Flash->render(); ?>
		<div class="payment-process">
			<h2>Extend Your Membership</h2>
			<div class="plans-container">
				<?php
				$cnt = 1;
				foreach($Subscriptions as $subKey => $subVal){
					?>
					<div class="plan-block" rel="<?= $subVal['id'] ?>">
						<div class="plan-no"></div>
						<div class="membership-plan">
							<h3><?php echo $subVal['s_name'];?></h3>
							<p>
								<?php echo $subVal['description'];?>
							</p>
							<div class="plan-price green-block" style="background-color:#<?php echo $subVal['color'];?>">
								<b><sup>$</sup><?php echo $subVal['amount'];?></b>
								<a href="#"> Learn More</a>
							</div>
						</div>
					</div>
					<?php
					$cnt++;
				}
				?>
			</div>
			
			<div class="row center-align mrg-40">
				<?= $this->Html->link('Next Step','javascript:void(0);',['class' => 'green-btn', 'id' => 'submitPayment']); ?>
			</div>
		</div>
	</div>	
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('.plan-block').click(function() {
		$('.plan-block').removeClass('active');
		$(this).addClass('active');
		var paymentId = $(this).attr('rel');
		$('#submitPayment').attr('href',"<?= $this->Url->build(['controller' => 'users', 'action' => 'pay_to_extend']); ?>/"+paymentId);
	});
});
</script>