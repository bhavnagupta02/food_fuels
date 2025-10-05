<div class="myCommentDiv">
	<?php
	if(isset($finalCount['comments']) && !empty($finalCount['comments'])){
		if(count($finalCount['comments']) > 2){
			?>
			<div class="load-comment">
				<?= $this->Html->link('Load more comments','javascript:void(0)',['rel'=>1, 'onclick' => 'loadMore(this)']); ?>
			</div>
			<?php
		}
		foreach ($finalCount['comments'] as $keyComm => $valueComm) {
			$hideClass = "";
			if((count($finalCount['comments'])-3) >= $keyComm){
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