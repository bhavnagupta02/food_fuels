<div class="main-content row">
<style>.top-area > p {
    text-align: justify;
    text-indent: 0 !important;
    width: auto;
    font-size:15px !important;
}</style>
	<div class="container">
		<div class="mealplan-container faq-section">
			<h2>Frequently Asked Questions<br />FoodFuels FAQs: The Best Way to Lose Weight</h2>
			<div id="accordion">
				<?php
					if(!empty($faqList)) {
						foreach($faqList as $faq) {
				?>
							<h3><?php echo ucfirst($faq['question']); ?></h3>	
							<div class="tabs">
								<div id="tabs-1">
									<div class="top-area">
										<?php echo ucfirst($faq['answer']); ?>
									</div>
                                    <div>
                                    
                                    </div>
								</div>
							</div>
				<?php
						}
					}
				?>
			</div>
		</div>
        <div style="  color: #444444 !important;
    float: left;
    font-size: 28px !important;
    font-weight: bold !important;
    height: auto;
    margin-top: 34px;
    text-align: center;
    width: 100%;"><h2><a href="javascript:void(0)" class="sinup-btn signup_popup_open" data-popup-ordinal="1" id="open_72585593">Start seeing results right away! Sign up for FoodFuels today.</a></h2></div>
	</div>
    
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.tabs').tabs();
	$('.clickMe a').click();
});
</script>
