<!-- dashboard title Start -->
<div class="breadcrumb row">
	<div class="container">
		<h2 class="fleft"><i class="icons icon-spoon"></i>Recipies</h2>
		<?= $this->Form->create('Recipies',['class' => 'payment-form search-form']); ?>
		<?= $this->Form->input('search',array('class' => 'form-control', 'label' => false, 'placeholder' => 'Search recipes here', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
		<?= $this->Form->hidden('category_id'); ?>
		<?= $this->Form->hidden('rating'); ?>
		
		<?= $this->Form->submit('Search',['class' => 'add-btn fleft', 'id' => 'filterMe']); ?>
		<?= $this->Html->link('+Add Recipe',['action' => 'add'],['class' => 'add-btn']); ?>
		<?= $this->Form->end(); ?>
	</div>
</div>
<!-- dashboard title End -->

<div class="main-content row">
	<ul id="filterOptions" class="recipe-nav">
		  <li class="active"><a href="#" class="all">All</a></li>
		  <li><a href="#" rel="1" class="protein-veggie protein-veg">Protein Veggie</a></li>
		  <li><a href="#" rel="2" class="fruit">Fruit</a></li>
		  <li><a href="#" rel="3" class="fast-fuel">Fast Fuel</a></li>
		  <li><a href="#" rel="4" class="carb">Carb</a></li>
		  <li><a href="#" rel="5" class="vegetarian">vegetarian</a></li>
		  <li><a href="#" rel="6" class="sea-food">sea food</a></li>
		  <li><a href="#" rel="7" class="chicken">Chicken</a></li>
		  <li><a href="#" rel="" class="highest-rated rated">Highest Rated</a></li>
	</ul>
	
	<div class="recipe-container">
		<div class="container">
			<div class="recipeholder">
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
			</div>
		</div>	
	</div>
</div>
<script type="text/javascript">
	$('document').ready(function(){
		$('#filterOptions li a').click(function(){
			var catId = $(this).attr('rel');
			$('input[name="category_id"]').val(catId);
			
			if($(this).hasClass('highest-rated')){
				$('input[name="rating"]').val(1);
			}
			else{
				$('input[name="rating"]').val('');	
			}

			$('#filterMe').click();	
		});

		$('.search-form').submit(function(){
			event.preventDefault();
			$.ajax({
                'url'       :   base_url+'recipes/search',
                'type'      :   'post',
                'async'     : 	false,
                'data'      :   $(this).serialize(),
                'success'   :   function(data){ 
                	$('.recipeholder').html(data);
		    	}
            });
		});
	});
</script>