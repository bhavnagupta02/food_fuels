<!-- dashboard title Start -->
<?= $this->element('c_breadcrumb'); ?>
<!-- dashboard title End -->
<div class="main-content row">
	<div class="container">
		<?= $this->element('left_sidebar'); ?>
		<div class="middle-content message-content">
			<div class="heading">Messages</div>
			<div class="message-block">
				<?php 
				if(isset($messages) && !empty($messages)){
					foreach ($messages as $key => $value) {
						if($value['user_id'] == $userDetails->Users['id'])
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
								<p><?= $value['reply']; ?></p>
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
								<p><?= $value['reply']; ?></p>
								<div class="post-time"><?= date('M dS, g:ia', $value['timestamp']) ?></div>
							</div> 
							<?php
						}	
					}
				} ?>
				<div class="message-panel reply">
					<div class="thumnil-block">
						<a href="javascript:void(0);" class="thum-img">
							<?php
								$msgConversationFirst = '';
								$msgLastReplyId = '';

								if(isset($messages) && !empty($messages)){
							    	$msgConversationFirst = $messages[0]['conversation_id'];
							    	$msgLastReplyId = $messages[count($messages)-1]['timestamp'];
								}
	                            $proImage = $this->Custom->getProfileImage($this->request->session()->read('Auth.User.image'),USER_THUMB);
	                            echo $this->Html->image($proImage);
	                        ?>
						</a>
					</div>
					<h1>
					<?= $this->request->session()->read('Auth.User.first_name')." ".$this->request->session()->read('Auth.User.last_name'); ?>
					</h1>
					<?php echo $this->Form->create('ConversationReply',['class' => 'payment-form','id'=>'MessageForm']); ?>
					<?php $this->Form->templates([
							    'label' => false
							]); ?>
					<div>
						<?= $this->Form->input('reply',array('id' => 'MessageForm', 'class' => 'form-control height75px','maxlength' => 550,'id' => 'ckeditor','type' => 'textarea', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
						<?php echo $this->Form->hidden('conversation_id',array('value'=>$msgConversationFirst));
						      echo $this->Form->hidden('last_reply_id',array('value'=>$msgLastReplyId));
						      echo $this->Form->submit('Send',['class' => 'green-btn']); ?>
					</div>
					<?php echo $this->Form->end(); ?>
				</div> 
			</div>
		</div>
		<?= $this->element('right_sidebar'); ?>
	</div>
</div>
<script type="text/javascript">
	var base_url =
	$('input[name="reply"]').focusout(function(){
		var lastMessageOn = $('.message-block .messageRel:last').attr('rel');
		$('input[name="last_reply_id"]').val(lastMessageOn);
	});
	
	function appendHtmlData(htmlData){
		if(htmlData){ 
			$('textarea[name="reply"]').val('');
			$('div.reply').before(htmlData);
		}
		else{
			alert("Sorry, message sending failed.");
		}
	}

	$(document).ready(function(){
		setInterval(function() {
			var lastMessageOn = $('.message-block .messageRel:last').attr('rel');
			var conversationId = $('input[name="conversation_id"]').val();
			$.ajax({
		    	'url': '<?= $this->Url->build(["controller" => "messages","action" => "get_message",$value['id']]); ?>',
		    	'async': true,
		    	'data' : {'last_reply_id':lastMessageOn,'conversation_id':conversationId},
		    	'type': 'post',
		    	'success': function(data){
		    		if(data)
		    		{
		    			$('div.reply').before(data);
		    			var lastMessageOn = $('.message-block .messageRel:last').attr('rel');
						$('input[name="last_reply_id"]').val(lastMessageOn);
		    		}
		    	}
		    });
		}, 5000);
	});

	if($('#MessageForm').length){
        $('#MessageForm').submit(function(event){
            event.preventDefault();
            $.ajax({
                'url'       :   base_url+'messages/add_message',
                'type'      :   'post',
                'async'     : 	false,
                'data'      :   $(this).serialize(),
                'success'   :   function(data){ 
                	if(data)
		    		{	
		    			$('textarea[name="reply"]').val('');
		    			$('div.reply').before(data);
		    			var lastMessageOn = $('.message-block .messageRel:last').attr('rel');
						$('input[name="last_reply_id"]').val(lastMessageOn);
		    		}
                }
            });
        });
    }
</script>