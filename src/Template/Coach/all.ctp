<div class="review-single-box row">
<style>
.coach-list>li {
    width: 100%;
    height: 200px;
}
.image-right {
    width: 186px;
    padding: 10px;
    float: left;
}
.content-left {
    float: right;
    width: 82%;
    padding: 13px;
}
.content-left h2{
	font-size:23px;
	    font-weight: 600;
}
.content-left p{
	font-size:21px;
	    
}
h1 {
    font-size: 32px;
    padding: 20px;
    text-decoration: underline;
    font-weight: 700;
    padding-left: 10px;
}

.image-right img {
  border-radius: 100%;
}

/*span.rating span.image{ float:left; margin-right:15px;}

span.rating span.text{ font-size:14px; line-height:38px; float:left; color:#000;}*/
</style>
	<div class="container">
	<h1>All Coaches</h1>
		<ul class="coach-list">
		 <?php 
		  	if(isset($featuredTrainers) && !empty($featuredTrainers)){
		  		foreach ($featuredTrainers as $key => $value) {
			  	?>
				<li>
					<div class="image-right"> <?php
					        $proImage = $this->Custom->getProfileImage($value['image']);
					        echo $this->Html->image($proImage);
					    ?></div>
					<div class="content-left">
						<h2><?= $value['first_name']." " ?><small><?= $value['last_name'] ?></small> 
						&nbsp;<input type='hidden' name='id' value='<?= $value['id'] ?>'></h2>
						
					<span class="rating"><span class="image"><?php $avg = $value['rating']; 
					//echo $avg; 
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
			        <?php } } if($avg==0) { for($i=1;$i<=5;$i++){ ?>                    
			            <img src="/coach/ratings.png" alt="">
			        <?php }} ?>

                    </span> <!--<span class="text">(<?php //echo $totalRatings; ?> ratings)</span>--></span>
						<p><?= '"'.$value['short_description'].'"' ?></p>
						
						<?= $this->Html->link('View','coach/view/'.$value['id'],['class' => 'btn green-btn','rel' => $value['id']]); ?>
					</div>
				  </li>
		<?php 		}
			}
		?>
		</ul>
	</div>
</div>
