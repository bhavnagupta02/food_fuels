$(document).ready(function(){
	var $image = $('.img-container > img'),
	    cropBoxData,
	    canvasData;
	    
		$('#myModal').on('shown.bs.modal', function () {
		  $image.cropper({
		    autoCropArea: 0.5,
		    aspectRatio: 1 / 1,
	        preview: '.img-preview',
	        crop: function(data){
	        	$('#image-x').val(Math.round(data.x));
	            $('#image-y').val(Math.round(data.y));
	            $('#image-height').val(Math.round(data.height));
	            $('#image-width').val(Math.round(data.width));
	            $('#image-rotate').val(Math.round(data.rotate));
	        },
	        built: function () {
		      // Strict mode: set crop box data first
		      $image.cropper('setCropBoxData', cropBoxData);
		      $image.cropper('setCanvasData', canvasData);
		    }
		  });
		}).on('hidden.bs.modal', function () {
		  cropBoxData = $image.cropper('getCropBoxData');
		  canvasData = $image.cropper('getCanvasData');
		  $image.cropper('destroy');
		});

		$(document.body).on('click', '[data-method]', function () {
	      var data = $(this).data(),
	          $target,
	          result;

	      if (data.method) {
	        data = $.extend({}, data); // Clone a new one

	        if (typeof data.target !== 'undefined') {
	          $target = $(data.target);

	          if (typeof data.option === 'undefined') {
	            try {
	              data.option = JSON.parse($target.val());
	            } catch (e) {
	              console.log(e.message);
	            }
	          }
	        }

	        result = $image.cropper(data.method, data.option);

	        if (data.method === 'getCroppedCanvas') {
	          $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
	        }

	        if ($.isPlainObject(result) && $target) {
	          try {
	            $target.val(JSON.stringify(result));
	          } catch (e) {
	            console.log(e.message);
	          }
	        }

	      }
	    }).on('keydown', function (e) {

	      switch (e.which) {
	        case 37:
	          e.preventDefault();
	          $image.cropper('move', -1, 0);
	          break;

	        case 38:
	          e.preventDefault();
	          $image.cropper('move', 0, -1);
	          break;

	        case 39:
	          e.preventDefault();
	          $image.cropper('move', 1, 0);
	          break;

	        case 40:
	          e.preventDefault();
	          $image.cropper('move', 0, 1);
	          break;
	      }

	    });
});