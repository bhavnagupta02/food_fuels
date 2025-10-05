<!-- c_sidebar element start -->
<?php 
	$action 		= strtolower($this->request->action);
	$controller 	= strtolower($this->request->controller);
	$feedsClass 	= $sharedVideos = $sharedPhotos = $messages = '';
	$combiNation 	= $controller."-".$action;

	switch ($combiNation) {
		case 'feeds-index':
			$feedsClass = 'current-menu-item';
			break;

		case 'feeds-my_videos':
			$sharedVideos = 'current-menu-item';
			break;

		case 'feeds-my_photos':
			$sharedPhotos = 'current-menu-item';
			break;	

		case 'messages-index':
		case 'messages-inbox':
		case 'messages-detail':
			$messages = 'current-menu-item';
			break;			
		
		default:
			$feedsClass = 'current-menu-item';
			break;
	}
?>
<div class="left-sidebar">
	<ul class="sidear-menu">
		<li class="<?= $feedsClass ?>">
			<i class="icons nw-feed-icon"></i>
			<?= $this->Html->link(__('News Feeds'),['controller' => 'feeds', 'action' => 'index']); ?>
		</li>
		<li class="<?= $sharedVideos ?>">
			<i class="icons video-icon"></i>
			<?= $this->Html->link(__('Shared Videos'),['controller' => 'feeds', 'action' => 'my_videos']); ?>
		</li>
		<li class="<?= $sharedPhotos ?>">
			<i class="icons photo-icon"></i>
			<?= $this->Html->link(__('Shared Photos'),['controller' => 'feeds', 'action' => 'my_photos']); ?>
		</li>
		<li class="<?= $messages ?>">
			<i class="icons bubble-icon"></i>
			<?= $this->Html->link(__('Messages'),['controller' => 'messages', 'action' => 'inbox']); ?>
		</li>
	</ul>
</div>
<!-- c_sidebar element end -->