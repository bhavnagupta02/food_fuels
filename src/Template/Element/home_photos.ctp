<?php
    $imagesAre  = $videosAre = '';
    if(isset($userDetails->upload_images) && !empty($userDetails->upload_images)){
        foreach($userDetails->upload_images as $id => $name){
            if(isset($name['name']) && !empty($name['name'])){
                if($name['type'] == 'pics'){
                    $proImage = $this->Custom->getMyPics($name['name']);
                    $imagesAre .= '<li>';
                    $imagesAre .= '<div class="delete_images" rel="'.$name['id'].'">';
                    //$imagesAre .= $this->Html->image('cross-thum.png',array('height'=>15));;
                    $imagesAre .= '</div>';
                    $imagesAre .= $this->Html->image($proImage);
                    $imagesAre .= '</li>';
                }
                elseif($name['type'] == 'videos'){
                    $proImage = $this->Custom->getMyPics($name['name']);
                    
                    $videosAre .= '<li>';
                    $videosAre .= '<div class="delete_images" rel="'.$name['id'].'">';
                    //$videosAre .= $this->Html->image('cross-thum.png',array('height'=>15));;
                    $videosAre .= '</div>';
                    $videosAre .= $this->Html->image($proImage);
                    $videosAre .= '</li>';
                }
            }
        }
    }
?>
<!-- home-photos-section element start -->
<div class="gallery-sec row">
    <div class="container">
        <ul class="nav-tabs" id="filterOptions">
            <li class="active"><a href="#" class="pics">My Pics</a></li>
            <li><a href="#" class="videos">My Videos</a></li>
        </ul>
        <div class="tab-content" id="picsHolder">
            <div>
                <div class="form-row">
                    <div class="image-upload">
                        <div class="container">
                            <div class="one_half">
                                <div class="btn-group">
                                    <div class="upload-file">
                                        Choose Images
                                        <div class="input file">
                                            <?php echo $this->Form->input('UploadImage.name.', array('type' => 'file', 'multiple' => true)); ?>
                                        </div>  
                                    </div>
                                    <div class="upload-file" id="uploadMyPics">
                                        Upload All
                                    </div>
                                </div>
                                <ul class="imgPreviewUl">
                                    <?php 
                                        if(isset($imagesAre) && !empty($imagesAre)){
                                            echo $imagesAre;
                                        }
                                        else{
                                            echo "Images not found.";
                                        }
                                    ?>
                                </ul>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row center-align" style="display:none;" id="videosHolder">
            <div>
                <div class="form-row">
                    <div class="image-upload">
                        <div class="container">
                            <div class="one_half">
                                <div class="btn-group">
                                    <div class="upload-file">
                                        Choose Video
                                        <div class="input file">
                                            <?php echo $this->Form->input('UploadVideo', array('type' => 'file', 'accept' => 'video/*')); ?>
                                        </div>  
                                    </div>
                                    <div class="upload-file" id="uploadMyVideos">
                                        Upload Video
                                    </div>
                                </div>
                                <ul class="imgPreviewUl">
                                    **Note Video size should be less then 10 mb.<br>
                                           Mp4,flv,3gp or mpeg files are allowed.<br>

                                <?php 
                                    if(isset($videosAre) && !empty($videosAre)){
                                        echo $videosAre;
                                    }
                                    else{
                                        echo "Videos not found.";
                                    }
                                ?>
                                </ul>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <!--
            <a href="#" class="see-all"> see all</a>
            -->
        </div>
    </div>
</div>
<!-- home-photos-section element start -->

<script type="text/javascript">
$('document').ready(function()
{
    $("#uploadimage-name").change(function (evt) {
        $(".imgPreviewUl").find('img.thumb').parent('li').remove();
        handleFileMultiple(evt);
    });
    
    $('#uploadMyPics').click(function(){
        $('#UserForm').submit();
    });

    $('#uploadMyVideos').click(function(){
        $('#UserForm').submit();
    });

    $("#uploadvideo").change(function (evt) {
        var extensionName = get_extension($('#uploadvideo').val());
        var checkVal = $.inArray(extensionName, ['mp4','flv','3gp','mpeg']);
        
        if(!($('#uploadvideo')[0].files[0].size < 10485760 || checkVal == -1)) {
            // 10 MB (this size is in bytes)
            //Prevent default and display error
            alert("File is wrong type or over 10Mb in size! Only mp4,flv,3gp or mpeg files are allowed.");
            e.preventDefault();
        }
    });
   
});

function get_extension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1].toLowerCase();
}

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