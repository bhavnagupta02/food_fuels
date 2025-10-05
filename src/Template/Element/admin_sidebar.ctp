<!-- admin_sidebar element start -->

<div class="page-sidebar-wrapper">
	<div class="page-sidebar navbar-collapse collapse">
		<ul class="page-sidebar-menu page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
			<li class="start ">
				<?php
					echo $this->Html->link(
						'<span class="title">Dashboard</span>', [
							'controller' => 'Users', 'action' => 'dashboard', 'prefix' => 'backoffice'
						], ['escape' => false]
					);
				?>
			</li>
			<li>
				<?php
					echo $this->Html->link(
						'<span class="title">Coaches</span>', [
							'controller' => 'Users', 'action' => 'trainers', 'prefix' => 'backoffice'
						], ['escape' => false]
					);
				?>
			</li>

			<li>
				<a href="javascript:;"><span class="title">Members</span> <span class="arrow "></span> </a>
				<ul class="sub-menu">
					<li>
						<?php
							echo $this->Html->link(
								'<span class="title">Members List</span>', [
									'controller' => 'Users', 'action' => 'clients', 'prefix' => 'backoffice'
								], ['escape' => false]
							);
						?>
					</li>
					<li>
						<?php
							echo $this->Html->link(
								'<span class="title">Members Weight List</span>', [
									'controller' => 'Users', 'action' => 'weight', 'prefix' => 'backoffice'
								], ['escape' => false]
							);
						?>
					</li>
				</ul>
			</li>
			<li>
				<?php
					echo $this->Html->link(
						'<span class="title">Meal planner</span>', [
							'controller' => 'DailyMealPlans', 'action' => 'index', 'prefix' => 'backoffice'
						], ['escape' => false]
					);
				?>
			</li>
			<li>
				<?php
					echo $this->Html->link(
						'<span class="title">Recipes</span>', [
							'controller' => 'Recipes', 'action' => 'index', 'prefix' => 'backoffice'
						], ['escape' => false]
					);
				?>
			</li>
			<li>
				<?php
					echo $this->Html->link(
						'<span class="title">Feeds</span>', [
							'controller' => 'Feeds', 'action' => 'index', 'prefix' => 'backoffice'
						], ['escape' => false]
					);
				?>
			</li>
			<li>
				<a href="javascript:;"><span class="title">Contact Requests</span> <span class="arrow "></span> </a>
				<ul class="sub-menu">
					<li>
						<?php
							echo $this->Html->link(
								'<span class="title">Enquiry</span>', [
									'controller' => 'Enquiries', 'action' => 'index', 'prefix' => 'backoffice'
								], ['escape' => false]
							);
						?>
					</li>
					<li>
						<?php
							echo $this->Html->link(
								'<span class="title">Subcribers</span>', [
									'controller' => 'Enquiries', 'action' => 'subscriber_index', 'prefix' => 'backoffice'
								], ['escape' => false]
							);
						?>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:;"><span class="title">Other Settings</span> <span class="arrow "></span> </a>
				<ul class="sub-menu">
					<li>
						<?php
							echo $this->Html->link(
								'<span class="title">Email Templates</span>', [
									'controller' => 'email_templates', 'action' => 'index', 'prefix' => 'backoffice'
								], ['escape' => false]
							);
						?>
					</li>
					<li>
						<?php
							echo $this->Html->link(
								'<span class="title">Cms Pages</span>', [
									'controller' => 'Cmspages', 'action' => 'index', 'prefix' => 'backoffice'
								], ['escape' => false]
							);
						?>
					</li>
					<li>
						<?php
							echo $this->Html->link(
								'<span class="title">Subscriptions</span>', [
									'controller' => 'Subscriptions', 'action' => 'index', 'prefix' => 'backoffice'
								], ['escape' => false]
							);
						?>
					</li>
					<li>
						<?php
							echo $this->Html->link(
								'<span class="title">Shopping List</span>', [
									'controller' => 'Cmspages', 'action' => 'shopping_list', 'prefix' => 'backoffice'
								], ['escape' => false]
							);
						?>
					</li>
					<li>
						<?php
							echo $this->Html->link(
								'<span class="title">Coupon Codes</span>', [
									'controller' => 'Cmspages', 'action' => 'promo_index', 'prefix' => 'backoffice'
								], ['escape' => false]
							);
						?>
					</li>
                                        <li>
						<?php
							echo $this->Html->link(
								'<span class="title">FAQs</span>', [
									'controller' => 'Faqs', 'action' => 'index', 'prefix' => 'backoffice'
								], ['escape' => false]
							);
						?>
					</li>
				</ul>
			</li>
			<li>
				<?php
					echo $this->Html->link(
						'<span class="title">Coach Ratings</span>', [
							'controller' => 'coach', 'action' => 'allratings', 'prefix' => 'backoffice'
						], ['escape' => false]
					);
				?>
			</li>
		</ul>
		<!-- END SIDEBAR MENU -->
	</div>
</div>
<!-- admin_sidebar element start -->
