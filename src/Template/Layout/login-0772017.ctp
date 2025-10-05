<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
	<head>
		<?= $this->Html->charset() ?>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>
	        <?= $this->fetch('title') ?>
	    </title>
	    <?= $this->Html->meta('icon') ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<?= $this->Html->css('//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all') ?>
		<?= $this->Html->css('assets/global/plugins/font-awesome/css/font-awesome.min') ?>
		<?= $this->Html->css('assets/global/plugins/simple-line-icons/simple-line-icons.min') ?>
		<?= $this->Html->css('assets/global/plugins/bootstrap/css/bootstrap.min') ?>
		<?= $this->Html->css('assets/global/plugins/uniform/css/uniform.default') ?>
		<!-- END GLOBAL MANDATORY STYLES -->
		<!-- BEGIN PAGE LEVEL STYLES -->
		<?= $this->Html->css('assets/admin/pages/css/login') ?>
		<!-- END PAGE LEVEL SCRIPTS -->
		<!-- BEGIN THEME STYLES -->
		<?= $this->Html->css('assets/global/css/components') ?>
		<?= $this->Html->css('assets/global/css/plugins') ?>
		<?= $this->Html->css('assets/admin/layout/css/layout') ?>
		<?= $this->Html->css('assets/admin/layout/css/themes/darkblue') ?>
		<?= $this->Html->css('assets/admin/layout/css/custom') ?>
		<!-- END THEME STYLES -->
		<!-- BEGIN CORE PLUGINS -->
		<!--[if lt IE 9]>
		<script src="../../assets/global/plugins/respond.min.js"></script>
		<script src="../../assets/global/plugins/excanvas.min.js"></script> 
		<![endif]-->
		<?= $this->Html->script('/css/assets/global/plugins/jquery.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/jquery-migrate.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/bootstrap/js/bootstrap.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/jquery.blockui.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/jquery.cokie.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/uniform/jquery.uniform.min') ?>
		<!-- END CORE PLUGINS -->
		<!-- BEGIN PAGE LEVEL PLUGINS -->
		<?= $this->Html->script('/css/assets/global/plugins/jquery-validation/js/jquery.validate.min') ?>
		<!-- END PAGE LEVEL PLUGINS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<?= $this->Html->script('/css/assets/global/scripts/metronic') ?>
		<?= $this->Html->script('/css/assets/admin/layout/scripts/layout') ?>
		<?= $this->Html->script('/css/assets/admin/layout/scripts/demo') ?>
		<?= $this->Html->script('/css/assets/admin/pages/scripts/mylogin') ?>
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<?= $this->fetch('meta') ?>
		<?= $this->fetch('css') ?>
		<?= $this->fetch('script') ?>

    <?= $this->Html->css('reset.css') ?>
    <?= $this->Html->css('jquery.stickyNavbar.css') ?>
    <?= $this->Html->css('animate.min.css') ?>
    <?= $this->Html->css('jquery.bxslider.css') ?>
    <?= $this->Html->css('style.css?q=12345') ?>
    <?= $this->Html->css('custom.css') ?>
    <?= $this->Html->css('responsive.css') ?>
    <?= $this->Html->css('jquery.modal.min.css') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
     <?= $this->Html->script('jquery-1.11.0.min.js'); ?>
    <?= $this->Html->script('jquery-ui.js'); ?>
    <?= $this->Html->script('jquery.modal.min.js'); ?>
   
    <?= $this->Html->script('jquery-ui.js'); ?>
    <?= $this->Html->script('jquery.modal.min.js'); ?>
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-87277097-1', 'auto');
  ga('send', 'pageview');

</script>


	</head>
	<body class="login <?= (($this->request->session()->read('Auth.User.id')?"inner-pages":"")) ?>">
	<div id="container">
        <div id="content">
            <?= $this->Flash->render() ?>
            <?php
                if($this->request->session()->read('Auth.User.id')) 
                { //logged in
                    echo $this->element('login_header');
                }
                else
                {
                    echo $this->element('header');
                     if(strtolower($this->request->action) == "home" && strtolower($this->request->controller) == 'pages')
                        echo $this->element('home-video');
                }
            ?>
            <?= $this->fetch('content') ?>
            <?= $this->element('footer'); ?>
        </div>
        <footer>
        </footer>
    </div>

    <script type="text/javascript">
    var base_url = '<?= BASE_URL ?>';
</script>    
<?= $this->Html->script('html5.js'); ?>
<?= $this->Html->script('modernizr.js'); ?>
<?= $this->Html->script('jquery.stickyNavbar.js'); ?>
<?= $this->Html->script('jquery.bxslider.min.js'); ?>
<?= $this->Html->script('jquery.popupoverlay.js'); ?>
<?= $this->Html->script('script.js'); ?>

<script>
$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});
</script>
<style>

.modal-open .modal {
    
    overflow-y: hidden !important;
}
/*==POPUP CSS==*/

@import "lesshat";

/*******************************************
  = LESS
*******************************************/

/* LESS - Mixins */
.clearfix() {
  
  &:before,
  &:after {
	content: "";
	display:table;
  }
  
  &:after {
	clear: both;
  }
  
}

.transition() {
  -webkit-transition: all .2s ease-in-out;
  -moz-transition: all .2s ease-in-out;
  -o-transition: all .2s ease-in-out;
  transition: all .2s ease-in-out;  
}


/*******************************************
  = LAYOUT
*******************************************/

* {
  -webit-box-sizing: border-box;
	-moz-box-sizing: border-box;
		 box-sizing: border-box;
  margin: 0;
  padding: 0;
  border: 0;
}


/*******************************************
  = RATING
*******************************************/

/* RATING - Form */
.rating-form {
	margin-top: 40px;
	    height: 500px;
}

	/* RATING - Form - Group */
	.rating-form .form-group {
        position: relative;
    border: 0;
    /*margin-top: 45px;
    margin-right: 185px;*/
    float: left;
    margin-bottom: 35px;
	}

	/* RATING - Form - Legend */
	.rating-form .form-legend {
		display: none;
		margin: 0;
		padding: 0;
		font-size: 20px; font-size: 2rem;
		/*background: green;*/
	}

	/* RATING - Form - Item */
	.rating-form .form-item {
		position: relative;
		margin: auto;
		width: 495px;
		
		direction: rtl;
		/*background: green;
    text-align: center;*/
	}

	.rating-form .form-legend + .form-item {
		padding-top: 10px;
	}

		.rating-form input[type='radio'] {
			position: absolute;
			left: -9999px;
		}

	  /* RATING - Form - Label */
	  .rating-form label {
		display: inline-block;
		cursor: pointer;
	  }

		.rating-form .rating-star {
       display: inline-block;
			position: relative;
		}

	.rating-form input[type='radio'] + label:before {
		content: attr(data-value);
		position: absolute;
		right: 30px; top: 83px;
		font-size: 30px; font-size: 3rem;
		opacity: 0;
		direction: ltr;
		.transition();
	}

	.rating-form input[type='radio']:checked + label:before {
		right: 25px;
		opacity: 1;
	}

	.rating-form input[type='radio'] + label:after {
		content: "/ 5";
		position: absolute;
		right: 5px; top: 96px;
		font-size: 16px; font-size: 1.6rem;
		opacity: 0;
		direction: ltr;
		.transition();
	}

	.rating-form input[type='radio']:checked + label:after {
		/*right: 5px;*/
		opacity: 1;
	}

		.rating-form label .fa {
		  font-size: 60px; font-size: 6rem;
		  line-height: 60px;
		  .transition();
		}

		.rating-form label .fa-star-o {

		}

		.rating-form label:hover .fa-star-o,
		.rating-form label:focus .fa-star-o,
		.rating-form label:hover ~ label .fa-star-o,
		.rating-form label:focus ~ label .fa-star-o,
		.rating-form input[type='radio']:checked ~ label .fa-star-o {
		  opacity: 0;
		}

		.rating-form label .fa-star {
		  position: absolute;
		  left: 0; top: 0;
		  opacity: 0;
		}

		.rating-form label:hover .fa-star,
		.rating-form label:focus .fa-star,
		.rating-form label:hover ~ label .fa-star,
		.rating-form label:focus ~ label .fa-star,
		.rating-form input[type='radio']:checked ~ label .fa-star {
		  opacity: 1;
		}

		.rating-form input[type='radio']:checked ~ label .fa-star {
		  color: gold;
		}

		.rating-form .ir {
		  position: absolute;
		  left: -9999px;
		}

	/* RATING - Form - Action */
	.rating-form .form-action {
		opacity: 0;
		position: absolute;
    left:0;
		/*left: 90px;*/
    bottom: -40px;
		.transition();
	}

	.rating-form input[type='radio']:checked ~ .form-action {
	  cursor: pointer;
	  opacity: 1;
	}

	.rating-form .btn-reset {
		display: inline-block;
		margin: 0;
		padding: 4px 10px;
		border: 0;
		font-size: 10px; font-size: 1rem;
		background: #fff;
		color: #333;
		cursor: auto;
		border-radius: 5px;
		outline: 0;
		.transition();
	}

	   .rating-form .btn-reset:hover,
	   .rating-form .btn-reset:focus {
		 background: gold;
	   }

	   .rating-form input[type='radio']:checked ~ .form-action .btn-reset {
		 cursor: pointer;
	   }


	/* RATING - Form - Output */
	.rating-form .form-output {
		display: none;
		position: absolute;
		right: 15px; bottom: -45px;
		font-size: 30px; font-size: 3rem;
		opacity: 0;
		.transition();
	}

	.no-js .rating-form .form-output {
		right: 5px;
		opacity: 1;
	}

	.rating-form input[type='radio']:checked ~ .form-output {
		right: 5px;
		opacity: 1;
	}
	.modal{
		max-width:550px;
		}
		.coach-img {
    border: 1px solid #000;
    border-radius: 200px;
    float: left;
    height: 90px;
    overflow: hidden;
    margin-bottom: 10px;
    width: 90px;
    margin-right: 20px;
		}
		.coach-name {
    /*float: left;*/
    padding: 15px;
}

.ui-dialog.ui-widget.ui-widget-content.ui-corner-all.ui-front.ui-draggable.ui-resizable {
  width: 50% !important;
  left: 316.5px !important;
  position: absolute;
}

#fg_form_InnerContainer {
    display: inline-block !important;
    margin: 0 auto;
    margin-top: -160.8pxpx !important;
}

.ui-widget {
    font-family: "Roboto",sans-serif !important;
}

.ui-widget input,
.ui-widget select,
.ui-widget textarea,
.ui-widget button {
    font-family: "Roboto",sans-serif !important;
}

.ui-dialog .ui-dialog-content {
    overflow: hidden !important;
    padding: .5em 2em !important;
}

.sub-btn{
  background: #20b4f0 none repeat scroll 0 0;
  border: 1px solid dodgerblue;
  border-radius: 3px;
  color: #ffff;
  display: inline-block;
  font-size: 15px;
  font-weight: 500;
  line-height: 20px;
  margin-left: 33%;
  margin-top: 10px;
  padding: 8px;
  text-align: center;
  text-transform: uppercase;
  width: 12%;
}

.ui-widget-header {
  background: #ffff none repeat scroll 0 0;
  border: 1px solid #ffff;
}

.ui-dialog-titlebar-close {
display: block;
background: rgba(0, 0, 0, 0) url("../images/cross-btn.png") no-repeat scroll center center;
}

.review-text{
  margin-top: 20px; /*margin-left: 80px;*/
  border: 2px solid grey;
  width: 464px;
  height: 140px;    font-size: 20px;
  padding: 5px;
}
</style>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<!-- RATING - Form -->
<!-- <div id="message_popup1"> -->
    <form  id="fg_form_InnerContainer" style="display:none;" class="rating-form" action="#" method="post" name="rating-movie">
	  <h2 style="font-size: 27px; margin-bottom: 12px;">Review your coach</h2>
  	<div class="coach-img">
  		<?php
        if(isset($userDetails->trainer->image))
            $trainerImage = $userDetails->trainer->image;
        else
            $trainerImage = "";

        $proImage = $this->Custom->getProfileImage($trainerImage);
        echo $this->Html->image($proImage);
      ?>
  	</div>
  	<div class="coach-name">
  		<h3 style="font-size: 22px;"><?php echo $userDetails->trainer->first_name.' '.$userDetails->trainer->last_name; ?></h3>
      <p> <?php echo $userDetails->trainer->short_description; ?></p>
  	</div>
    <fieldset class="form-group">
      <legend class="form-legend">Rating:</legend>
      <div class="form-item many">
        <input id="rating-5" name="rating" type="radio" value="5" />
        <label for="rating-5" data-value="5">
          <span class="rating-star">
            <i class="fa fa-star-o"></i>
            <i class="fa fa-star"></i>
          </span>
          <span class="ir">5</span>
        </label>
        <input id="rating-4" name="rating" type="radio" value="4" />
        <label for="rating-4" data-value="4">
          <span class="rating-star">
            <i class="fa fa-star-o"></i>
            <i class="fa fa-star"></i>
          </span>
          <span class="ir">4</span>
        </label>
        <input id="rating-3" name="rating" type="radio" value="3" />
        <label for="rating-3" data-value="3">
          <span class="rating-star">
            <i class="fa fa-star-o"></i>
            <i class="fa fa-star"></i>
          </span>
          <span class="ir">3</span>
        </label>
        <input id="rating-2" name="rating" type="radio" value="2" />
        <label for="rating-2" data-value="2">
          <span class="rating-star">
            <i class="fa fa-star-o"></i>
            <i class="fa fa-star"></i>
          </span>
          <span class="ir">2</span>
        </label>
        <input id="rating-1" name="rating" type="radio" value="1" />
        <label for="rating-1" data-value="1">
          <span class="rating-star">
            <i class="fa fa-star-o"></i>
            <i class="fa fa-star"></i>
          </span>
          <span class="ir">1</span>
        </label>
        
        <div class="form-action">
          <input class="btn-reset" type="reset" value="Reset" />   
        </div>

        <div class="form-output">
          ? / 5
        </div>
      </div>
    </fieldset>
    <textarea style="" class="review-text" name="review" placeholder="Enter reviews for the coach"></textarea><br/>
    <button type="submit" name="rating" class="sub-btn">Save</button>
      <!-- <a href="#close-modal" rel="modal:close" class="close-modal">Close</a> -->
</form> 
<!-- </div> -->


		<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
		<div class="menu-toggler sidebar-toggler"></div>
		<!-- END SIDEBAR TOGGLER BUTTON -->
		<!-- BEGIN LOGO -->
		<div class="logo">
			<?php 
				echo $this->Html->link(
					$this->Html->image('/images/logo.png'), '/', ['escape' => false]
					);
			
			?>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN LOGIN -->
		<div class="content">
			<?= $this->Flash->render() ?>
			<?= $this->fetch('content') ?>
			<!-- BEGIN LOGIN FORM -->
		</div>
		<div class="copyright">
			 2014 © Metronic. Admin Dashboard Template.
		</div>
		<!-- END JAVASCRIPTS -->
	</body>
</html>
<script>
	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		Login.init();
		Demo.init();
	});
</script>
<script type="text/javascript">
  $(document).ready(function () {
  $("#fg_form_InnerContainer").dialog();
  });
//jQuery("#fg_form_InnerContainer").modal();  
</script>
</body>  
</html>