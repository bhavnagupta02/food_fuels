<?php 
	echo $this->Html->css(array('bootstrap.min', 'multiple-select'));
?>
<style type="text/css">
.modal-backdrop.in
{display:none !important;}
</style>
<div class="main row">
	<div class="container">
		<?= $this->element('left_sidebar'); ?>
		<div class="payment-process middle-content message-content">
			<h2>Edit Recipes</h2>
			<div class="plans-container payment-container">
				<?= $this->Form->create($recipe,array('class' => 'payment-form', 'id' => 'RecipeForm','type' => 'file')); ?>
				<?php 
					echo $this->Flash->render();
				?>
				<?= $this->Form->input('title',array('class' => 'form-control', 'required', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
				
				<?= $this->Form->input('description',array('class' => 'form-control height75px', 'required', 'type' => 'textarea', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
				
				<?= $this->Form->input('ingredients',array('class' => 'form-control height75px', 'type' => 'textarea', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
				
				<?= $this->Form->input('directions',array('class' => 'form-control height75px', 'type' => 'textarea', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

				<?= $this->Form->input('notes',array('class' => 'form-control height75px', 'type' => 'textarea', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

				<?= $this->Form->input('serving_size',array('class' => 'form-control', 'templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>

				<?= $this->Form->input('preparation_time',array('class' => 'form-control', 'templates' => ['inputContainer' => '<div class="col-6-2"><div class="form-row">{{content}}</div></div>'])); ?>

				<div class="form-row marginTop20 whiteColor">
					<label for="category">Please select categories</label>
					<?php
						if(isset($categories) && !empty($categories)){
							foreach ($categories as $key => $value) {
								$checked = false;
								if(strpos($recipe['category_id'], '"'.$key.'"')){
									$checked = true;
								}
								echo $this->Form->input('category_id.'.$key,array('id' => 'checkbox'.$key, 'label' => false, 'checked' => $checked, 'type' => 'checkbox','templates' => ['inputContainer' => '<div class="fleft width30"><div class="checkbox-div">{{content}}<label for="checkbox'.$key.'"></label></div><div class="customLabel">'.$value.'</div></div>']));
							}
						}
					?>
				</div>
				
				<?= $this->Form->input('status_id',array('class' => 'form-control', 'required','templates' => ['inputContainer' => '<div class="col-6-1"><div class="form-row">{{content}}</div></div>'])); ?>

				<!--
				<div>
					<div class="form-row">
						<div class="image-upload">
							<div class="container">
								<h2>Photos</h2>
								<div class="one_half">
									<div class="btn-group">
										<div class="upload-file">
											Choose Image
											<div class="input file">
												<?php echo $this->Form->input('UploadImage.name', array('type' => 'file', 'multiple' => true)); ?>
											</div>	
										</div>
									</div>
									<ul class="imgPreviewUl">
									</ul>
									<?php
				                        if(isset($this->request->data['UploadImage']) && !empty($this->request->data['UploadImage'])){
				                            foreach($this->request->data['UploadImage'] as $id => $name){
			                                    if(isset($name['name']) && !empty($name['name'])){
			                                        if(FILE_EXISTS(DISH_IMAGE_URL.$name['name'])){ ?>
			                                            <li>
			                                            <div class="delete_images" rel="<?php echo $name['id']; ?>">
			                                                <?php echo $this->Html->image('cross-thum.png',array('height'=>15)); ?>
			                                            </div>
			                                            <?php 
			                                            $imgName = DISH_IMAGE_FOLDER.$name['name'];
			                                            echo $this->Image->resize($imgName, 150, 150, true);
			                                            ?>
			                                            </li> 
			                                        <?php }
			                                    }
			                                }
				                        }
				                   ?>
								</div>	
							</div>
						</div>
					</div>
				</div>
				-->
			
				<div class="row center-align mrg-40">
					<?= $this->Form->submit('Edit Dish',['class' => 'green-btn']); ?>
				</div>
				<?= $this->Form->end(); ?>
			</div>
		</div>
		<?= $this->element('right_sidebar'); ?>
	</div>	
</div>
</section>
<?php 
	echo $this->Html->script(array('multiple-select','ckeditor/ckeditor'));
?>

<script type="text/javascript">
$(document).ready(function(){
	$("#uploadimage-name").change(function (evt) {
		$(".imgPreviewUl").find('img.thumb').parent('li').remove();
    	handleFileMultiple(evt);
    });

    $('#category-id').change(function() {
        console.log($(this).val());
    }).multipleSelect({
        width: '100%'
    });
});

function handleFileMultiple(evt) {
		var files = evt.target.files; // FileList object

	    // Loop through the FileList and render image files as thumbnails.
	    for (var i = 0, f; f = files[i]; i++) {

	      // Only process image files.
	      if (!f.type.match('image.*')) {
	        continue;
	      }

	      var reader = new FileReader();

	      // Closure to capture the file information.
	      reader.onload = (function(theFile) {
	        return function(e) {
	          // Render thumbnail.
	          var li = document.createElement('li');
	          li.innerHTML = ['<img class="thumb" src="', e.target.result,
	                            '" title="', escape(theFile.name), '"/>'].join('');
	          $(".imgPreviewUl").append(li);
	        };
	      })(f);

	      // Read in the image file as a data URL.
	      reader.readAsDataURL(f);
	    }
	  }
</script>