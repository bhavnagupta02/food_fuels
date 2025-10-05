<!-- footer element start -->

<script type="text/javascript">
function redirecttocontact(){
	
    jQuery('html, body').animate({
        scrollTop: jQuery(".right-sec").offset().top
    }, 500);
}
</script>
<div class="footer row">
	<div class="container">
		<div class="ft-about">
			<?= $cmsText[4]->description ?>
		</div>
		<div class="ft-menu">
			<ul>
				<li><?= $this->Html->link(__('Login'),'javascript:void(0)',['class' => 'login_popup_open']); ?></li>
				<!--li><?= $this->Html->link(__('Success Stories'),['controller' => 'Cmspages', 'action' => 'index','story']); ?></li>
				<li><?= $this->Html->link(__('The Nutrition'),['controller' => 'Cmspages', 'action' => 'index','nutrition']); ?></li-->
				<li><?= $this->Html->link(__('About Us'),['controller' => 'Cmspages', 'action' => 'index','about-us']); ?></li>
				<!--<li><?= $this->Html->link(__('Contact Us'),['controller' => 'Cmspages', 'action' => 'index','contact-us']); ?></li>-->
				<li><?= $this->Html->link(__('Contact Us'),'javascript: void(0); redirecttocontact();'); ?></li>
				<li><?= $this->Html->link(__('FAQs'),['controller' => 'Pages', 'action' => 'faq_list']); ?></li>
			</ul>
		</div>
		<div class="ft-subscribe">
			<h6>Subscribe</h6>
			<p>Subscribe here to receive more information from Foodfuels</p>
		

<!-- Begin MailChimp Signup Form -->
<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
<style type="text/css">
	#mc_embed_signup{ clear:left; font:14px Helvetica,Arial,sans-serif; }
	#mc_embed_signup .button {
	 background: #9fbb2e url("../images/btn-arw.png") no-repeat scroll center center;
border: 0 none;
border-radius: 4px;
clear: both;
color: #ffffff;
cursor: pointer;
display: inline-block;
font-size: 15px;
font-weight: normal;
height: 45px;
line-height: 32px;
margin: 28px 9px 7px 0;
padding: 0 21px;
text-align: center;
text-decoration: none;
transition: all 0.23s ease-in-out 0s;
vertical-align: top;
white-space: nowrap;
width: auto;
	}
	/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
	   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
</style>
<div id="mc_embed_signup">
<form action="//FoodFuelsWeightloss.us10.list-manage.com/subscribe/post?u=eeb74dd2ce2c464b6e9e9b34e&amp;id=15fc0b3bb9" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate subscribe-form" target="_blank" novalidate>
    <div id="mc_embed_signup_scroll">
	
<div class="mc-field-group">
	<label for="mce-EMAIL">Email Address </label>
	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
</div>
	<div id="mce-responses" class="clear">
		<div class="response" id="mce-error-response" style="display:none"></div>
		<div class="response" id="mce-success-response" style="display:none"></div>
	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_eeb74dd2ce2c464b6e9e9b34e_15fc0b3bb9" tabindex="-1" value=""></div>
    <div class="clear"><input type="submit" value="" name="subscribe" id="mc-embedded-subscribe" class="button btn-icon"></div>
    </div>
</form>
</div>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->
			<ul class="media-icon">
				<?= $this->Html->link($this->Html->image('facebook.png'),'https://www.facebook.com/FoodFuels/',array('escape'=>false)); ?>
				<?= $this->Html->link($this->Html->image('twitter.png'),'https://www.instagram.com/foodfuels/',array('escape'=>false)); ?>
				<?//= $this->Html->link($this->Html->image('gplus.png'),'#',array('escape'=>false)); ?>
				<?//= $this->Html->link($this->Html->image('youtube.png'),'#',array('escape'=>false)); ?>
			</ul>
			
		</div>
		<div class="copyright">&copy; 2015 - FoodFuels Weightloss . All rights reserved.</div>
		
	</div>
</div>

<!-- footer element start -->
