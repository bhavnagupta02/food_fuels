<?php 
				if(isset($feedList) && !empty($feedList)){
					foreach ($feedList as $key => $value) {
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
						else if($value['activity_id']==5){
							?>
							<div class="feed-block feed-block-2" rel="<?= $value['id'] ?>">
								<div class="feed-head">
									<div class="thumnil">
										<?php 
											$proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
					                        echo $this->Html->image($proImage);
										?>
									</div>
									<p><a href="#"><?= $value['user']['first_name']." ".$value['user']['last_name']; ?></a> updated his/her status.</p>
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
						
					}
				}
			?>