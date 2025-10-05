<!-- admin_nav element start -->

<?php
	if($this->request->session()->check('Auth.User.id'))
	{
?>
		<div class="menubg">
			<div class="nav">
				<ul id="navigation">
					<li onmouseout="this.className=''" onmouseover="this.className='hov'">
						<?php
							echo $this->Html->link(
								'Home', array(
									'controller' => 'Users', 'action' => 'dashboard', 'prefix' => 'backoffice'
								), array(
									'class' => ''
								)
							);
						?>
					</li>
					<li onmouseout="this.className=''" onmouseover="this.className='hov'">
						<?php echo $this->Html->link('Manage Trainers', '#_'); ?>
						<div class="sub">
							<ul>
								<li>
									<?php echo $this->Html->link('Trainers List', array('controller' => 'Users', 'action' => 'index', 'prefix' => 'backoffice')); ?>
								</li>
								<li>
									<?php echo $this->Html->link('Clients List', array('controller' => 'Users', 'action' => 'client_index', 'prefix' => 'backoffice')); ?>
								</li>
								<li>
									<?php echo $this->Html->link('Add Trainer', array('controller' => 'Users', 'action' => 'add')); ?>
								</li>
							</ul>
						</div>
					</li>
					<li onmouseout="this.className=''" onmouseover="this.className='hov'">
						<?php echo $this->Html->link('Manage Emails', '#_'); ?>
						<div class="sub">
							<ul>
								<li>
									<?php echo $this->Html->link('List Emails', array('controller' => 'EmailTemplates', 'action' => 'index', 'prefix' => 'backoffice')); ?>
								</li>
							</ul>
						</div>
					</li>
					
					<!--
					<li onmouseout="this.className=''" onmouseover="this.className='hov'">
						<?php echo $this->Html->link('Manage deals', '#_'); ?>
						<div class="sub">
							<ul>
								<li>
									<?php echo $this->Html->link('List Deals', array('controller' => 'Deals', 'action' => 'index', 'prefix' => 'admin')); ?>
								</li>
								<li>
									<?php echo $this->Html->link('Add Deal', array('controller' => 'Deals', 'action' => 'add', 'prefix' => 'admin')); ?>
								</li>
							</ul>
						</div>
					</li>
					<li onmouseout="this.className=''" onmouseover="this.className='hov'">
						<?php echo $this->Html->link('Manage cmspages', '#_'); ?>
						<div class="sub">
							<ul>
								<li>
									<?php echo $this->Html->link('List Cmspages', array('controller' => 'Cmspages', 'action' => 'index', 'prefix' => 'admin')); ?>
								</li>
							</ul>
						</div>
					</li>
					-->
				</ul>
			</div>
			<div class="logout">
				<?php
					echo $this->Html->image("logout.gif", array(
							"alt" => "Logout",
							'url' => array('controller' => 'Users', 'action' => 'logout', 'prefix' => 'backoffice')
						));
				?>
			</div>
		</div>
<?php } ?>
<!-- admin_nav element start -->
