<style type="text/css">
.modal-backdrop.in{display:none !important;}
ul.imgPreviewUl li{ width: 500px; float: none; display: inline-block; }
</style>
<div class="main-content row">
	<div class="container">
		<?= $this->element('left_sidebar'); ?>
		<div class="middle-content message-content">
			<div class="heading">Shared Videos</div>
			<div class="plans-container payment-container">
				<?= $this->Form->create('Feed', array('class' => 'payment-form', 'id' => 'FeedForm','type' => 'file'));
					echo $this->Flash->render();
				?>
				<?= $this->Form->input('title',array('class' => 'form-control height75px', 'required', 'type' => 'textarea', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
				<div>
	                <div class="form-row">
	                    <div class="image-upload">
	                        <div class="container">
	                            <div class="one_half">
	                                <div class="btn-group">
	                                    <div class="upload-file">
	                                        Choose Video
	                                        <div class="input file">
	                                            <?php echo $this->Form->input('UploadImage', array('type' => 'file', 'accept' => 'video/*')); ?>
	                                        </div>  
	                                    </div>
	                                </div>
	                                <ul class="imgPreviewUl">
	                                    **Note Video size should be less then 10 mb.<br>
	                                    	   Mp4,flv,3gp or mpeg files are allowed.
	                                </ul>
	                            </div>  
	                        </div>
	                    </div>
	                </div>
	            </div>
				<div class="row center-align mrg-40">
					<?= $this->Form->submit('Share This Post',['class' => 'green-btn']); ?>
				</div>	
				<?= $this->Form->end(); ?>
			</div>
			<?php 
				if(isset($feedList) && !empty($feedList)){
					foreach ($feedList as $key => $value) {
						if($value['activity_id']==2){
							?>
							<div class="feed-block feed-block-2" rel="<?= $value['id'] ?>">
								<div class="feed-head">
									<div class="thumnil">
										<?php 
											$proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
					                        echo $this->Html->image($proImage);
										?>
									</div>
									 <p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> posted a new video.</p>
									 <small><?php //echo $this->Custom->getTimeAgo($value['created']); ?></small>
									 <?php echo $this->Html->link('Delete','javascript:void(0)',['class' => 'deleteBtn', 'rel' => $value['id'] ]); ?>
								</div>
								<div class="feed-content">
									<p><?= $value['title']; ?></p>
									<div class="video-row">
										<?php
											$proVideoUrl = $this->Custom->getMyVideos((isset($value['upload_images'][0]['name']))?$value['upload_images'][0]['name']:"");
				                        ?>
				                        <?php if(!empty($proImage)) ?>
										<video width="600" controls>
										  <source src="<?= $proVideoUrl ?>" type="video/mp4">
										  Your browser does not support HTML5 video.
										</video>
									</div>
								</div>
								<div class="comments-block">
									<div class="reviews-row">
										<a href="javascript:void(0);" class="like">
											<i class="icons icon-like"></i><span id="likeCount<?=$value['id']?>"><?= $value['like_count']." Likes"; ?></span>
										</a>
											 - 
										<a href="javascript:void(0);"> 
											<span id="commentCount<?=$value['id']?>"><?= $value['comment_count']." Comments"; ?></span>
										</a>
										<!--
											 - 
										<a href="javascript:void(0);">
											<span id="shareCount<?=$value['id']?>"><?= $value['share_count']." share"; ?></span>
										</a>
										-->
									</div>
									<div class="myCommentDiv">
										<?php
										if(isset($value['comments']) && !empty($value['comments'])){
											if(count($value['comments']) > 2){
												?>
												<div class="load-comment">
													<?= $this->Html->link('Load more comments','javascript:void(0)',['rel'=>1, 'onclick' => 'loadMore(this)']); ?>
												</div>
												<?php
											}
											foreach ($value['comments'] as $keyComm => $valueComm) {
												$hideClass = "";
												if((count($value['comments'])-3) >= $keyComm){
													$hideClass = "hide";
												}
												?>
												<div class="feed-head <?= $hideClass ?>">
													<div class="thumnil">
														<?php
								                            $proImage = $this->Custom->getProfileImage($valueComm['user']['image'],USER_THUMB);
								                            echo $this->Html->image($proImage);
								                        ?>
													</div>
													<p>
														<a href="#">
															<?= $valueComm['user']['first_name']." ".$valueComm['user']['last_name']; ?>
														</a>
														<?= $valueComm['comment']; ?>
													</p>
													<small><?= $this->Custom->getTimeAgo($valueComm['timestamp']).' ago'; ?></small>
												</div>
												<?php
											}
										}
										?>
									</div>	
									<div class="feed-head rply-row">
										<div class="thumnil">
											<?php 
											$proImage = $this->Custom->getProfileImage($this->request->session()->read('Auth.User.image'),USER_THUMB);
                            				echo $this->Html->image($proImage);
											?>
										</div>

										<input type="text" placeholder="Leave a comment..." class="textClass input-control">
									</div>
								</div>
							</div>
							<?php
						}
					}
				}
			?>
		</div>
		<?= $this->element('right_sidebar'); ?>
	</div>	
</div>

<?= $this->element('crop_model'); ?>

</section>
<script type="text/javascript">
$('document').ready(function()
{
	$('.deleteBtn').click(function(){
    	var idVal = $(this).attr('rel');
    	if(idVal){
    		if(confirm('Are you sure you want to delete this post?')){
    			window.location.href = '<?= $this->Url->build(["controller" => "Coachcommunity","action" => "delete"]); ?>/'+idVal;
    		}
    	}
    });

	$('.icons.icon-like').click(function(){
	
		var site_url 	= '<?= $this->request->webroot ?>';
		var obj 		= this;
		var feedId 		= $(this).closest('div[class^="feed-block"]').attr('rel');
		var type 		= 2;
		
		$.ajax({
			url: site_url+'Coachcommunity/likeme',
			data:{ feed_id: feedId,type: type},
			'type'      :   'post',
			'dataType'  :   'json',
			success: function(response)
			{
				if($(obj).hasClass('active')){
					$(obj).removeClass('active');
				}
				else{
					$(obj).addClass('active');
				}

				if(response.status)
				{	
					$('#likeCount'+feedId).html(response.likes+' Likes');
				}
			}
		});
	});
	
	$(".textClass").keyup(function(event){
		if(event.keyCode == 13 && $(this).val() != ''){
	    	var site_url 	= '<?= $this->request->webroot ?>';
			var obj 		= this;
			var feedId 		= $(this).closest('div[class^="feed-block"]').attr('rel');
			var type 		= 2;
			var textClass	= $(this).val();
			$.ajax({
				url: site_url+'Coachcommunity/commentme',
				data:{ feed_id: feedId,type: type,comment: textClass},
				'type'      :   'post',
				'dataType'  :   'html',
				success: function(response)
				{
					$(obj).closest('div[class^="comments-block"]').find('div[class^="myCommentDiv"]').html(response);
					//$('#commentCount'+feedId).html(response.comment+' Comments');
					$(obj).val('');
				}
			});   
	    }
	});

	$("#uploadimage").change(function (evt) {
		var extensionName = get_extension($('#uploadimage').val());
		var checkVal = $.inArray(extensionName, ['mp4','flv','3gp','mpeg']);
        
		if(!($('#uploadimage')[0].files[0].size < 10485760 || checkVal == -1)) {
        	// 10 MB (this size is in bytes)
	        //Prevent default and display error
	        alert("File is wrong type or over 10Mb in size! Only mp4,flv,3gp or mpeg files are allowed.");
	        e.preventDefault();
	    }
    });

	function get_extension(filename) {
	    var parts = filename.split('.');
	    return parts[parts.length - 1].toLowerCase();
	}

	function loadMore(obj){
		var mainObj = obj;
		$(mainObj).closest('div[class^="myCommentDiv"]').find('div[class^="feed-head"]').each(function(){
			if($(mainObj).attr('rel') == 1){
				if($(this).hasClass('hide')){
					$(this).removeClass('hide');
				}
			}
		});
	}

});
</script>