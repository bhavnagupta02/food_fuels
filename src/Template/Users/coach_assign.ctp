<?= $this->Html->css('jquery-ui-redmond.min.css');  ?>
<style type="text/css">
.modal-backdrop.in
{display:none !important;}
#selectedCoaches{ max-height: 80px; border: 1px solid #dfdfdf; display: inline-block; width: 100%; overflow-y: scroll; min-height: 30px; }

#selectedCoaches li{ width:100%; border-bottom: 1px solid #dfdfdf; padding:10px; cursor: pointer;  }
#selectedCoaches li:hover, #selectedCoaches li:active{ background-color: #ddd; }
</style>
<div class="main row">
	<div class="container">
		<div class="payment-process">
		
			<h2>Edit Your Account</h2>
			
			<?= $this->element('topBarAfterLogin'); ?>
			
			<div class="plans-container payment-container">
				<?= $this->Form->create('User', array('class' => 'payment-form', 'id' => 'UserForm'));
				?>
				<?= 
                                   $this->Flash->render();
                                   $coachName = (!empty($this->request->data['trainer']['first_name'])) ? $this->request->data['trainer']['first_name']." ".$this->request->data['trainer']['last_name'] : ''; 
				?>
				<?= $this->Form->input('trainer_name',array('class' => 'form-control margin0', 'required', 'label'=> 'Enter your coaches name and Select your coach.','value' => $coachName, 'type' => 'text', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
				<?= $this->Form->hidden('trainer_id'); ?>
				
				<?php
				$cnt = 1;
				if(isset($featuredTrainers) && !empty($featuredTrainers)){
					?>
					<div class="form-row marginTop20">
					<label for="trainer-name">Suggestions</label>
					<?php
						foreach($featuredTrainers as $subKey => $subVal){
							?>
							<div class="item-cont suggestion-box">
								<div class="thum">
									<?php
			                            $proImage = $this->Custom->getProfileImage($subVal['image'],USER_THUMB);
			                            echo $this->Html->image($proImage);
			                        ?>
								</div>
								<a href="#" class="nameClass"><?php echo $subVal['first_name']." ".$subVal['last_name'];?></a>
								<div style="clear:both;"></div>
								<a class="green-btn selectBtn" rel="<?= $subVal['id'] ?>">Select</a>
							</div>
							<?php
							$cnt++;
						}
					?>
					</div>
					<?php
				}
				?>
				
				<div class="form-row marginTop20 whiteColor">
		         <?= $this->Form->input('assign_coach',array('id' => 'checkbox', 'label' => false,'type' => 'checkbox','templates' => ['inputContainer' => '<div class="checkbox-div">{{content}}<label for="checkbox"></label></div>Coach not found? Request administrator for coach assignment'])); ?>
		        </div>  
				<ul id="selectedCoaches" style="display:none;">
				</ul>
			</div>
			<div class="row center-align mrg-40">
				<?= $this->Form->submit('Save My Coach',['class' => 'green-btn']); ?>
			</div>
			<?= $this->Form->end(); ?>
			</div>
		</div>
	</div>	
</div>
</section>
<script type="text/javascript">
$(document).ready(function(){
	$('.selectBtn').click(function(){
		var name = $(this).siblings('.nameClass').html();
		if(confirm('Are you sure you want to select '+name+' as your coach?')){
			$('input[name="trainer_id"]').val($(this).attr('rel'));
			$('input[name="trainer_name"]').val(name);
		}
	});

	$('#trainer-name').autocomplete({
	  source: base_url+'users/get_trainer?term='+$('#trainer-name').val(),
      minLength: 2,
      select: function( event, ui ) {
      	if(confirm('Are you sure you want to select '+ui.item.label+' as your coach?')){
		  	$('input[name="trainer_name"]').val(ui.item.label);
    	  	$('input[name="trainer_id"]').val(ui.item.id);
    	}
      }
    });

    $('input[name="assign_coach"]').click(function(){
    	if($(this).is(':checked')){
    		$('#trainer-name').attr('required',false);
    	}
    	else{
    		$('#trainer-name').attr('required',true);
    	}
    });
});
</script>