<!-- dashboard title Start -->
<?= $this->element('c_breadcrumb'); ?>
<!-- dashboard title End -->
<div class="main-content row">
	<div class="container">
		<div class="mealplan-container">
			<?php if ($this->request->session()->read('Auth.User.group_id') == USERGROUPID || (!empty($subscriptionData) && $subscriptionData->status_id == 1)) {?>
				<div id="accordion">
					<?php 
					//echo "iam";
						if(isset($mealArray) && !empty($mealArray)){
							//echo "here";die;
							$count=0;
							$weekDisplay=0;
							foreach ($mealArray as $key => $eachDate) {
								//if($key == Date('W')){
								$weekDisplay = $originalStartingWeek+$count;
								
								if($this->request->session()->read('Auth.User.group_id') != USERGROUPID && $key == $currentWeek){
									?>
									<h3>
										This Week <?php echo " (Week ".$weekDisplay.")"; ?> 
									</h3>	
									<?php
								}
								//elseif($key == Date('W',strtotime('+1 week'))){
								elseif($this->request->session()->read('Auth.User.group_id') != USERGROUPID && $key == (($currentWeek==12) ? 1 : $currentWeek+1)){
									echo "<h3>Next Week Preview (Week ".$weekDisplay.")</h3>";	
								}
								else{
									$gendate = new DateTime();
									$gendate->setISODate(Date('Y'),$key,1); //year , week num , day
									if ($this->request->session()->read('Auth.User.group_id') == USERGROUPID) {
										echo "<h3>"."Week ".$key."</h3>";
									} else {
										echo "<h3>".$gendate->format('d-m')." (Week ".$weekDisplay.")</h3>";
									}	
								}
								echo "<div>";
								if(isset($eachDate[0]['document_name']) && !empty($eachDate[0]['document_name'])){
									echo $this->Html->link('<i class="pdf-icon"></i>Download SHOPPING LIST',['controller' => 'media', 'action' => 'shopping_list', $eachDate[0]['document_name']],array('class' => 'shop-btn','escape' => false, 'target' => 'blank'));
								}
								echo '<div class="tabs">';
								echo "<ul>";	
								foreach ($eachDate as $innerkey => $eachDay) {
									//echo $eachDay['week_day'].'--'.$weekDay.'<br/>';
									//echo Date('Y-m-d',strtotime($eachDay['meal_date'])).'--'.Date('Y-m-d').'<br/>';
									
									if(strtotime(Date('Y-m-d',strtotime($eachDay['meal_date']))) == strtotime(Date('Y-m-d'))){
									//if($eachDay['week_day'] == $weekDay) {
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
										<?php 
											//echo $eachDay['text_highlight'];
											echo 'Week '.$weekDisplay.', Day '.$eachDay['week_day'];
										?>
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
								$count++;
							}
						}
					?>
				</div>
			<?php } else {?>
				<div class="message-content">
					<div class="row center-align mrg-40">
						<?php echo $this->Html->link(__('Start Your Meal From Today'), ['controller' => 'users', 'action' => 'start_meal'], ['class' => 'green-btn']);?>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.tabs').tabs();
	$('.clickMe a').click();
	<?php if ($this->request->session()->read('Auth.User.group_id') == USERGROUPID) {?>
		$("#accordion").accordion({ header: "h3", collapsible: true, active: false });
	<?php }?>
});
</script>