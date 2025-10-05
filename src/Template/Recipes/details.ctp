<!-- dashboard title Start -->
<div class="breadcrumb row">
	<div class="container">
		<h2><i class="icons icon-spoon"></i>Recipies</h2>
		<?= $this->Html->link('+Add Recipe',['action' => 'add'],['class' => 'add-btn']); ?>
	</div>
</div>
<!-- dashboard title End -->

<div class="main-content detail-page row">
	<div class="container">
	<?php 
	if(isset($recipeDetails) && !empty($recipeDetails)){ ?>
	<div class="detail-row">
		<h1><?= $recipeDetails['title'] ?></h1>
		<div class="row-4">
			<div class="recipe-img">
				<?php
					$proImage = $this->Custom->getDishImage((isset($recipeDetails['upload_images'][0]['name']))?$recipeDetails['upload_images'][0]['name']:"");
                    echo $this->Html->image($proImage);
                ?>
			</div>
			<ul class="social-link">
				<li><?= $this->Html->link('<i class="icon icon-like-lg"></i>Like this','javascript:void();',['escape' => false, 'id' => 'likeme']); ?></li>
				<li><?= $this->Html->link('<i class="icon icon-share-lg"></i>Share this','javascript:void();',['escape' => false, 'id' => 'shareme']); ?></li>
			</ul>
		</div>
		<div class="row-8 description">
			<p><?= $recipeDetails['description'] ?></p>
			<p class="note"><?= $recipeDetails['notes'] ?></p>
			<ul class="description-actn">
				<li><a href="#"><i class="icon icon-heart-md"></i></a><span id="likeCount"> <?= $recipeDetails['like_count']." Likes"; ?></span></li>
				<li><a href="#"><i class="icon icon-comments-md"></i></a><span id="commentCount">  <?= $recipeDetails['comment_count']." Comments"; ?></span> </li>
				<li><a href="#"><i class="icon icon-share-md"></i></a><span id="shareCount">  <?= $recipeDetails['share_count']." Shares"; ?></span></li>
			</ul>
			<div class="description-list">
				<h5><i class="icon ingredient-icon"></i>Ingredients</h5>
				<ul class="ingredient-list">
					<?= $recipeDetails['ingredients'] ?>
				</ul>
			</div>
			<div class="description-list">
				<h5><i class="icon instruction-icon"></i>Instructions</h5>
				<ul class="instru-list">
					<?= $recipeDetails['directions'] ?>
				</ul>
			</div>
			<?php if($recipeDetails['serving_size']){ ?>
			<div class="description-list">
				<h5><i class="icon instruction-icon"></i>Serving Size</h5>
				<ul class="instru-list">
					<?= $recipeDetails['serving_size'] ?>
				</ul>
			</div>
			<?php } ?>
			<?php if($recipeDetails['preparation_time']){ ?>
			<div class="description-list">
				<h5><i class="icon instruction-icon"></i>Preparation time</h5>
				<ul class="instru-list">
					<?= $recipeDetails['preparation_time'] ?>
				</ul>
			</div>
			<?php } ?>
			
		</div>
	</div>
	<?php } ?>

	<div style="clear:both;"></div>
	
	<div class="recipe-container feed-block feed-block-2 width45 fleft">
		<div class="meal-heading"> <strong>More Recipes Like This</strong> </div>
		<div class="recipeholder">
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
										<li><a href="#"><i class="icon icon-heart"></i></a><?= $value['like_count'] ?></li>
										<li><a href="#"><i class="icon icon-comment"></i></a><?= $value['comment_count'] ?></li>
										<li><a href="#"><i class="icon icon-share"></i></a><?= $value['share_count'] ?></li>
									</ul>
								</div>
							</div>
							<div class="item-cont">
								<div class="thum">
									<?php
			                            $proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
			                            echo $this->Html->image($proImage);
			                        ?>
								</div>
								<a href="#"><?= $value['user']['username']; ?></a>
							</div>
						</div>			
						<?php
					}
				}
			?>			
		</div>
	</div>
	<div class="recipe-container feed-block feed-block-2 width45 fright">
		<div class="meal-heading"> <strong>Comments</strong> </div>
		<div class="comments-block">
				<div class="myCommentDiv">
					<?php
					if(isset($recipeDetails['comments']) && !empty($recipeDetails['comments'])){
						if(count($recipeDetails['comments']) > 2){
							?>
							<div class="load-comment">
								<?= $this->Html->link('Load more comments','javascript:void(0)',['rel'=>1, 'onclick' => 'loadMore(this)']); ?>
							</div>
						<?php
						}
						foreach ($recipeDetails['comments'] as $keyComm => $valueComm) {
							$hideClass = "";
							if((count($recipeDetails['comments'])-3) >= $keyComm){
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
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var site_url 	= '<?= $this->request->webroot ?>';
	var recipeId 	= '<?= $recipeDetails["id"]  ?>';
	var type 		= 1;
		
	$('#likeme').click(function(){
		$.ajax({
				url: site_url+'feeds/likeme',
				data:{ recipe_id: recipeId,type: type},
				'type'      :   'post',
				'dataType'  :   'json',
				success: function(response)
				{
					if(response.status)
					{	
						$('#likeCount').html(response.likes+' Likes');
					}
				}
		});
	});

	$('#shareme').click(function(){
		if(confirm('Are you sure you want to share this recipe?')){
			$.ajax({
				url: site_url+'feeds/shareme',
				data:{ recipe_id: recipeId,type: type},
				'type'      :   'post',
				'dataType'  :   'json',
				success: function(response)
				{
					if(response.status)
					{
						$('#shareCount').html(response.shares+' Shares');
						alert('This recipe has successfully shared.');
					}
					else{
						alert('Dish has already shared.');
					}
				}
			});
		}
	});

	$(".textClass").keyup(function(event){
		if(event.keyCode == 13 && $(this).val() != ''){
			var obj 		= this;
			var textClass	= $(this).val();
			$.ajax({
				url: site_url+'feeds/commentme',
				data:{ recipe_id: recipeId,type: type,comment: textClass},
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
});

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
</script>