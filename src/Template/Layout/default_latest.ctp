<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
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
		<?= $this->Html->css('assets/global/plugins/select2/select2') ?>
		<?= $this->Html->css('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap') ?>

		<!-- END PAGE LEVEL SCRIPTS -->
		<!-- BEGIN THEME STYLES -->
		<?= $this->Html->css('assets/global/css/components') ?>
		<?= $this->Html->css('assets/global/css/plugins') ?>
		<?= $this->Html->css('assets/admin/layout2/css/layout') ?>
		<?= $this->Html->css('assets/admin/layout2/css/themes/grey') ?>
		<?= $this->Html->css('assets/admin/layout2/css/custom') ?>
		<?= $this->Html->css('assets/global/plugins/bootstrap-datepicker/css/datepicker') ?>
		<!-- END THEME STYLES -->
		
		<!-- BEGIN CORE PLUGINS -->
		<!--[if lt IE 9]>
		<script src="../../assets/global/plugins/respond.min.js"></script>
		<script src="../../assets/global/plugins/excanvas.min.js"></script> 
		<![endif]-->
		<?= $this->Html->script('/css/assets/global/plugins/jquery.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/jquery-migrate.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/jquery-ui/jquery-ui.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/bootstrap/js/bootstrap.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/jquery.blockui.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/jquery.cokie.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/uniform/jquery.uniform.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min') ?>
		<!-- END CORE PLUGINS -->
		<!-- BEGIN PAGE LEVEL PLUGINS -->
		<?= $this->Html->script('/css/assets/global/plugins/jquery-validation/js/jquery.validate.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/select2/select2') ?>
		<?= $this->Html->script('/css/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker') ?>

		<?= $this->Html->script('/css/assets/global/plugins/datatables/media/js/jquery.dataTables.min') ?>
		<?= $this->Html->script('/css/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap') ?>
		<!-- END PAGE LEVEL PLUGINS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<?= $this->Html->script('/css/assets/global/scripts/metronic') ?>
		<?= $this->Html->script('/css/assets/admin/layout/scripts/layout') ?>
		<?= $this->Html->script('/css/assets/admin/layout/scripts/demo') ?>
		<?= $this->Html->script('/css/assets/admin/pages/scripts/table-editable') ?>
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>
<!-- END HEAD -->
	<!-- BEGIN BODY -->
	<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
	<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
	<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
	<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
	<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
	<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
	<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
	<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
	<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo ">
	<!-- BEGIN HEADER -->
	<?php echo $this->element('admin_header'); ?>
	<!-- END HEADER -->
	<div class="clearfix"></div>
	<div class="container">
		<!-- BEGIN CONTAINER -->
		<div class="page-container">
			<!-- BEGIN SIDEBAR -->
			<?php echo $this->element('admin_sidebar'); ?>
			<!-- END SIDEBAR -->
			<!-- BEGIN CONTENT -->
			<div class="page-content-wrapper">
				<div class="page-content">
					<?= $this->fetch('content') ?>
				</div>
			</div>
			<!-- END CONTENT -->
			<!-- BEGIN QUICK SIDEBAR -->
			<!--Cooming Soon...-->
			<!-- END QUICK SIDEBAR -->
		</div>
		<!-- END CONTAINER -->
		<!-- BEGIN FOOTER -->
		<div class="page-footer">
			<div class="page-footer-inner">
				 2014 &copy; Metronic by keenthemes.
			</div>
			<div class="scroll-to-top">
				<i class="icon-arrow-up"></i>
			</div>
		</div>
		<!-- END FOOTER -->
	</div>
<script>
jQuery(document).ready(function() {       
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	TableEditable.init();
	var form3 = $('.form_met_validate');
	var error3 = $('.alert-danger', form3);
    form3.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "", // validate all fields including form hidden input
        errorPlacement: function (error, element) { // render error placement for each input type
            if (element.parent(".input-group").size() > 0) {
                error.insertAfter(element.parent(".input-group"));
            } else if (element.attr("data-error-container")) { 
                error.appendTo(element.attr("data-error-container"));
            } else if (element.parents('.radio-list').size() > 0) { 
                error.appendTo(element.parents('.radio-list').attr("data-error-container"));
            } else if (element.parents('.radio-inline').size() > 0) { 
                error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
            } else if (element.parents('.checkbox-list').size() > 0) {
                error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
            } else if (element.parents('.checkbox-inline').size() > 0) { 
                error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
            } else {
                error.insertAfter(element); // for other inputs, just perform default behavior
            }
        },
        invalidHandler: function (event, validator) { //display error alert on form submit   
            error3.show();
            Metronic.scrollTo(error3, -200);
        },
        highlight: function (element) { // hightlight error inputs
           $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },
        unhighlight: function (element) { // revert the change done by hightlight
            $(element)
                .closest('.form-group').removeClass('has-error'); // set error class to the control group
        },
        success: function (label) {
            label
                .closest('.form-group').removeClass('has-error'); // set success class to the control group
        },
        submitHandler: function (form) {
            error3.hide();
            form[0].submit(); // submit the form
        }
    });
});
</script>
</body>
<!-- END BODY -->
</html>