<!-- dashboard title Start -->
<?= $this->element('c_breadcrumb'); ?>
<!-- dashboard title End -->
<div class="main-content row">
	<div class="container">
		<div class="mealplan-container">
			<div id="accordion">
				<?php 
					if(isset($mealArray) && !empty($mealArray)){
						foreach ($mealArray as $key => $eachDate) {
							//if($key == Date('W')){
							if($key == $currentWeek){
								?>
								<h3>
									This Week <?= " (Week ".$key.")" ?> 
								</h3>	
								<?php
							}
							//elseif($key == Date('W',strtotime('+1 week'))){
							elseif($key == $currentWeek+1){
								echo "<h3>Next Week Preview (Week ".$key.")</h3>";	
							}
							else{
								$gendate = new DateTime();
								$gendate->setISODate(Date('Y'),$key,1); //year , week num , day
								echo "<h3>".$gendate->format('d-m')." (Week ".$key.")</h3>";
							}
							echo "<div>";
							if(isset($shoppingListData) && !empty($shoppingListData)){
				    			echo $this->Html->link('<i class="pdf-icon"></i>Download SHOPPING LIST',['controller' => 'media', 'action' => 'shopping_list', $shoppingListData['document_name']],array('class' => 'shop-btn','escape' => false, 'target' => 'blank'));
				    		}
							echo '<div class="tabs">';
							echo "<ul>";	
							foreach ($eachDate as $innerkey => $eachDay) {
								//if(Date('Y-m-d',strtotime($eachDay['meal_date'])) == Date('Y-m-d')){
								if($eachDay['week_day'] == $weekDay){
									echo '<li class="clickMe"><a href="#tabs-'.$innerkey.'">Day '.$eachDay['week_day'].'</br>'.Date('l',strtotime($eachDay['meal_date'])).'</br>'.Date('jS M',strtotime($eachDay['meal_date'])).'</a></li>';
								}
								else{
									echo '<li><a href="#tabs-'.$innerkey.'">Day '.$eachDay['week_day'].'</br>'.Date('l',strtotime($eachDay['meal_date'])).'</br>'.Date('jS M',strtotime($eachDay['meal_date'])).'</a></li>';
								}
							}
							echo "</ul>";
							foreach ($eachDate as $innerkey => $eachDay) {
								echo '<div id="tabs-'.$innerkey.'">';
								?>
								<div class="top-area">
									<?php echo $eachDay['text_highlight']; ?>
								</div>
								<?php
									if(isset($eachDay['meals']) && !empty($eachDay['meals'])){
										foreach ($eachDay['meals'] as $childkey => $mealval) {
											?>
												<div class="meal-block">
													<div class="meal-heading"> <strong><?php echo $mealval['heading']." (Estimated ".Date('h:iA',strtotime($mealval['time'])).")"; ?></strong> </div>
													<div class="meal-left">
														<?= $this->Html->link('<h5>'.$mealval['title_option_1'].'</h5>','javascript:void(0)',['escape' => false]); ?>
														<p><?= $mealval['short_description_option_1'] ?></p>
														<p><?= $mealval['long_description_option_1'] ?></p>
													</div>
													<div class="meal-right">
														<div class="or">or</div>
														<?= $this->Html->link('<h5>'.$mealval['title_option_2'].'</h5>','javascript:void(0)',['escape' => false]);
														?>
														<p><?= $mealval['short_description_option_2'] ?></p>
														<p><?= $mealval['long_description_option_2'] ?></p>
													</div>
												</div>
											<?php
										}
									}
								echo '</div>';
							}
							echo "</div>";
							echo "</div>";
						}
					}
				?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.tabs').tabs();
	$('.clickMe a').click();
});
</script>