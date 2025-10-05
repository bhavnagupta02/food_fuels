<!-- login_header element start -->

<?php 
	$action 		= strtolower($this->request->action);
	$controller 	= strtolower($this->request->controller);
	$communityClass	= $fuelClass = $mealPlanClass = $leaderboardClass = $recipeClass = '';
	$combiNation 	= $controller."-".$action;

	switch ($combiNation) {
		case 'feeds-index':
		case 'feeds-my_videos':
		case 'feeds-my_photos':
		case 'messages-index':
			$communityClass = 'current-menu-item';
			break;

		case 'users-home':
			$fuelClass = 'current-menu-item';
			break;

		case 'diets-index':
			$mealPlanClass = 'current-menu-item';
			break;	

		case 'feeds-index':
			$leaderboardClass = 'current-menu-item';
			break;

		case 'recipes-index':
			$recipeClass = 'current-menu-item';
			break;	

		case 'users-board':
			$leaderboardClass = 'current-menu-item';
			break;	
		case 'pages-faq_list':
			$faqClass = 'current-menu-item';
			break;					
		/*
		default:
			$fuelClass = 'current-menu-item';
			break;
		*/	
	}
?>
<div class="nav row">
	<div class="container">
		<?= $this->Html->link($this->Html->image('logo.png'),'/',array('escape'=>false, 'class' => array('logo'))); ?>
		<span class="mMenu" style="display:none;">
			<?= $this->Html->link(__('Toggle menu'),'#',array('id' => 'responsive-menu')); ?>
		</span>
		<div class="nav-menu">
			<ul class="menu">
				<li class="<?= $fuelClass ?>"><?= $this->Html->link(__('Fuel Gauge'),['controller' => 'users', 'action' => 'home']); ?></li>
				<li class="<?= $mealPlanClass ?>"><?= $this->Html->link(__('Meal Plan'),['controller' => 'diets', 'action' => 'index']); ?></li>
				<li class="<?= $communityClass ?>"><?= $this->Html->link(__('Community'),['controller' => 'feeds', 'action' => 'index']); ?></li>
				<li class="<?= $recipeClass ?>"><?= $this->Html->link(__('Recipes'),['controller' => 'recipes', 'action' => 'index']); ?></li>
				<li class="<?= $leaderboardClass ?>"><?= $this->Html->link(__('leaderboard'),['controller' => 'users', 'action' => 'board']); ?>
				
				<?php if($this->request->session()->read('Auth.User.group_id') == CLIENTGROUPID){ ?>
				<!--<li class=""><?= $this->Html->link(__('Coach Corner'),['controller' => 'mycoach']); ?></li></li>-->
				<?php } ?>

				<?php //if($this->request->session()->read('Auth.User.group_id') == USERGROUPID){ ?>
				<!--<li class=""><?= $this->Html->link(__('Coach Community'),['controller' => 'Coachcommunity', 'action' => 'index']); ?></li>-->
				<?php// } ?>

				<li class="<?= $faqClass ?>"><?= $this->Html->link(__('FAQs'),['controller' => 'Pages', 'action' => 'faq_list']); ?></li>
			</ul>
			
			<div class="user-info">
				<div class="up-info row margin0">
					<div class="icon">
						<?php
	                        $proImage = $this->Custom->getProfileImage($this->request->session()->read('Auth.User.image'),USER_THUMB);
	                        echo $this->Html->image($proImage);
	                    ?>
					</div>
					<?php $userName = $this->request->session()->read('Auth.User.first_name')." ".$this->request->session()->read('Auth.User.last_name'); ?>
					<p><?= $userName ?></p>
				</div>
				<ul class="user-menu">


					<li>
					<?= $this->Html->link(__('Coach Community'),['controller' => 'Coachcommunity', 'action' => 'index']); ?>
					

					<?php 
						/*if($this->request->session()->read('Auth.User.group_id') == USERGROUPID){
					 		echo $this->Html->link(__('Coach Community'),['controller' => 'Coachcommunity', 'action' => 'index']);
						}*/

						/*if($this->request->session()->read('Auth.User.group_id') == USERGROUPID){
					 		echo $this->Html->link(__('Coach Community'),['controller' => 'Coach', 'action' => 'coach_private']);
						}*/
					
					?>
					</li>

					<li><?= $this->Html->link(__('My Notifications'),['controller' => 'notifications', 'action' => 'index']); ?></li>
					<li>
					<?php
						if($this->request->session()->read('Auth.User.group_id') == CLIENTGROUPID)
					 		echo $this->Html->link(__('My Profile'),['controller' => 'users', 'action' => 'my_profile']);
					 	elseif($this->request->session()->read('Auth.User.group_id') == USERGROUPID)
					 		echo $this->Html->link(__('My Profile'),['controller' => 'trainers', 'action' => 'my_profile']);
					?>
					</li>
					
					<li><?= $this->Html->link(__('My Inbox'),['controller' => 'messages', 'action' => 'inbox']); ?></li>
					<li><?= $this->Html->link(__('My Recipe'),['controller' => 'recipes', 'action' => 'my_recipe']); ?></li>
					<li><?= $this->Html->link(__('Change Password'),['controller' => 'users', 'action' => 'change_password']); ?></li>
					<!--li><?= $this->Html->link(__('Review Coach'),['controller' => 'coach', 'action' => 'review_rating',$this->request->session()->read('Auth.User.trainer_id')]); ?></li-->
					<li><?= $this->Html->link(__('Logout'),['controller' => 'users', 'action' => 'logout']); ?></li>
				</ul>
			</div>
			
			<style>
			.countdown {
    display: table-cell;
    font-weight: normal;
}
.countdown .item {
  border: 2px solid #63FAFA;
  border-radius: 50%;
  color:#63FAFA;
  display: inline-block;
  font-family: arial;
  font-size: 18px;
  font-weight: 500;
  height: 50px;
  line-height: 35px;
  margin-right: 5px;
  overflow: hidden;
  padding: 0 10px;
  position: relative;
  text-align: center;
  vertical-align: bottom;
  width: 50px;
  background:#666;
}
.countdown .item-ss {
    font-size: 50px;
    line-height: 70px;
}
.countdown .item::after {
  content: "";
  display: block;
  height: 1px;
  left: 0;
  position: absolute;
  width: 95%;
}
.countdown .label {
  bottom: 4px;
  color:#63FAFA;
  display: block;
  font-family: arial;
  font-size: 10px;
  line-height: normal;
  position: absolute;
  right: 0;
  text-transform: uppercase;
  width: 100%;
}
span.item.item-hh{
	/*display:none;*/
}
span.item.item-mm{
	/*display:none;*/
}
span.label.label-dd{
	/*display:none;*/
}


.extend-membership{ 
  position: fixed; 
  top:auto;
  bottom:0;
  padding:0 0; 
  z-index: 999;
 
}
.extend-membership a.extend-membership-link-01 { 
  display: block; 
  background:#71A442 ; 
  color: #fff; 
  font-family: Arial, sans-serif; 
  font-size: 15px; 
  font-weight: bold; 
  text-decoration: none; 
  border-bottom: 2px solid #63FAFA;
  border-left:2px solid #63FAFA;
  border-top:2px solid #63FAFA;
  border-right:2px solid #63FAFA;
  border-top-left-radius:5px;
  border-bottom-left-radius:5px;
  width:130px;
  height:130px;
  border-radius:100%;
  text-align:center;
  padding-top:40px;
  line-height:22px;
  text-transform:uppercase;
}
.extend-membership a.extend-membership-link-01 span{ float:right; margin-left:10px; display:inline-block; background:url(../images/right-arrw.png) no-repeat scroll 0 center; width:13px; height:20px;}

			</style>
			 
			
				<?php
					if(!isset($remainingDays) || $remainingDays == 0){
						$remainingDays = 1;
					} else{
						$remainingDays = $remainingDays+1;
					}
						$totalDays =  "+".$remainingDays."days";
						$today = gmdate("Y-m-d H:i:s");  
						 $date = strtotime(gmdate("Y-m-d H:i:s", strtotime($today)) . $totalDays);
						 
					
				?>
			<?php
			/*
			 if($remainingDays <= 7){
			 ?>
			 	<script>
				 	 var ask = window.confirm("Please rate your Coach now.");
						if (ask) {
							document.location.href = "https://www.foodfuels.com/coach/review_rating/<?php echo $this->request->session()->read('Auth.User.trainer_id'); ?>";

						}
				 </script>
			 <?php } */?>
			<div style="padding: 5px 1px 5px 10px; clear: both; float: right;"><time><?php echo date("Y-m-d", $date); ?></time></div>
			
			<?php //if($this->request->session()->read('Auth.User.group_id') == CLIENTGROUPID && $subscriptionStatus == 'renew') {?>
			<?php if($this->request->session()->read('Auth.User.group_id') == CLIENTGROUPID) {?>
				<div class="extend-membership">
					<?php // echo $this->Html->link(__(''), ['controller' => 'users', 'action' => 'select_membership_to_extend'], ['class' => 'extend-membership-link']); ?>
                    
                    <a class="extend-membership-link-01" href="https://foodfuels.com/users/select_membership_to_extend">Renew<br /> Membership</a>
					
				</div>
			<?php }?>
		</div>
	</div>
</div>
<?php echo $this->Html->script('jquery.countdown.js', array('inline' => true)); ?>

  
<script>
        window.jQuery(function ($) {
            "use strict";

            $('time').countDown({
               
				with_seconds:     false,
				label_hh:     'Hrs',
				label_mm:     'Min',
				with_separators:  false,
				
            });
           

        });
		
        </script>
<!-- login_header element start -->



