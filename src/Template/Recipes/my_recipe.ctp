<style type="text/css">
.modal-backdrop.in{display:none !important;}
ul.imgPreviewUl li{ width: 500px; float: none; display: inline-block; }
</style>
<div class="main-content row">
	<div class="container">
		<?= $this->element('left_sidebar'); ?>
		<div class="middle-content message-content recipeholder">
			<div class="heading">My Recipe</div>
			<?php 
				if(isset($recipeList) && !empty($recipeList)){
					foreach ($recipeList as $key => $value) {
						$catSlug = str_replace(' ', '-' , strtolower($value['category']['name']));
						?>
						<div class="item one_fifth <?= $catSlug ?>">
							<div class="img-block">
								<a href="<?= $this->Url->build(["controller" => "Recipes","action" => "details",$value['id']]); ?>">
									<?php
										$proImage = $this->Custom->getDishImage((isset($value['upload_images'][0]['name']))?$value['upload_images'][0]['name']:"");
			                            echo $this->Html->image($proImage);
			                        ?>
								</a>
								<div class="img-overlay">
									<h5><a href="<?= $this->Url->build(["controller" => "Recipes","action" => "details",$value['id']]); ?>"><?= $value['title'] ?></a></h5>
									<ul class="social-act">
										<!--
										<li><a href="#"><i class="icon icon-heart"></i></a><?= $value['like_count'] ?></li>
										<li><a href="#"><i class="icon icon-comment"></i></a><?= $value['comment_count'] ?></li>
										<li><a href="#"><i class="icon icon-share"></i></a><?= $value['share_count'] ?></li>
										-->
										<li><?php echo $this->Html->link('Edit',['controller' => 'recipes', 'action' => 'edit', $value['id']]); ?></li>
										<li><?php echo $this->Html->link('Delete','javascript:void()',['rel' => $value['id'],'class' => 'deleteRecipe']); ?></li>
									</ul>
								</div>
							</div>
						</div>			
						<?php
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
	$('.icons.icon-like').click(function(){
	
		var site_url 	= '<?= $this->request->webroot ?>';
		var obj 		= this;
		var feedId 		= $(this).closest('div[class^="feed-block"]').attr('rel');
		var type 		= 2;
		
		$.ajax({
			url: site_url+'feeds/likeme',
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
				url: site_url+'feeds/commentme',
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

    $('.deleteRecipe').click(function(){
    	var idVal = $(this).attr('rel');
    	if(idVal){
    		if(confirm('Are you sure you want to delete this recipe?')){
    			window.location.href = '<?= $this->Url->build(["controller" => "Recipes","action" => "delete"]); ?>/'+idVal;
    		}
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

<div class="main-content row">
	<div class="recipe-container">
		<div class="container">
			<div class="recipeholder">
				
			</div>
		</div>	
	</div>
</div>