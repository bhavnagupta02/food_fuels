<?php 
	if(isset($messages) && !empty($messages)){
		foreach ($messages as $key => $value) {
			if($value['user_id'] == $this->request->session()->read('Auth.User.id'))
			{ 
				?>
				<div class="message-panel messageRel" rel="<?= $value['timestamp']; ?>">
					<div class="thumnil-block">
						<a href="javascript:void(0);" class="thum-img">
							<?php
	                            $proImage = $this->Custom->getProfileImage($value->user['image'],USER_THUMB);
	                            echo $this->Html->image($proImage);
	                        ?>
						</a>
					</div>
					<h1><?= $value->user['first_name']." ".$value->user['last_name']; ?></h1>
					<p><?= nl2br($value['reply']); ?></p>
					<div class="post-time"><?= date('M dS, g:ia', $value['timestamp']) ?></div>
				</div> 
			<?php
			}
			else{
				?>
				<div class="message-panel messageRel" rel="<?= $value['timestamp']; ?>">
					<div class="thumnil-block">
						<a href="javascript:void(0);" class="thum-img">
							<?php
	                            $proImage = $this->Custom->getProfileImage($value->user['image'],USER_THUMB);
	                            echo $this->Html->image($proImage);
	                        ?>
						</a>
					</div>
					<h1><?= $value->user['first_name']." ".$value->user['last_name']; ?></h1>
					<p><?= nl2br($value['reply']); ?></p>
					<div class="post-time"><?= date('M dS, g:ia', $value['timestamp']) ?></div>
				</div> 
				<?php
			}	
		}
	} ?>