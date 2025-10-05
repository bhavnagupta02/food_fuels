<style>
/* new css starts */

.edit-btn {
  background: #26a69a none repeat scroll 0 0 !important;
  padding: 10px 10px 10px !important;
  width: 72px !important;
  text-align: center !important;
  margin-top:12px;
}

.edit-btn > button {
    background: #26a69a none repeat scroll 0 0;
    border: none;
    color: #fff;
}

.edit-btn a{ color:#fff;}
.no-padding{padding-left: 0;}
.right-form h2{ font-size:20px; font-weight:600;}

.form-actions.fluid{
	margin: -21px 0px 0px;
}

.item.one_fifth {
    float: left;
    width: 250px;
}
.col-md-8.side1 {
    margin-top: 20px;
}
.RecipeScroll {
    float: left;
}

/* new css ends */

</style>
<?php echo $this->Html->script('ckeditor/ckeditor.js'); ?>
<!-- BEGIN PAGE CONTENT-->
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>Add Meals to daily plans
		</div>
	</div>
	<div class="portlet-body form">
	<?php //print_r($recipeList); ?>
		<!-- BEGIN FORM-->
		<?=
		$this->Form->create($meal,['class' => 'form-horizontal form_met_validate']);
		$this->Form->templates([
		    'label' => false
		]);
		?>
			<?php echo $this->Form->input('id'); ?>
			<div class="alert alert-danger display-hide">
				<button class="close" data-close="alert"></button>
				You have some form errors. Please check below.
			</div>
			<div class="form-body side-form">
            <div class="col-md-8 side1">


            	<div class="form-group">
					<label class="col-md-3 control-label">Gender</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->input(
									'gender', array(
										'options' => array(
											0 => '', 1 => 'Male', 2 => 'Female'
										),
										'class' => 'form-control',
										'id' => 'select1',
										'required' => 'true'
									)
								);
							?>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label">Meal Type</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->input(
									'meal_type', array(
										'options' => array(
											0 => '', 1 => 'Vegetarian', 2 => 'Non-Vegetarian', 3 => 'Other'
										),
										'class' => 'form-control',
										'id' => 'select2',
										'required' => 'true'
									)
								);
							?>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label">Activity Level</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->input(
									'activity_level', array(
										'options' => array(
											0 => '', 1 => 'Low', 2 => 'Medium', 3 => 'High'
										),
										'class' => 'form-control',
										'id' => 'select3',
										'required' => 'true'
									)
								);
							?>
						</div>
					</div>
				</div>


				<div class="form-group">
					<label class="col-md-3 control-label">Meal Heading</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->text(
									'heading', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Time</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->time(
									'time', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Title for option 1</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->text(
									'title_option_1', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Short Description for option 1</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->text(
									'short_description_option_1', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Long Description for option 1</label>
					<div class="col-md-8">
						<div class="input-group" >
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->textarea(
									'long_description_option_1', array(
										'class' => 'form-control',
										'id' => 'long_desc1',

									)
								);
							?>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label">Title for option 2</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->text(
									'title_option_2', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Short Description for option 2</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->text(
									'short_description_option_2', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3
                     control-label">Long Description for option 2</label>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
							<?php
								echo $this->Form->textarea(
									'long_description_option_2', array(
										'class' => 'form-control',
										
									)
								);
							?>
						</div>
					</div>
				</div>
                </div>

<div class="col-md-4">

            <div class="right-form col-md-8" >
            <h2>All Recipe</h2> 
			</div>
                <div class="col-md-4 no-padding">
	            <div class="edit-btn">
	            	<button type="button" id="addRecipe" ><i class="fa fa-file-text-o" aria-hidden="true"></i>
 Add </button> 
	            </div>
	           	</div>
           	<div class="RecipeScroll" style="height:550px;overflow:scroll;overflow-x:hidden;overflow-y:scroll; padding:20px; ">
            	<?php
				if(isset($recipeList) && !empty($recipeList)){
						foreach ($recipeList as $key => $value) {
							$catSlug = str_replace(' ', '-' , strtolower($value['category']['name']));
							?>
							<div class="item one_fifth <?= $catSlug ?>">
									<div class="img-overlay">
										<h5><input type="checkbox" id="recipeL" name="recipe[]" value="<?php echo $value['id'];?>"> 
										<span id="<?php echo $value['id'];?>"> <?= $value['title'] ?> </span></h5>
									</div>
							</div>			
							<?php
						}
					}
				?>
				</div>
           </div>
           </div>
         
				<div class="form-actions fluid">
					<div class="row">
						<div class="col-md-offset-3 col-md-9">
							<button type="submit" class="btn green">
								Submit
							</button>
						</div>
					</div>
				</div>	
                
			</div>
            
            
            
            
		</form>
		<!-- END FORM-->


	</div>
</div>
<script>
	$(document).ready(function (){
        //initialize datepicker
        $('.datepicker').datepicker({
            rtl: Metronic.isRTL(),
            autoclose: true
        });
        $('.datepicker .form-control').change(function() {
            form3.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
        })
	});
</script>
<script>
	$(document).ready(function (){
		$( "#datepicker" ).datepicker();
		$('.validate').validate();
	});

$(document).ready(function() {
  $('#addRecipe').click(function() {
 
    //var newline = "\n";
    var long_desc1 = $('#long_desc1');
    long_desc1.text('');
    $("input[id=recipeL]:checked").each(function() {
      var recipeList = $(this).val();
      
      var title = $("#"+recipeList).text();
      
      var recipeLink = "http://demo.foodfuels.com/recipes/details/"+recipeList;
      var recipeHtml = '<a href="'+recipeLink+'" target="_blank">'+title+'</a><br>';
      
      var oldVal = long_desc1.val();
		long_desc1.text(oldVal+recipeHtml);
    });
  });
});

</script>

<script>
$(document).ready(function() {
$('#select2').hide();
$('#select3').hide();

$('#select1').on('change', function () {
    if($('#select1').val() == $(this).val()) {
        $('#select2').show();
    } 
});

$('#select2').on('change', function () {
    if($('#select2').val() == $(this).val()) {
        $('#select3').show();   
    } 
});

});
</script>