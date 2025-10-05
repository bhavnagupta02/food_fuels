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
						$last_key = key( array_slice( $value->conversation_replies, -1, 1, TRUE ) );
						?>
							<div class="message-panel <?php echo ($value->conversation_replies[$last_key]['seen']!=1 && $userId != $value->conversation_replies[$last_key]['user_id']) ? 'new-message' : '';?>">
								<div class="thumnil-block">
									<a href="<?= $this->url->build(array('controller'=>'messages','action'=>'detail',$value['id'])); ?>" class="thum-img">
										<?php
				                            $proImage = $this->Custom->getProfileImage($other_person->image,USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
								</div>
								<h1><?= $this->Html->link($other_person['first_name']." ".$other_person['last_name'],array('controller'=>'messages','action'=>'detail',$value['id'])); ?></h1>
								<p><?= nl2br($value->conversation_replies[$last_key]['reply']); ?></p>
								<div class="post-time"><?= date('M dS, g:ia', $value->conversation_replies[$last_key]['timestamp']) ?></div>
							</div>
						<?php
					}
				} ?>
			</div>
		</div>
		<?= $this->element('right_sidebar'); ?>
	</div>
</div>