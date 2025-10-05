<style>

.right-sidebar.coac-right {
  width: 30% !important;
}

.mem-name h5 {
  margin: -44px 120px 0;
  position: absolute;
  z-index: 99999999;
}
.mem-name {
  clear: both !important ;
}
.mem-img {
  float: left;
  padding: 7px 0;
  width: 200px;
  margin-left: 50px;
}
.img.member {
  float: left;
  height: 50px;
  width: 50px;
}
</style>

<!-- ccright_sidebar element start -->
<div class="right-sidebar coac-right">
	<!--
	<ul class="posts-list">
		<li><a href="#">John</a> commented on your post. “Lorem ipsum dolor sit amet...”</li>
		<li><a href="#">John</a> likes your post. “Lorem ipsum dolor sit amet...”</li>
		<li><a href="#">John</a> likes your post. “Lorem ipsum dolor sit amet...”</li>
		<li><a href="#">John</a> likes your post. “Lorem ipsum dolor sit amet...”</li>
	</ul>
	-->
		<?php
		if($this->request->session()->read('Auth.User.group_id') == USERGROUPID){
	$username = $this->request->session()->read('Auth.User.first_name')." ".$this->request->session()->read('Auth.User.last_name');?>
	
		<div class="member-info coac-mem">
			<h4><?= $username." Group Members"; ?></h4>
			<?php 
				if(isset($ccoach_users) && !empty($ccoach_users)){
					foreach ($ccoach_users as $key => $wValue) {
				?>
				
				<div class="mem-img">
					<div class="img member">
						<?php
		                    $proImage = $this->Custom->getProfileImage($wValue['image'],PROFILE_IMAGE);
		                    echo $this->Html->image($proImage);
		                ?>
					</div>
				</div>
					<div class="mem-name">
					<?= $this->Html->link('<h5>'.$wValue['first_name'].' '.$wValue['last_name'].'</h5>','javascript:void(0)',['escape' => false]); ?>
					</div>
				
					<?php
						}
					}
					}
					else{
					
		$username = $this->request->session()->read('Auth.User.first_name')." ".$this->request->session()->read('Auth.User.last_name');?>
	
		<div class="member-info coac-mem">
			<h4><?= $username." Assign Coach"; ?></h4>
			<?php 
				if(isset($cuserDetails['trainer']) && !empty($cuserDetails['trainer'])){
				?>
				
				<div class="mem-img">
					<div class="img member">
						<?php
		                    $proImage = $this->Custom->getProfileImage($cuserDetails['trainer']['image'],PROFILE_IMAGE);
		                    echo $this->Html->image($proImage);
		                ?>
					</div>
				</div>
					<div class="mem-name">
					<?= $this->Html->link('<h5>'.$cuserDetails['trainer']['first_name'].' '.$cuserDetails['trainer']['last_name'].'</h5>','javascript:void(0)',['escape' => false]); ?>
					</div>
				
					<?php
						}
					}
					?>
		</div>
		</div>		
</div>
<!-- ccright_sidebar element end -->