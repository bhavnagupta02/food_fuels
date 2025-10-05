<div class="main-content row">
	<div class="container">
		<?= $this->element('left_sidebar'); ?>
		<div class="middle-content message-content">
			<div class="heading">Change Password</div>
			<div class="plans-container payment-container">
				<?= $this->Form->create('User', array('class' => 'payment-form', 'id' => 'UserForm'));
				?>
				<?php 
					echo $this->Flash->render();
				?>
				<?= $this->Form->input('old_password',array('class' => 'form-control', 'type'=>'password','required', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
				
				<?= $this->Form->input('password',array('class' => 'form-control', 'required', 'type'=>'password', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
				
				<?= $this->Form->input('confirm_password',array('class' => 'form-control', 'type'=>'password', 'required', 'templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
				
		        <div class="row center-align mrg-40">
					<?= $this->Form->submit('Update Your Password',['class' => 'green-btn']); ?>
				</div>	
				<?= $this->Form->end(); ?>
			</div>
		</div>
		<?= $this->element('right_sidebar'); ?>
	</div>	
</div>
</section>