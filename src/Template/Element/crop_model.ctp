<!-- TopBarAfterLogin element start -->
<?php 
	$action 		= strtolower($this->request->action);
	$controller 	= strtolower($this->request->controller);
	$combiNation 	= $controller."-".$action;
	switch ($combiNation) {
		case 'users-edit_profile':
		case 'trainers-edit_profile':
			echo $this->Html->script(array('cropmain'));
			break;

		case 'users-home':
			echo $this->Html->script(array('crop_before_after'));
			break;
	}
?>
<?php 
	echo $this->Html->css(['jquery-ui-redmond.min.css','bootstrap.min','cropper','main']);

	echo $this->Html->script(['bootstrap.min','cropper','cropmain','common','jquery.ajax_form']);
	
?>
<input style="display:none;" class="imagecrop" />
	
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Photo</h4>
      </div>
      <div class="modal-body">
      		<!-- Content -->
			<div class="container">
			    <div class="row">
			      <div class="col-md-7">
			        <h4 class="page-header">Adjust Photo:</h4>
			        <div class="img-container">
			          <img src="" alt="Picture">
			        </div>
			      </div>
			      <div class="col-md-3">
			        <h4 class="page-header">Preview:</h4>
			        <div class="docs-preview clearfix">
			          <div class="img-preview preview-lg"></div>
			          <div class="img-preview preview-md"></div>
			          <div class="img-preview preview-sm"></div>
			          <div class="img-preview preview-xs"></div>
			        </div>
			      </div>
			    </div>
			    <div class="row">
			      <div class="col-md-9 docs-buttons">
			        <!-- <h3 class="page-header">Toolbar:</h3> -->
			        <div class="btn-group">
			          <button class="btn btn-primary" data-method="zoom" data-option="0.1" type="button" title="Zoom In">
			              <span class="icon icon-zoom-in"></span>
			          </button>
			          <button class="btn btn-primary" data-method="zoom" data-option="-0.1" type="button" title="Zoom Out">
			              <span class="icon icon-zoom-out"></span>
			          </button>
			          <button class="btn btn-primary" data-method="rotate" data-option="-45" type="button" title="Rotate Left">
			              <span class="icon icon-rotate-left"></span>
			          </button>
			          <button class="btn btn-primary" data-method="rotate" data-option="45" type="button" title="Rotate Right">
			              <span class="icon icon-rotate-right"></span>
			          </button>
			        </div>
			      </div><!-- /.docs-buttons -->
			    </div>
			</div>

			<!-- Alert -->
			<div class="docs-alert"><span class="warning message"></span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveMyPic">Save changes</button>
      </div>
    </div>
  </div>
</div>
<button type="button" class="btn btn-primary btn-lg hide showCropBox" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>