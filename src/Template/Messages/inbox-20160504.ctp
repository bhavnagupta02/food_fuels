<!-- dashboard title Start -->
<?= $this->element('c_breadcrumb'); ?>
<!-- dashboard title End -->
<div class="main-content row">
	<div class="container">
		<?= $this->element('left_sidebar'); ?>
		<div class="middle-content message-content">
			<div class="heading">Messages</div>
			<div class="message-block">
				<?php if(isset($messages) && !empty($messages)){
					foreach ($messages as $key => $value) {
						
						if($userId == $value['sender_id'])
						{
							$other_person = $value->receiver;
						}
						else 
						{
							$other_person = $value->sender;
						}
						?>
							<div class="message-panel">
								<div class="thumnil-block">
									<a href="<?= $this->url->build(array('controller'=>'messages','action'=>'detail',$value['id'])); ?>" class="thum-img">
										<?php
				                            $proImage = $this->Custom->getProfileImage($other_person->image,USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
								</div>
								<h1><?= $this->Html->link($other_person['first_name']." ".$other_person['last_name'],array('controller'=>'messages','action'=>'detail',$value['id'])); ?></h1>
								<p><?= $value->conversation_replies['0']['reply']; ?></p>
								<div class="post-time"><?= date('M dS, g:ia', $value->conversation_replies['0']['timestamp']) ?></div>
							</div>
						<?php
					}
				} ?>
			</div>
		</div>
		<?= $this->element('right_sidebar'); ?>
	</div>
</div>