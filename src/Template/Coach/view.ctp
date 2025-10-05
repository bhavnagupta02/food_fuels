<style>
.review-single-box{ padding:40px 0;}
.review-single-box span.single-man-img{ float:left; margin-right:20px;}
.review-single-box h1{ font-size:35px; line-height:40px; font-weight:bold; color:#000; padding-top:10px;}
.review-single-box span.rating span.image{ float:left; margin-right:15px;}
.review-single-box span.rating span.text{ font-size:14px; line-height:38px; float:left; color:#000;}
.review-single-box h4{ float:left; width:100%; font-size:18px; line-height:25px; padding-top:10px;}
.review-single-box h3{ float:left; width:100%; font-size:22px; line-height:30px; font-weight:bold; color:#000; padding-top:15px; padding-bottom:5px;}
.review-single-box p{ float:left; width:100%; font-size:16px; color:#333; line-height:22px;}
.review-single-box ul.review-list{ float:left; width:100%; padding-top:15px;}
.review-single-box ul.review-list li{ float:left; width:100%; margin-bottom:25px;}
.review-single-box ul.review-list li .left-box{ float:left; width:20%;}
.review-single-box ul.review-list li .left-box h5{ font-size:13px; color:#c15c75; position:relative; padding-bottom:10px;}
.review-single-box ul.review-list li .left-box span.rating{ float:left; width:100%; padding-top:10px;}
.review-single-box ul.review-list li .left-box span.rating img{ width:50%;}
.review-single-box ul.review-list li .left-box h5:before{ position:absolute; left:0; bottom:0; content:''; border-bottom:1px solid #c15c75; width:100px; height:1px;}
.review-single-box ul.review-list li .right-box{ float:right; width:80%;}
.review-single-box ul.review-list li .right-box .image-box{ float:left; width:10%;}
.review-single-box ul.review-list li .right-box .image-box .image{ float:left; width:100%; padding:10px; border-left: 1px solid #c15c75; border-radius: 50px;}
.review-single-box ul.review-list li .right-box .image-box .image img{ float:left; width:100%;}
.review-single-box ul.review-list li .right-box .text-box{ float:right; width:87%;}
.review-single-box ul.review-list li .right-box .text-box p{ margin-bottom:15px;}
.review-single-box ul.review-list li .right-box .text-box span.client-name{ float:left; width:100%; font-size:15px;}
.review-single-box ul.review-list li .right-box .text-box span.client-name strong{ font-weight:bold; color:#000;}
.review-single-box span.reviews-btn{ float:left; width:100%; text-align:center; margin-top:50px;}
.review-single-box span.reviews-btn a{ color:#c15c75; font-size:15px; padding:8px 20px; border-radius:50px; border:1px solid #c15c75;}
.single-man-img > img{width:60%;}
.review-single-box ul.review-list li .left-box span.rating img { width:10% ! important;}
.review-single-box span.reviews-btn a{ color:#c15c75; font-size:15px; padding:8px 20px; border-radius:50px; border:1px solid #c15c75;}
.single-man-img > img{width:60%;}
.single-man-img > img {
  border: 1px solid #ccc;
  border-radius: 100%;
  height: 100px;
  width: 100px;
}
.review-single-box ul.review-list li .right-box .image-box .image {
  border-left: 1px solid #c15c75;
  border-radius: 50px;
  float: left;
  overflow: hidden;
  padding: 0;
  width: 100%;
}
</style>
<div class="review-single-box row">

	<div class="container">
<?php foreach($details as $key => $value){  ?>
    	<span class="single-man-img">
        	<?php
	        $proImage = $this->Custom->getProfileImage($value['image']);
	        echo $this->Html->image($proImage);
	    ?>
        </span>
        <h1><?= $value['first_name']." " ?><small><?= $value['last_name'] ?></small></h1>
        <span class="rating"><span class="image"><?php //echo $avg; 
        
        $avint = intval($avg);
        //echo $avint;
        $avdec = abs($avg - $avint);
        $avgdec = $avdec;
        //echo $avgdec;
        //for($i=$avg;$i>0;$i--){} 
        
        for($i=$avint;$i>0;$i--){
        ?>
                  <img src="/coach/big-star-img.png" alt="">
          <?php } if($avgdec!=0 && $avdec==$avgdec){ ?>
                  <img src="/coach/half_ratings.png" alt="">
          <?php } if(($avint>0 && $avint<4.5 && $avg>5) || ($avint>0 && $avint>=$avg && $avg<=$avg)) { for($i=$avint;$i<5;$i++){?>
                  <img src="/coach/ratings.png" alt="">
          <?php } } else if($avint>0 && $avint<4 || $avgdec<0) { for($i=1,$j=0.0;$i<$avint,$j<$avgdec;$i++,$j++){?>
                 <img src="/coach/ratings.png" alt="">
          <?php } } if($totalRatings==0) { for($i=1;$i<=5;$i++){ ?>                    
                  <img src="/coach/ratings.png" alt="">
          <?php }} ?>

        </span> <span class="text">(<?php echo $totalRatings; ?> ratings)</span></span>
        <h4><?= '"'.$value['short_description'].'"' ?></h4>
        
        <h3>Description:</h3>
        <p><?= $value['achievements'] ?></p>
       <?php } ?>
        <h3>Reviews:</h3>
        
        <ul class="review-list">
        <?php if($totalRatings!=0){ foreach($coach_reviews as $reviews){?>
			<li>
            	<div class="left-box">
            	<?php foreach($reviews['user_details'] as $innerData){ $userImg = $innerData['image']; ?>
                	<h5><?php echo $innerData['first_name']." ".$innerData['last_name'] ?></h5>
                	<?php } ?>
                    <span class="rating"><?php for($i=$reviews['rating'];$i>0;$i--){ ?>
                            <img src="/coach/big-star-img.png" alt="">
                    <?php } ?></span>
                </div>
                <div class="right-box">
                	<div class="image-box">
                    	<span class="image"><?php
	                        $proImage = $this->Custom->getProfileImage($userImg,USER_THUMB);
	                        echo $this->Html->image($proImage);
	                    ?></span>
                    </div>
                    <div class="text-box">
                    	<p><?php echo $reviews['reviews']; ?></p>
                        
                    </div>
                </div>
            </li>
        <?php }}else {?><p><?php echo "No reviews yet!!!";}?></p>
        </ul>
        <!--span class="reviews-btn">
        	<a href="#">Read More Reviews</a>
        </span-->
    </div>

</div>

