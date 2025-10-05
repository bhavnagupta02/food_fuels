<!-- home-meal-section element start -->
<div class="Picture-upload row">
    <div class="container">
        <h2>UPLOAD YOUR PICTURE</h2>
        <div class="image-status">
            <div class="status-1"></div>
            <div class="status-2"><strong>before</strong></div>
            <div class="status-3"><strong>After</strong></div>
        </div>

        <div class="one_half">
            <div class="figure before_image">
				<?php
                    $proImage = $this->Custom->getProfileImage($userDetails->before_image,USER_BEFORE);
                    echo $this->Html->image($proImage);
                ?>
            </div>
            <div class="btn-group">
                <div class="upload-file">
                    Choose File
                     <div class="input file">
                        <input id = "hideFile1" value="" name = "before_image" type="file" onchange="profile_img_upload(this,'before');"/><i class="fa fa-upload"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="one_half last">
            <div class="figure after_image">
				<?php
                    $proImage = $this->Custom->getProfileImage($userDetails->after_image,USER_AFTER);
                    echo $this->Html->image($proImage);
                ?>
            </div>
            <div class="btn-group">
                <div class="upload-file">
                    Choose File
                    <div class="input file">
                        <input id = "hideFile2" value="" name = "after_image" type="file" onchange="profile_img_upload(this,'after');"/><i class="fa fa-upload"></i>
                    </div>  
                </div>
            </div>
        </div>
        <?php
            echo $this->Form->input('id', array('type' => 'hidden','value'=>$userDetails->id));
            echo $this->Form->input('image_x', array('type' => 'hidden'));
            echo $this->Form->input('image_y', array('type' => 'hidden'));
            echo $this->Form->input('image_height', array('type' => 'hidden'));
            echo $this->Form->input('image_width', array('type' => 'hidden'));
            echo $this->Form->input('image_rotate', array('type' => 'hidden'));
            echo $this->Form->input('image_url', array('type' => 'hidden'));
            echo $this->Form->input('current_image', array('type' => 'hidden'));
        ?>
    </div>
</div>
<!-- home-meal-section element start -->