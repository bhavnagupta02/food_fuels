<!-- home-leaderboard-section element start -->
<div class="leaderboard-row row">

	<div class="container">
		<h2>Leaderboard</h2>
		
		<div class="leaderboard-tab">
			<ul>
				<li><a href="#tab_week" rel="tab_week">Current Week</a></li>
				<li><a href="#tab_month" rel="tab_month" class="active">Current Month</a></li>
				<li><a href="#tab_total" rel="tab_total">Total</a></li>
			</ul>
			
			<div class="tab-content" id="tab_week" style="display:none;">
				<?php 
					if(isset($leaderBoardData['week']) && !empty($leaderBoardData['week'])){
						foreach ($leaderBoardData['week'] as $key => $wValue) {
							?>
							<div class="one_fifth">
								<div class="img-block">
									<?php
					                    $proImage = $this->Custom->getProfileImage($wValue['image'],PROFILE_IMAGE);
					                    echo $this->Html->image($proImage);
					                ?>
								</div>
								<?= $this->Html->link('<h5>'.$wValue['username'].'</h5>','javascript:void(0)',['escape' => false]); ?>
								<p>Total Weightloss <strong><?= '-'.$wValue['week_weight_loss_percent'].'%' ?></strong></p>
								<?php 
								if(isset($wValue['trainer']['first_name']) && !empty($wValue['trainer']['first_name']))
								{ ?>
								<div class="coach-info"><p>Coach</p>
								<div class="thumnil">
									<?php
									$coachImage = $this->Custom->getProfileImage($wValue['trainer']['image'],USER_THUMB);
				                    echo $this->Html->image($coachImage);
				                    ?>
								</div>
								<?= $this->Html->link($wValue['trainer']['first_name']." ".$wValue['trainer']['last_name'],'javascript:void(0)'); ?>
								</div>
								<?php }
								else{
								?>
								<div class="coach-info"><p>Coach</p>
								<div class="thumnil">
									<?php
									$coachImage = $this->Custom->getProfileImage($wValue['trainer']['image'],USER_THUMB);
				                    echo $this->Html->image($coachImage);
				                    ?>
								</div>
								<?= $this->Html->link('Not Assigned','javascript:void(0)'); ?>
								</div>
								<?php
								} ?>
							</div>
							<?php
						}
					}
				?>
			</div>
			<div class="tab-content" id="tab_month">
				<?php 
					if(isset($leaderBoardData['month']) && !empty($leaderBoardData['month'])){
						foreach ($leaderBoardData['month'] as $key => $wValue) {
							?>
							<div class="one_fifth">
								<div class="img-block">
									<?php
					                    $proImage = $this->Custom->getProfileImage($wValue['image'],PROFILE_IMAGE);
					                    echo $this->Html->image($proImage);
					                ?>
								</div>
								<?= $this->Html->link('<h5>'.$wValue['username'].'</h5>','javascript:void(0)',['escape' => false]); ?>
								<p>Total Weightloss <strong><?= '-'.$wValue['month_weight_loss_percent'].'%' ?></strong></p>
								<?php 
								if(isset($wValue['trainer']['first_name']) && !empty($wValue['trainer']['first_name']))
								{ ?>
								<div class="coach-info"><p>Coach</p>
								<div class="thumnil">
									<?php
									$coachImage = $this->Custom->getProfileImage($wValue['trainer']['image'],USER_THUMB);
				                    echo $this->Html->image($coachImage);
				                    ?>
								</div>
								<?= $this->Html->link($wValue['trainer']['first_name']." ".$wValue['trainer']['last_name'],'javascript:void(0)'); ?>
								</div>
								<?php }
								else{
								?>
								<div class="coach-info"><p>Coach</p>
								<div class="thumnil">
									<?php
									$coachImage = $this->Custom->getProfileImage($wValue['trainer']['image'],USER_THUMB);
				                    echo $this->Html->image($coachImage);
				                    ?>
								</div>
								<?= $this->Html->link('Not Assigned','javascript:void(0)'); ?>
								</div>
								<?php
								} ?>
							</div>
							<?php
						}
					}
				?>
			</div>
			<div class="tab-content" id="tab_total" style="display:none;">
				<?php 
					if(isset($leaderBoardData['total']) && !empty($leaderBoardData['total'])){
						foreach ($leaderBoardData['total'] as $key => $wValue) {
							?>
							<div class="one_fifth">
								<div class="img-block">
									<?php
					                    $proImage = $this->Custom->getProfileImage($wValue['image'],PROFILE_IMAGE);
					                    echo $this->Html->image($proImage);
					                ?>
								</div>
								<?= $this->Html->link('<h5>'.$wValue['username'].'</h5>','javascript:void(0)',['escape' => false]); ?>
								<p>Total Weightloss <strong><?= '-'.$wValue['total_weight_loss_percent'].'%' ?></strong></p>
								<?php 
								if(isset($wValue['trainer']['first_name']) && !empty($wValue['trainer']['first_name']))
								{ ?>
								<div class="coach-info"><p>Coach</p>
								<div class="thumnil">
									<?php
									$coachImage = $this->Custom->getProfileImage($wValue['trainer']['image'],USER_THUMB);
				                    echo $this->Html->image($coachImage);
				                    ?>
								</div>
								<?= $this->Html->link($wValue['trainer']['first_name']." ".$wValue['trainer']['last_name'],'javascript:void(0)'); ?>
								</div>
								<?php }
								else{
								?>
								<div class="coach-info"><p>Coach</p>
								<div class="thumnil">
									<?php
									$coachImage = $this->Custom->getProfileImage($wValue['trainer']['image'],USER_THUMB);
				                    echo $this->Html->image($coachImage);
				                    ?>
								</div>
								<?= $this->Html->link('Not Assigned','javascript:void(0)'); ?>
								</div>
								<?php
								}
								?>
							</div>
							<?php
						}
					}
				?>
			</div>
			
		</div>
		
		
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.leaderboard-tab ul li a').click(function(){
		$('.leaderboard-tab ul li a.active').removeClass('active');
		$(this).addClass('active');
		$('.tab-content').hide();
		var rel = $(this).attr('rel');
		$('#'+rel).show();
	});
});
</script>
<!-- home-leaderboard-section element start -->