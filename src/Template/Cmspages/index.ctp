<?php 
	$cmsData = array();
	if(!empty($cmspageDetails))
		$cmsData = $cmspageDetails->toArray(); 
?>
<div class="main row">
	<div class="container">
		<div class="payment-process cmsIndex">
			<h2><?php echo (isset($cmsData['title']))?$cmsData['title']:""; ?></h2>
			<main class="discussion-container invite-frnds">
				<h4 class="comm-title"><?php echo (isset($cmsData['sub_title']))?$cmsData['sub_title']:""; ?></h4>
				<div class="clearfix"></div>
				<?php echo (isset($cmsData['description']))?$cmsData['description']:""; ?>
				<div class="clearfix"></div>
			</main>
		</div>
	</div>	
</div>