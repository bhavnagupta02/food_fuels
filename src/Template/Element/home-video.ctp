<!-- home-video element start -->
<?php if(!isset($_COOKIE['skipVideo'])){ ?>
<div class="hdr-video row">
	<div id="bgVideo" class="background" style="height: 800px;"><video controls id="video_background" style="position: relative; top:0px; left: 0px; bottom: 0px; right: 0px; z-index: 999999; width: 100%; height: 890px;"><source src="<?= BASE_URL.'videos/video.mp4' ?>" type="video/mp4"><source src="<?= BASE_URL.'videos/video.mp4' ?>" type="video/mp4">bgvideo</video></div>

	<div class="video-descp">
		<!--
		<h2>FoodFuels Weight Loss</h2>
		<?= $cmsText[0]->description ?>
		<span class="how-work">See how it works </span>
		-->
		<div style="clear:both;"></div>
			<?= $this->Html->link('Do not show me again','javascript:void()',['class' => 'skipVideo']); ?>
		</div>
</div>
<?php } ?>
<!-- home-video element start -->