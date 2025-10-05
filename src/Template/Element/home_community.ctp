<!--community feed section start-->
<div class="community-row dashbord-row row">
    <div class="container">
        <div class="one_half">
            <div class="heading"> 
                community feed  
				<?= $this->Html->link('',['controller' => 'feeds', 'action' => 'index'],array('class' => 'arrow-icon')); ?>
            </div>

            <?php
                if(isset($feedList) && !empty($feedList)){
                    foreach ($feedList as $key => $value) {
                        if($value['activity_id']==1){
                            ?>
                            <div class="feed-block">
                                <div class="feed-head">
                                    <div class="thumnil">
                                        <?php 
                                            $proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
                                            echo $this->Html->image($proImage);
                                        ?>
                                    </div>
                                    <p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> posted a new photo.</p>
                                    <small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
                                </div>
                                <div class="feed-content">
                                    <div class="video-sec">
                                        <a href="#">
                                            <?php
                                                $proImage = $this->Custom->getMyPics((isset($value['upload_images'][0]['name']))?$value['upload_images'][0]['name']:"");
                                                echo $this->Html->image($proImage,['width' => 150]);
                                            ?>
                                        </a>
                                    </div>
                                    <p><?= $value['title']; ?></p>
                                </div>
                            </div>
                            <?php
                        }
                        elseif($value['activity_id']==2){
                            ?>
                            <div class="feed-block">
                                <div class="feed-head">
                                    <div class="thumnil">
                                        <?php 
                                            $proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
                                            echo $this->Html->image($proImage);
                                        ?>
                                    </div>
                                    <p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> posted a new video.</p>
                                    <small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
                                </div>
                                <div class="feed-content">
                                    <div class="video-sec">
                                        <a href="#">
                                            <?php
                                                $proVideoUrl = $this->Custom->getMyVideos((isset($value['upload_images'][0]['name']))?$value['upload_images'][0]['name']:"");
                                            ?>
                                            <?php if(!empty($proImage)){ ?>
                                            <video width="120" controls>
                                              <source src="<?= $proVideoUrl ?>" type="video/mp4">
                                              Your browser does not support HTML5 video.
                                            </video>
                                            <?php } ?>
                                        </a>
                                    </div>
                                    <p><?= $value['title']; ?></p>
                                </div>
                            </div>
                            <?php
                        }
                        elseif($value['activity_id']==3){
                            ?>
                            <div class="feed-block">
                                <div class="feed-head">
                                    <div class="thumnil">
                                        <?php 
                                            $proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
                                            echo $this->Html->image($proImage);
                                        ?>
                                    </div>
                                    <p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> shared a new recipe.</p>
                                    <small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
                                </div>
                                <div class="feed-content">
                                    <div class="video-sec">
                                        <a href="<?= $this->Url->build(["controller" => "Recipes","action" => "details",$value['recipe']['id']]); ?>">
                                            <?php
                                                $proImage = $this->Custom->getDishImage((isset($value['recipe']['upload_images'][0]['name']))?$value['recipe']['upload_images'][0]['name']:"");
                                                echo $this->Html->image($proImage);
                                            ?>
                                        </a>
                                    </div>
                                    <p><?= $value['recipe']['title']; ?></p>
                                </div>
                            </div>
                            <?php
                        }
                        elseif($value['activity_id']==5){
                            ?>
                            <div class="feed-block">
                                <div class="feed-head">
                                    <div class="thumnil">
                                        <?php 
                                            $proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
                                            echo $this->Html->image($proImage);
                                        ?>
                                    </div>
                                    <p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> updated his/her status.</p>
                                    <small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
                                </div>
                                <div class="feed-content">
                                    <p><?= $value['title']; ?></p>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
            ?>
        </div>
        <div class="one_half last">
			<div class="heading"> Meal Plan  <?= $this->Html->link('',['controller' => 'diets', 'action' => 'index'],array('class' => 'arrow-icon')); ?></div>
			<div class="sub-title">today</div>
			<?php ?>
			<?php if(isset($mealPlans->meals) && !empty($mealPlans->meals)){
					foreach ($mealPlans->meals as $mkey => $mValue) {
					 	?>
					 	<div class="meal-block">
							<div class="meal-heading"> <strong><?php echo $mValue['heading']." (Estimated ".Date('H:iA',strtotime($mValue['time'])).")"; ?></strong> </div>
							<div class="meal-left">
								<?= $this->Html->link('<h5>'.$mValue['title_option_1'].'</h5>','javascript:void(0)',['escape' => false]); ?>
								<p><?= $mValue['short_description_option_1'] ?></p>
								<?= "or"; ?>
								<?= $this->Html->link('<h5>'.$mValue['title_option_2'].'</h5>','javascript:void(0)',['escape' => false]);
								?>
								<p><?= $mValue['short_description_option_2'] ?></p>
							</div>
						</div>
					 	
					 	<?php
					 } ?>

			<?php } ?>
		</div>
        <?php if(isset($shoppingListData) && !empty($shoppingListData)){ ?>
        <div class="row center-align">
			<?= $this->Html->link('<i class="pdf-icon"></i> SHOPPING LIST',['controller' => 'media', 'action' => 'shopping_list', $shoppingListData['document_name']],array('class' => 'shop-btn','escape' => false)); ?>
        </div>
        <?php } ?>
    </div>
</div>
<!--community feed section end-->
<script type="text/javascript">
$(document).ready(function(){
    $('.feed-block').click(function(){
        window.location.href = '<?= $this->Url->build(["controller" => "feeds","action" => "index"]); ?>';
    });
    $('.meal-block').click(function(){
        window.location.href = '<?= $this->Url->build(["controller" => "diets","action" => "index"]); ?>';
    });
});
</script>