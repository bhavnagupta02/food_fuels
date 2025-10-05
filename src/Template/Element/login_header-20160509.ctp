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
				<li class="<?= $fuelClass ?>"><?= $this->Html->link(__('Fuel Guage'),['controller' => 'users', 'action' => 'home']); ?></li>
				<li class="<?= $mealPlanClass ?>"><?= $this->Html->link(__('Meal Plan'),['controller' => 'diets', 'action' => 'index']); ?></li>
				<li class="<?= $communityClass ?>"><?= $this->Html->link(__('Community'),['controller' => 'feeds', 'action' => 'index']); ?></li>
				<li class="<?= $recipeClass ?>"><?= $this->Html->link(__('Recipes'),['controller' => 'recipes', 'action' => 'index']); ?></li>
				<li class="<?= $leaderboardClass ?>"><?= $this->Html->link(__('leaderboard'),['controller' => 'users', 'action' => 'board']); ?>
				<?php if($this->request->session()->read('Auth.User.group_id') == CLIENTGROUPID){ ?>
				<!--<li class=""><?= $this->Html->link(__('Coach Corner'),['controller' => 'mycoach']); ?></li></li>-->
				<?php } ?>
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
					<li><?= $this->Html->link(__('Logout'),['controller' => 'users', 'action' => 'logout']); ?></li>
				</ul>
			</div>
			<div style="padding: 5px 10px; clear: both; float: right;">
				<?php
					if(isset($remainingDays) && !empty($remainingDays)){
						echo $remainingDays." days remaining";
					}
				?>
			</div>
		</div>
	</div>
</div>

<!-- login_header element start -->