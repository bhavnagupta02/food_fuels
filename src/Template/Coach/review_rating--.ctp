<?php 

if(!empty($reviews['reviews'])){
$stars = $reviews['reviews']['rating'];
}else{
$stars = 0;
}

 ?>
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
.review-single-box ul.review-list li .left-box span.rating img{ /*width:50%;*/}
.review-single-box ul.review-list li .left-box h5:before{ position:absolute; left:0; bottom:0; content:''; border-bottom:1px solid #c15c75; width:100px; height:1px;}
.review-single-box ul.review-list li .right-box{ float:right; width:80%;}
.review-single-box ul.review-list li .right-box .image-box{ float:left; width:10%;}
.review-single-box ul.review-list li .right-box .image-box .image{ float:left; width:100%; padding:0px; border-left: 1px solid #c15c75; overflow:hidden; border-radius: 50px;}
.review-single-box ul.review-list li .right-box .image-box .image img{ float:left; width:100%;}
.review-single-box ul.review-list li .right-box .text-box{ float:right; width:87%;}
.review-single-box ul.review-list li .right-box .text-box p{ margin-bottom:15px;}
.review-single-box ul.review-list li .right-box .text-box span.client-name{ float:left; width:100%; font-size:15px;}
.review-single-box ul.review-list li .right-box .text-box span.client-name strong{ font-weight:bold; color:#000;}
.review-single-box span.reviews-btn{ float:left; width:100%; text-align:center; margin-top:50px;}
.review-single-box span.reviews-btn a{ color:#c15c75; font-size:15px; padding:8px 20px; border-radius:50px; border:1px solid #c15c75;}
.single-man-img > img{width:60%;}
.single-man-img > img {
  border: 1px solid #ccc;
  border-radius: 100%;
  height: 100px;
  width: 100px;
}
select {
background: transparent url("../images/arw-gray.png") no-repeat scroll 97% center;
border: 0 none;
color: #444444;
font-family: roboto;
font-size: 14px;
font-weight: 400;
height: 42px;
padding: 0 7px;
position: relative;
width: 100%;
border: 2px solid #dfdfdf;
border-radius: 3px;
float: left;
height: 44px;
margin-bottom: 21px;
padding: 0 10px;
width: 100%;
}
.green-btn {
  background-color: #9fbb2e;
  border: 1px solid #87a024;
  border-radius: 2px;
  color: #ffffff;
  display: inline-block;
  font-size: 16px;
  font-weight: 500;
  letter-spacing: 1px;
  line-height: 50px;
  min-width: auto;
  padding: 0 20px;
  text-shadow: 1px 0 1px rgba(0, 0, 0, 0.3);
  text-transform: uppercase;
}
textarea {
  border: 1px solid;
}
</style>
<div class="review-single-box row">

	<div class="container">
<?php foreach($reviews['details'] as $key => $value){ ?>
    	<span class="single-man-img">
        	<?php
	        $proImage = $this->Custom->getProfileImage($value['image']);
	        echo $this->Html->image($proImage);
	    ?>
        </span>
        <h1><?= $value['first_name']." " ?><small><?= $value['last_name'] ?></small></h1>
        <span class="rating"><span class="image"><?php for($i=$stars;$i>0;$i--){ ?>
                            <img src="/coach/big-star-img.png" alt="">
                    <?php } ?></span></span></span></span>
        <h4><?= '"'.$value['short_description'].'"' ?></h4>
        
        <h3>Description</h3>
        <p><?= $value['achievements'] ?></p>
       <?php } ?>
	<?php if(!empty($reviews['reviews'])){ ?>
        <h3>Reviews</h3>
        <ul class="review-list">
        	<li>
            	<div class="left-box">
                	<h5></h5>
                    <span class="rating">
                    <?php for($i=$stars;$i>0;$i--){ ?>
                            <img src="/coach/big-star-img.png" alt="">
                    <?php } ?></span>
                </div>
                <div class="right-box">
                	<div class="image-box">
                    	<span class="image"><?php
	                        $proImage = $this->Custom->getProfileImage($this->request->session()->read('Auth.User.image'),USER_THUMB);
	                        echo $this->Html->image($proImage);
	                    ?></span>
                    </div>
                    <div class="text-box">
                    	<p><?php echo $reviews['reviews']['reviews']; ?></p>
                       
                    </div>
                </div>
            </li>
        </ul>
        <?php } else { ?>
		<h3>Add Review</h3>
		<form action="" method="post">
        <ul class="review-list">
        	<li>
            	<div class="left-box">
                	<h5></h5>
                    <span class="rating">
			<select name="rating" class="form-control" required>
				<option value="">Select rating out of 5</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>			
			</select>                    
			</span>
                </div>
                <div class="right-box">
                	
                    <div class="text-box">
                    	<p><textarea name="review" placeholder="Add Reviews" style="margin: 0px; width: 544px; height: 158px;" required></textarea></p>
                       
                    </div>
                </div>
            </li>
        </ul>
        <span class="reviews-btn">
        	<button class="green-btn" type="submit" name="save_rating">SAVE</button>
        </span>
        </form>
	<?php } ?>
    </div>

</div>

