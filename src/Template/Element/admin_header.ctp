<!-- admin_header element start -->

<style type="text/css">
.page-header.navbar .page-logo{ background-color: white; }
</style>

<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner container">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<?php 
				echo $this->Html->link(
					$this->Html->image('logo.png'), '/', [
						'style' => 'margin-right: -10px;', 'escape' => false
					]
				)
			?>
		<!--
		<div class="menu-toggler sidebar-toggler">
			DOC: Remove the above "hide" to enable the sidebar toggler button on header
		</div>
		-->
		</div>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN PAGE ACTIONS -->
		<!-- DOC: Remove "hide" class to enable the page header actions -->
		<div class="page-actions hide">
		</div>
		<!-- END PAGE ACTIONS -->
		<!-- BEGIN PAGE TOP -->
		<div class="page-top">
			<!-- BEGIN HEADER SEARCH BOX -->
			<!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
			<!-- END HEADER SEARCH BOX -->
			<!-- BEGIN TOP NAVIGATION MENU -->
			<div class="top-menu">
				<ul class="nav navbar-nav pull-right">
					<!-- BEGIN NOTIFICATION DROPDOWN -->
					<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
					<!-- END NOTIFICATION DROPDOWN -->
					<!-- BEGIN INBOX DROPDOWN -->
					<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
					<!-- END INBOX DROPDOWN -->
					<!-- BEGIN TODO DROPDOWN -->
					<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
					<!-- END TODO DROPDOWN -->
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
					<li class="dropdown dropdown-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<?php
	                        $proImage = $this->Custom->getProfileImage($this->request->session()->read('Auth.User.image'),USER_THUMB);
	                        echo $this->Html->image($proImage,['class' => 'img-circle']);
	                    ?>
						<span class="username username-hide-on-mobile">
							<?php
								echo $this->request->session()->read('Auth.User.first_name') . ' ' . $this->request->session()->read('Auth.User.last_name')
							?>
						</span>
						<i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-default">
							<li>
								<a href="#">
								<i class="icon-user"></i> My Profile </a>
							</li>
							<li class="divider">
							</li>
							<li>
								<?php
									echo $this->Html->link(
										'<i class="icon-key"></i> Log Out', [
											'controller' => 'Users', 'action' => 'logout', 'prefix' => 'backoffice'
										], ['escape' => false]
										);
								?>
							</li>
						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>
			</div>
			<!-- END TOP NAVIGATION MENU -->
		</div>
		<!-- END PAGE TOP -->
	</div>
	<!-- END HEADER INNER -->
</div>	
<!-- admin_header element start -->
