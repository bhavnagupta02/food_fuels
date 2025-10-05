<!-- dashboard title Start -->
<?= $this->element('c_breadcrumb'); ?>
<!-- dashboard title End -->
<style>
.loading-info {    bottom: 22px;    left: 0;    position: absolute;    right: 0;    text-align: center;}
</style>
<div class="main-content row">
	<div class="container">
		<?= $this->element('left_sidebar'); ?>
		<div class="middle-content message-content">
			<div class="plans-container payment-container">
				<?= $this->Form->create('Feed', array('class' => 'payment-form', 'id' => 'FeedForm'));
					echo $this->Flash->render();
				?>
				<?= $this->Form->input('title',array('class' => 'form-control height75px', 'required', 'label' => 'Status', 'type' => 'textarea', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
					<div class="row center-align">
						<?= $this->Form->submit('Update Status',['class' => 'green-btn']); ?>
					</div>	
				<?= $this->Form->end(); ?>
			</div>
			<?php 
				if(isset($feedList) && !empty($feedList)){
					foreach ($feedList as $key => $value) {
						foreach ($feedList1 as $fkey => $fvalue) {
							//print_r($fvalue['feed_type']);die;
						if($value['activity_id']==1){
							?>
							<div class="feed-block feed-block-2" rel="<?= $value['id'] ?>">
								<div class="feed-head">
									<div class="thumnil">
										<?php 
											$proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
					                        echo $this->Html->image($proImage);
										?>
									</div>
									<p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> posted a new photo.</p>
									<small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
									<i class="icons arrow-down"></i>
								</div>
								<div class="feed-content">
									<p><?= $value['title']; ?></p>
									<div class="video-row">
										<a href="#">
											<?php
												$proImage = $this->Custom->getMyPics((isset($value['upload_images'][0]['name']))?$value['upload_images'][0]['name']:"");
					                            echo $this->Html->image($proImage);
					                        ?>
					                    </a>
					                </div>
								</div>
								
								<div class="comments-block">
									<div class="reviews-row">
										<a href="javascript:void(0);" class="like">
											<i class="icons icon-like"></i><span id="likeCount<?=$value['id']?>"><?= $value['like_count']." Likes"; ?></span>
										</a>
											 - 
										<a href="javascript:void(0);"> 
											<span id="commentCount<?=$value['id']?>"><?= $value['comment_count']." Comments"; ?></span>
										</a>
											 - 
										<a href="javascript:void(0);" class="share" rel="<?=$value['id']?>">
											<span id="shareCount<?=$value['id']?>"><?= $value['share_count']." share"; ?></span>
										</a>
									</div>
									<div class="myCommentDiv">
										<?php
										if(isset($value['comments']) && !empty($value['comments'])){
											if(count($value['comments']) > 2){
												?>
												<div class="load-comment">
													<?= $this->Html->link('Load more comments','javascript:void(0)',['rel'=>1, 'onclick' => 'loadMore(this)']); ?>
												</div>
											<?php
											}
											foreach ($value['comments'] as $keyComm => $valueComm) {
												$hideClass = "";
												if((count($value['comments'])-3) >= $keyComm){
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
							<?php
						}
						else if($value['activity_id']==2){
							?>
							<div class="feed-block feed-block-2" rel="<?= $value['id'] ?>">
								<div class="feed-head">
									<div class="thumnil">
										<?php 
											$proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
					                        echo $this->Html->image($proImage);
										?>
									</div>
									<p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> posted a new video.</p>
					 				<small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
									<i class="icons arrow-down"></i>
								</div>
								<div class="feed-content">
									<p><?= $value['title']; ?></p>
									<div class="video-row">
										<?php
											$proVideoUrl = $this->Custom->getMyVideos((isset($value['upload_images'][0]['name']))?$value['upload_images'][0]['name']:"");
				                        ?>
				                        <?php if(!empty($proImage)){ ?>
										<video width="600" controls>
										  <source src="<?= $proVideoUrl ?>" type="video/mp4">
										  Your browser does not support HTML5 video.
										</video>
										<?php } ?>
									</div>
								</div>
								<div class="comments-block">
									<div class="reviews-row">
										<a href="javascript:void(0);" class="like">
											<i class="icons icon-like"></i><span id="likeCount<?=$value['id']?>"><?= $value['like_count']." Likes"; ?></span>
										</a>
											 - 
										<a href="javascript:void(0);"> 
											<span id="commentCount<?=$value['id']?>"><?= $value['comment_count']." Comments"; ?></span>
										</a>
											 - 
										<a href="javascript:void(0);" class="share"rel="<?=$value['id']?>">
											<span id="shareCount<?=$value['id']?>"><?= $value['share_count']." share"; ?></span>
										</a>
									</div>
									<div class="myCommentDiv">
										<?php
										if(isset($value['comments']) && !empty($value['comments'])){
											if(count($value['comments']) > 2){
												?>
												<div class="load-comment">
													<?= $this->Html->link('Load more comments','javascript:void(0)',['rel'=>1, 'onclick' => 'loadMore(this)']); ?>
												</div>
												<?php
											}
											foreach ($value['comments'] as $keyComm => $valueComm) {
												$hideClass = "";
												if((count($value['comments'])-3) >= $keyComm){
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
							<?php
						}
						else if($value['activity_id']==3){
							?>
							<div class="feed-block feed-block-2" rel="<?= $value['id'] ?>">
								<div class="feed-head">
									<div class="thumnil">
										<?php 
											$proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
					                        echo $this->Html->image($proImage);
										?>
									</div>
									<p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> shared a recipe.</p>
									<small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
									<i class="icons arrow-down"></i>
								</div>
								<div class="feed-content">
									<p><?= $value['recipe']['title']; ?></p>
									<div class="video-row">
										<a href="<?= $this->Url->build(["controller" => "Recipes","action" => "details",$value['recipe']['id']]); ?>">
											<?php
												$proImage = $this->Custom->getDishImage((isset($value['recipe']['upload_images'][0]['name']))?$value['recipe']['upload_images'][0]['name']:"");
					                            echo $this->Html->image($proImage);
					                        ?>
					                    </a>
					                </div>
								</div>
								<div class="comments-block">
									<div class="reviews-row">
										<a href="javascript:void(0);" class="like">
											<i class="icons icon-like"></i><span id="likeCount<?=$value['id']?>"><?= $value['like_count']." Likes"; ?></span>
										</a>
											 - 
										<a href="javascript:void(0);"> 
											<span id="commentCount<?=$value['id']?>"><?= $value['comment_count']." Comments"; ?></span>
										</a>
											 - 
										<a href="javascript:void(0);" class="share" rel="<?=$value['id']?>">
											<span id="shareCount<?=$value['id']?>"><?= $value['share_count']." share"; ?></span>
										</a>
									</div>
									<div class="myCommentDiv">
										<?php
										if(isset($value['comments']) && !empty($value['comments'])){
											if(count($value['comments']) > 2){
												?>
												<div class="load-comment">
													<?= $this->Html->link('Load more comments','javascript:void(0)',['rel'=>1, 'onclick' => 'loadMore(this)']); ?>
												</div>
												<?php
											}
											foreach ($value['comments'] as $keyComm => $valueComm) {
												$hideClass = "";
												if((count($value['comments'])-3) >= $keyComm){
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
							<?php
						}
						else if ($value['activity_id']==4) {
							if(isset($value['my_feed']) && !empty($value['my_feed'])){
								if($value['my_feed']['activity_id']==1){
								?>
									<div class="feed-block feed-block-2" rel="<?= $value['id'] ?>">
										<div class="feed-head">
											<div class="thumnil">
												<?php 
													$proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
							                        echo $this->Html->image($proImage);
												?>
											</div>
											<p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> shared a post.</p>
											<small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
											<i class="icons arrow-down"></i>
										</div>
										<div class="feed-content">
											<p><?= $value['my_feed']['title']; ?></p>
											<div class="video-row">
												<a href="#">
													<?php
														$proImage = $this->Custom->getMyPics((isset($value['my_feed']['upload_images'][0]['name']))?$value['my_feed']['upload_images'][0]['name']:"");
							                            echo $this->Html->image($proImage);
							                        ?>
							                    </a>
							                </div>
										</div>
										
										<div class="comments-block">
											<div class="reviews-row">
												<a href="javascript:void(0);" class="like">
													<i class="icons icon-like"></i><span id="likeCount<?=$value['id']?>"><?= $value['like_count']." Likes"; ?></span>
												</a>
													 - 
												<a href="javascript:void(0);"> 
													<span id="commentCount<?=$value['id']?>"><?= $value['comment_count']." Comments"; ?></span>
												</a>
													 - 
												<a href="javascript:void(0);" class="share" rel="<?=$value['my_feed']['id']?>">
													<span id="shareCount<?=$value['id']?>"><?= $value['share_count']." share"; ?></span>
												</a>
											</div>
											<div class="myCommentDiv">
												<?php
												if(isset($value['comments']) && !empty($value['comments'])){
													if(count($value['comments']) > 2){
														?>
														<div class="load-comment">
															<?= $this->Html->link('Load more comments','javascript:void(0)',['rel'=>1, 'onclick' => 'loadMore(this)']); ?>
														</div>
													<?php
													}
													foreach ($value['comments'] as $keyComm => $valueComm) {
														$hideClass = "";
														if((count($value['comments'])-3) >= $keyComm){
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
									<?php
								}
								else if($value['my_feed']['activity_id']==2){
									?>
									<div class="feed-block feed-block-2" rel="<?= $value['id'] ?>">
										<div class="feed-head">
											<div class="thumnil">
												<?php 
													$proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
							                        echo $this->Html->image($proImage);
												?>
											</div>
											<p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> shared a post.</p>
							 				<small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
											<i class="icons arrow-down"></i>
										</div>
										<div class="feed-content">
											<p><?= $value['my_feed']['title']; ?></p>
											<div class="video-row">
												<?php
													$proVideoUrl = $this->Custom->getMyVideos((isset($value['my_feed']['upload_images'][0]['name']))?$value['my_feed']['upload_images'][0]['name']:"");
						                        ?>
						                        <?php if(!empty($proImage)){ ?>
												<video width="600" controls>
												  <source src="<?= $proVideoUrl ?>" type="video/mp4">
												  Your browser does not support HTML5 video.
												</video>
												<?php } ?>
											</div>
										</div>
										<div class="comments-block">
											<div class="reviews-row">
												<a href="javascript:void(0);" class="like">
													<i class="icons icon-like"></i><span id="likeCount<?=$value['id']?>"><?= $value['like_count']." Likes"; ?></span>
												</a>
													 - 
												<a href="javascript:void(0);"> 
													<span id="commentCount<?=$value['id']?>"><?= $value['comment_count']." Comments"; ?></span>
												</a>
													 - 
												<a href="javascript:void(0);" class="share" rel="<?=$value['my_feed']['id']?>">
													<span id="shareCount<?=$value['id']?>"><?= $value['share_count']." share"; ?></span>
												</a>
											</div>
											<div class="myCommentDiv">
												<?php
												if(isset($value['comments']) && !empty($value['comments'])){
													if(count($value['comments']) > 2){
														?>
														<div class="load-comment">
															<?= $this->Html->link('Load more comments','javascript:void(0)',['rel'=>1, 'onclick' => 'loadMore(this)']); ?>
														</div>
														<?php
													}
													foreach ($value['comments'] as $keyComm => $valueComm) {
														$hideClass = "";
														if((count($value['comments'])-3) >= $keyComm){
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
									<?php
								}
								else if($value['my_feed']['activity_id']==3){
									?>
									<div class="feed-block feed-block-2" rel="<?= $value['id'] ?>">
										<div class="feed-head">
											<div class="thumnil">
												<?php 
													$proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
							                        echo $this->Html->image($proImage);
												?>
											</div>
											<p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> shared a post.</p>
											<small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
											<i class="icons arrow-down"></i>
										</div>
										<div class="feed-content">
											<p><?= $value['my_feed']['recipe']['title']; ?></p>
											<div class="video-row">
												<a href="<?= $this->Url->build(["controller" => "Recipes","action" => "details",$value['recipe']['id']]); ?>">
													<?php
														$proImage = $this->Custom->getDishImage((isset($value['my_feed']['recipe']['upload_images'][0]['name']))?$value['my_feed']['recipe']['upload_images'][0]['name']:"");
							                            echo $this->Html->image($proImage);
							                        ?>
							                    </a>
							                </div>
										</div>
										<div class="comments-block">
											<div class="reviews-row">
												<a href="javascript:void(0);" class="like">
													<i class="icons icon-like"></i><span id="likeCount<?=$value['id']?>"><?= $value['like_count']." Likes"; ?></span>
												</a>
													 - 
												<a href="javascript:void(0);"> 
													<span id="commentCount<?=$value['id']?>"><?= $value['comment_count']." Comments"; ?></span>
												</a>
													 - 
												<a href="javascript:void(0);" class="share" rel="<?=$value['my_feed']['id']?>">
													<span id="shareCount<?=$value['id']?>"><?= $value['share_count']." share"; ?></span>
												</a>
											</div>
											<div class="myCommentDiv">
												<?php
												if(isset($value['comments']) && !empty($value['comments'])){
													if(count($value['comments']) > 2){
														?>
														<div class="load-comment">
															<?= $this->Html->link('Load more comments','javascript:void(0)',['rel'=>1, 'onclick' => 'loadMore(this)']); ?>
														</div>
														<?php
													}
													foreach ($value['comments'] as $keyComm => $valueComm) {
														$hideClass = "";
														if((count($value['comments'])-3) >= $keyComm){
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
									<?php
								}
							}
							# code...
						} 
						//else if($value['activity_id']==5){
							/*foreach ($feedList1 as $fkey => $fvalue) {
							print_r($fvalue['user_id']);die;*/
						  	else if($value['activity_id']==5 && $value['id']==$fvalue['feed_id'] && $fvalue['feed_type']=='community'){
							?>
							<div class="feed-block feed-block-2" rel="<?= $value['id'] ?>">
								<div class="feed-head">
									<div class="thumnil">
										<?php 
											$proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
					                        echo $this->Html->image($proImage);
										?>
									</div>
									<p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> updated his/her status.</p> <!--<?= $fvalue['feed_type']?>-->
									<small><?= $this->Custom->getTimeAgo(strtotime($value['created'])).' ago'; ?></small>
									<i class="icons arrow-down"></i>
								</div>
								<div class="feed-content">
									<p><?= $value['title']; ?></p>
								</div>
								
								<div class="comments-block">
									<div class="reviews-row">
										<a href="javascript:void(0);" class="like">
											<i class="icons icon-like"></i><span id="likeCount<?=$value['id']?>"><?= $value['like_count']." Likes"; ?></span>
										</a>
											 - 
										<a href="javascript:void(0);"> 
											<span id="commentCount<?=$value['id']?>"><?= $value['comment_count']." Comments"; ?></span>
										</a>
											 - 
										<a href="javascript:void(0);" class="share" rel="<?=$value['id']?>">
											<span id="shareCount<?=$value['id']?>"><?= $value['share_count']." share"; ?></span>
										</a>
									</div>
									<div class="myCommentDiv">
										<?php
										if(isset($value['comments']) && !empty($value['comments'])){
											if(count($value['comments']) > 2){
												?>
												<div class="load-comment">
													<?= $this->Html->link('Load more comments','javascript:void(0)',['rel'=>1, 'onclick' => 'loadMore(this)']); ?>
												</div>
											<?php
											}
											foreach ($value['comments'] as $keyComm => $valueComm) {
												$hideClass = "";
												if((count($value['comments'])-3) >= $keyComm){
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
							<?php
						}
						
					//}
				}
				}
				}
			?>
			
		</div>
		<div class="loading-info"><a style="border:1px solid #9fbb2e; background-color:#9fbb2e;color:#fff; font-weight:bold; padding:10px;" onclick="load_More();">LOAD MORE</a></div>
		<?= $this->element('right_sidebar'); ?>
	</div>
</div>

<script type="text/javascript">

$(document).ready(function(){
	$('.icons.icon-like').click(function(){
	
		var site_url 	= '<?= $this->request->webroot ?>';
		var obj 		= this;
		var feedId 		= $(this).closest('div[class^="feed-block"]').attr('rel');
		var type 		= 2;
		
		$.ajax({
			url: site_url+'feeds/likeme',
			data:{ feed_id: feedId,type: type},
			'type'      :   'post',
			'dataType'  :   'json',
			success: function(response)
			{
				if($(obj).hasClass('active')){
					$(obj).removeClass('active');
				}
				else{
					$(obj).addClass('active');
				}

				if(response.status)
				{	
					$('#likeCount'+feedId).html(response.likes+' Likes');
				}
			}
		});
	});

	$('.share').click(function(){
	
		var site_url 	= '<?= $this->request->webroot ?>';
		var obj 		= this;
		var feedId 		= $(this).attr('rel');
		var type 		= 2;
		if(confirm('Are you sure you want to share this post?')){
			$.ajax({
				url: site_url+'feeds/shareme',
				data:{ feed_id: feedId,type: type},
				'type'      :   'post',
				'dataType'  :   'json',
				success: function(response)
				{
					if(response.status)
					{
						$('#shareCount'+feedId).html(response.shares+' Shares');
						alert('This post has successfully shared.');
					}
					else{
						alert('Post has already shared.');
					}
				}
			});
		}
		
	});

	
	$(".textClass").keyup(function(event){
		if(event.keyCode == 13 && $(this).val() != ''){
	    	var site_url 	= '<?= $this->request->webroot ?>';
			var obj 		= this;
			var feedId 		= $(this).closest('div[class^="feed-block"]').attr('rel');
			var type 		= 2;
			var textClass	= $(this).val();
			$.ajax({
				url: site_url+'feeds/commentme',
				data:{ feed_id: feedId,type: type,comment: textClass},
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
<script>
var track_page = 1; //track user scroll as page number, right now page number is 1
var loading  = false; //prevents multiple loads

//load_contents(track_page); //initial content load

function load_More(){ //detect page scroll
	
		track_page++; //page number increment
		load_contents(track_page); //load content	
	
}
//Ajax load function
function load_contents(track_page){
    if(loading == false){
		loading = true;  //set loading flag on
		$('.loading-info').show(); //show loading animation 
		$.post( 'https://www.foodfuels.com/feeds/fetch_feed_pages', {'page': track_page}, function(data){
			loading = false; //set loading flag off once the content is loaded
			if(data.trim().length == 0){
				//notify user if nothing to load
				$('.loading-info').html("No more records!");
				return;
			}
			
			//$('.loading-info').hide(); //hide loading animation once data is received
			eval($(".message-content").append(data)); //append data into #results element
		
		}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
			alert(thrownError); //alert with HTTP error
		})
	}
}
</script>