<!-- right_sidebar element start -->
<div class="right-sidebar">
	<!--
	<ul class="posts-list">
		<li><a href="#">John</a> commented on your post. “Lorem ipsum dolor sit amet...”</li>
		<li><a href="#">John</a> likes your post. “Lorem ipsum dolor sit amet...”</li>
		<li><a href="#">John</a> likes your post. “Lorem ipsum dolor sit amet...”</li>
		<li><a href="#">John</a> likes your post. “Lorem ipsum dolor sit amet...”</li>
	</ul>
	-->
	<?php 
		if(isset($memberOfmonth['week']) && !empty($memberOfmonth['week'])){
			foreach ($memberOfmonth['week'] as $key => $wValue) {
				?>
				<div class="member-info">
					<h4>Member of the week</h4>
					<div class="img">
						<?php
		                    $proImage = $this->Custom->getProfileImage($wValue['image'],PROFILE_IMAGE);
		                    echo $this->Html->image($proImage);
		                ?>
					</div>
					<?= $this->Html->link('<h5>'.$wValue['first_name'].' '.$wValue['last_name'].'</h5>','javascript:void(0)',['escape' => false]); ?>
					<p>Total Weightloss <strong><?= '-'.$wValue['week_weight_loss_percent'].'%' ?></strong></p>
				</div>
				<?php
			}
		}
	?>
</div>
<!-- left_sidebar element start -->