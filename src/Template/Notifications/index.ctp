<!-- dashboard title Start -->
<?= $this->element('c_breadcrumb'); ?>
<!-- dashboard title End -->
<div class="main-content row">
	<div class="container">
		<?= $this->element('left_sidebar'); ?>
		<div class="middle-content message-content">
			<div class="heading">Notifications</div>
			<div class="message-block notificationTile">
				<?php 
				if(isset($notiArray) && !empty($notiArray)){
					foreach ($notiArray['ActivityLogs'] as $key => $value) {
						
						if($value['activity_id'] == 1){
							?>
							<div class="message-panel messageRel" rel="<?= $value['timestamp']; ?>">
								<div class="thumnil-block">
									<a href="javascript:void(0);" class="thum-img">
										<?php
				                            $proImage = $this->Custom->getProfileImage($value['data']['conversation_replies'][0]['user']['image'],USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
								</div>
								<a href="<?= $this->Url->build(["controller" => "Messages","action" => "detail",$value['data']['conversation_replies'][0]['conversation_id']]); ?>">
									<h1><?= str_replace(['{user}'], ['<b>'.$value['data']['conversation_replies'][0]['user']['first_name']." ".$value['data']['conversation_replies'][0]['user']['last_name'].'</b>'], $value['activity_title']) ?></h1>
									<P>&nbsp;</P>
								</a>
								<div class="post-time"><?= $this->Custom->getTimeAgo($value['timestamp']).' ago'; ?></div>
							</div> 
							<?php
						}
						elseif($value['activity_id'] == 2){
							?>
							<div class="message-panel messageRel" rel="<?= $value['timestamp']; ?>">
								<div class="thumnil-block">
									<a href="javascript:void(0);" class="thum-img">
										<?php
				                            $proImage = $this->Custom->getProfileImage($value['data']['user']['image'],USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
								</div>
								<a href="<?= $this->Url->build(["controller" => "Recipes","action" => "details",$value['data']['recipe']['id']]); ?>">
									<h1><?= str_replace(['{user}','{recipe}'], ['<b>'.$value['data']['user']['first_name']." ".$value['data']['user']['last_name'].'</b>', '<b>'.$value['data']['recipe']['title'].'</b>'], $value['activity_title']) ?></h1>
									<P>&nbsp;</P>
								</a>
								<div class="post-time"><?= $this->Custom->getTimeAgo($value['timestamp']).' ago'; ?></div>
							</div> 
							<?php
						}
						elseif($value['activity_id'] == 3){
							?>
							<div class="message-panel messageRel" rel="<?= $value['timestamp']; ?>">
								<div class="thumnil-block">
									<a href="javascript:void(0);" class="thum-img">
										<?php
				                            $proImage = $this->Custom->getProfileImage($value['data']['user']['image'],USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
								</div>
								<a href="<?= $this->Url->build(["controller" => "Recipes","action" => "details",$value['data']['recipe']['id']]); ?>">
									<h1><?= str_replace(['{user}','{recipe}'], ['<b>'.$value['data']['user']['first_name']." ".$value['data']['user']['last_name'].'</b>', '<b>'.$value['data']['recipe']['title'].'</b>'], $value['activity_title']) ?></h1>
									<P>&nbsp;</P>
								</a>
								<div class="post-time"><?= $this->Custom->getTimeAgo($value['timestamp']).' ago'; ?></div>
							</div> 
							<?php
						}
						elseif($value['activity_id'] == 4){
							?>
							<div class="message-panel messageRel" rel="<?= $value['timestamp']; ?>">
								<div class="thumnil-block">
									<a href="javascript:void(0);" class="thum-img">
										<?php
				                            $proImage = $this->Custom->getProfileImage($value['data']['user']['image'],USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
								</div>
								<a href="<?= $this->Url->build(["controller" => "Recipes","action" => "details",$value['data']['recipe']['id']]); ?>">
									<h1><?= str_replace(['{user}','{comment}','{recipe}'], ['<b>'.$value['data']['user']['first_name']." ".$value['data']['user']['last_name'].'</b>','"'.substr($value['data']['comment'], 0, 100).'"', '<b>'.$value['data']['recipe']['title'].'</b>'], $value['activity_title']) ?></h1>
									<P>&nbsp;</P>
								</a>
								<div class="post-time"><?= $this->Custom->getTimeAgo($value['timestamp']).' ago'; ?></div>
							</div> 
							<?php
						}
						elseif($value['activity_id'] == 5){
							?>
							<div class="message-panel messageRel" rel="<?= $value['timestamp']; ?>">
								<div class="thumnil-block">
									<a href="javascript:void(0);" class="thum-img">
										<?php
				                            $proImage = $this->Custom->getProfileImage($value['data']['user']['image'],USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
								</div>
								<a href="<?= $this->Url->build(["controller" => "Feeds","action" => "index"]); ?>">
									<h1><?= str_replace(['{user}','{post}'], ['<b>'.$value['data']['user']['first_name']." ".$value['data']['user']['last_name'].'</b>', '<b>'.$value['data']['feed']['title'].'</b>'], $value['activity_title']) ?></h1>
									<P>&nbsp;</P>
								</a>
								<div class="post-time"><?= $this->Custom->getTimeAgo($value['timestamp']).' ago'; ?></div>
							</div>
							<?php
						}
						elseif($value['activity_id'] == 6){
							?>
							<div class="message-panel messageRel" rel="<?= $value['timestamp']; ?>">
								<div class="thumnil-block">
									<a href="javascript:void(0);" class="thum-img">
										<?php
				                            $proImage = $this->Custom->getProfileImage($value['data']['user']['image'],USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
								</div>
								<a href="<?= $this->Url->build(["controller" => "Feeds","action" => "index"]); ?>">
									<h1><?= str_replace(['{user}','{comment}','{post}'], ['<b>'.$value['data']['user']['first_name']." ".$value['data']['user']['last_name'].'</b>','"'.substr($value['data']['comment'], 0, 100).'"', '<b>'.$value['data']['feed']['title'].'</b>'], $value['activity_title']) ?></h1>
									<P>&nbsp;</P>
								</a>
								<div class="post-time"><?= $this->Custom->getTimeAgo($value['timestamp']).' ago'; ?></div>
							</div> 
							<?php
						}
						elseif($value['activity_id'] == 7){
							?>
							<div class="message-panel messageRel" rel="<?= $value['timestamp']; ?>">
								<div class="thumnil-block">
									<a href="javascript:void(0);" class="thum-img">
										<?php
				                            $proImage = $this->Custom->getProfileImage($value['data']['user']['image'],USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
								</div>
								<a href="<?= $this->Url->build(["controller" => "Feeds","action" => "index"]); ?>">
									<h1><?= str_replace(['{user}','{post}'], ['<b>'.$value['data']['user']['first_name']." ".$value['data']['user']['last_name'].'</b>', '<b>'.$value['data']['feed']['title'].'</b>'], $value['activity_title']) ?></h1>
									<P>&nbsp;</P>
								</a>
								<div class="post-time"><?= $this->Custom->getTimeAgo($value['timestamp']).' ago'; ?></div>
							</div>
							<?php
						}
						elseif($value['activity_id'] == 8){
							?>
							<div class="message-panel messageRel" rel="<?= $value['timestamp']; ?>">
								<div class="thumnil-block">
									<a href="javascript:void(0);" class="thum-img">
										<?php
				                            $proImage = $this->Custom->getProfileImage($value['data']['image'],USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
								</div>
								<a href="<?= $this->Url->build(["controller" => "Trainers","action" => "home"]); ?>">
									<h1><?= str_replace(['{user}','{email_address}', '{phone_number}','{membership_title}'], ['<b>'.$value['data']['first_name']." ".$value['data']['last_name'].'</b>', '<b>'.$value['data']['email'].'</b>','<b>'.$value['data']['mobile'].'</b>',(isset($value['data']['user_subscriptions']) && !empty($value['data']['user_subscriptions']))?'<b>'.$value['data']['user_subscriptions']['subscriptions']['s_name'].'</b>':'<b>Not paid yet</b>'], $value['activity_title'] ) ?></h1>
									<P>&nbsp;</P>
								</a>
								<div class="post-time"><?= $this->Custom->getTimeAgo($value['timestamp']).' ago'; ?></div>
							</div>
							<?php
						}
					}
				} ?>
			</div>
		</div>
		<?= $this->element('right_sidebar'); ?>
	</div>
</div>