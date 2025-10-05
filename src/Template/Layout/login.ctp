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
	</head>
	<body class="login">
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