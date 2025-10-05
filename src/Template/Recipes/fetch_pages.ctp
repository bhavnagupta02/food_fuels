<?php 
					if(isset($recipeList) && !empty($recipeList)){
						foreach ($recipeList as $key => $value) {
							$catSlug = str_replace(' ', '-' , strtolower($value['category']['name']));
							?>
							<div class="item one_fifth <?= $catSlug ?>">
								<div class="img-block">
									<a href="<?= $this->Url->build(["controller" => "Recipes","action" => "details",$value['id']]); ?>">
										<?php
											$proImage = $this->Custom->getDishImage((isset($value['upload_images'][0]['name']))?$value['upload_images'][0]['name']:"");
				                            echo $this->Html->image($proImage);
				                        ?>
									</a>
									<div class="img-overlay">
										<h5><a href="<?= $this->Url->build(["controller" => "Recipes","action" => "details",$value['id']]); ?>"><?= $value['title'] ?></a></h5>
										<ul class="social-act">
											<li><a href="#"><i class="icon icon-heart"></i></a><?= $value['like_count'] ?></li>
											<li><a href="#"><i class="icon icon-comment"></i></a><?= $value['comment_count'] ?></li>
											<li><a href="#"><i class="icon icon-share"></i></a><?= $value['share_count'] ?></li>
										</ul>
									</div>
								</div>
								<div class="item-cont">
									<div class="thum">
										<?php
				                            $proImage = $this->Custom->getProfileImage($value['user']['image'],USER_THUMB);
				                            echo $this->Html->image($proImage);
				                        ?>
									</div>
									<a href="#"><?= $value['user']['username']; ?></a>
								</div>
							</div>			
							<?php
						}
					}
				?>