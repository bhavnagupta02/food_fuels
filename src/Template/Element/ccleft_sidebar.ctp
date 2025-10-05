<!-- left_sidebar element start -->
<?php 
	/*$action 		= strtolower($this->request->action);
	$controller 	= strtolower($this->request->controller);
	$notiClass 	= $my_profile = $messages = $change_password = '';
	$feedsClass 	= $sharedVideos = $sharedPhotos = $messages = $recipes = '';
	$combiNation 	= $controller."-".$action;

	switch ($combiNation) {
		case 'notifications-index':
			$notiClass = 'current-menu-item';
			break;

		case 'users-my_profile':
			$my_profile = 'current-menu-item';
			break;

		case 'users-change_password':
			$change_password = 'current-menu-item';
			break;

		case 'feeds-index':
			$feedsClass = 'current-menu-item';
			break;

		case 'feeds-my_videos':
			$sharedVideos = 'current-menu-item';
			break;

		case 'feeds-my_photos':
			$sharedPhotos = 'current-menu-item';
			break;

		case 'recipes-index':
		case 'recipes-edit':
			$recipes = 'current-menu-item';
			break;			

		case 'messages-index':
		case 'messages-inbox':
		case 'messages-detail':
			$messages = 'current-menu-item';
			break;			
	}*/
?>
<div class="left-sidebar">
	<!--<ul class="sidear-menu">
		<li class="<?= $feedsClass ?>">
			<i class="icons nw-feed-icon"></i>
			<?= $this->Html->link(__('News Feeds'),['controller' => 'Coachcommunity', 'action' => 'index']); ?>
		</li>
		<li class="<?= $sharedVideos ?>">
			<i class="icons video-icon"></i>
			<?= $this->Html->link(__('Shared Videos'),['controller' => 'Coachcommunity', 'action' => 'my_videos']); ?>
		</li>
		<li class="<?= $sharedPhotos ?>">
			<i class="icons photo-icon"></i>
			<?= $this->Html->link(__('Shared Photos'),['controller' => 'Coachcommunity', 'action' => 'my_photos']); ?>
		</li>
		<li class="<?= $messages ?>">
			<i class="icons bubble-icon"></i>
			<?= $this->Html->link(__('Messages'),['controller' => 'messages', 'action' => 'inbox']); ?>
		</li>
		<li class="<?= $recipes ?>">
			<i class="icons recipe-icon" style="background-position: -206px 0px;"></i>
			<?= $this->Html->link(__('Recipes'),['controller' => 'recipes', 'action' => 'my_recipe']); ?>
		</li>
		<li class="<?= $notiClass ?>">
		<i class="icons notification-icon" style="background-position: -126px 0px;"></i>
			<?= $this->Html->link(__('Notifications'),['controller' => 'notifications', 'action' => 'index']); ?>
		</li>
		<li class="<?= $my_profile ?>">
			<i class="icons profile-icon"></i>
			<?php
				if($this->request->session()->read('Auth.User.group_id') == CLIENTGROUPID)
			 		echo $this->Html->link(__('My Profile'),['controller' => 'users', 'action' => 'my_profile']);
			 	elseif($this->request->session()->read('Auth.User.group_id') == USERGROUPID)
			 		echo $this->Html->link(__('My Profile'),['controller' => 'trainers', 'action' => 'my_profile']);
			?>
		</li>
		<li class="<?= $change_password ?>">
		<i class="icons password-icon" style="background-position: -191px -31px;"></i>
			<?= $this->Html->link(__('Change Password'),['controller' => 'users', 'action' => 'change_password']); ?>
		</li>
	</ul>-->
</div>
<!-- left_sidebar element end -->