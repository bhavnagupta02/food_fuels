<!-- Header element start -->

<script type="text/javscript">
function restrictredirect(){
	
	alert("hello");
}
</script>

<div class="nav row">
	<div class="container">
		<?= $this->Html->link($this->Html->image('logo.png'),'/',array('escape'=>false, 'class' => array('logo'))); ?>
		<span class="mMenu" style="display:none;">
			<?= $this->Html->link(__('Toggle menu'),'#',array('id' => 'responsive-menu')); ?>
		</span>
		<div class="nav-menu">
			<ul class="menu">
			
			<?php if ($this->request->here == '/') { ?>
			
				<li><?= $this->Html->link(__('About Us'),'#drabout'); ?></li>
				<li><?= $this->Html->link(__('Success Stories'),'#successstories'); ?></li>
				<li><?= $this->Html->link(__('The Nutrition'),'#thenutrition'); ?></li>
				<li><?= $this->Html->link(__('Coaches'),'#coaches'); ?></li>
				<li><?= $this->Html->link(__('FAQs'),['controller' => 'Pages', 'action' => 'faq_list']); ?></li>
			<?php }else{ ?> 
				
				<li><?= $this->Html->link(__('About Us'),'javascript: void(0);', array('onclick'=> "window.location.href = 'http://www.foodfuels.com/#drabout';")); ?></li>
				<li><?= $this->Html->link(__('Success Stories'),'javascript: void(0);', array('onclick'=> "window.location.href = 'http://www.foodfuels.com/#successstories';")); ?></li>
				<li><?= $this->Html->link(__('The Nutrition'),'javascript: void(0);', array('onclick'=> "window.location.href = 'http://www.foodfuels.com/#thenutrition';")); ?></li>
				<li><?= $this->Html->link(__('Coaches'),'javascript: void(0);', array('onclick'=> "window.location.href = 'http://www.foodfuels.com/#coaches';")); ?></li>			
				<li><?= $this->Html->link(__('FAQs'),['controller' => 'Pages', 'action' => 'faq_list']); ?></li>
			<?php } ?>
			

			</ul>
			
			<div class="hdr-btn">
				<?= $this->Html->link(__('LogIN'),'javascript:void(0)',array('class' => 'login-btn login_popup_open')); ?>
				<?= $this->Html->link(__('Join Now'),'javascript:void(0)', array('class' => 'sinup-btn signup_popup_open')); ?>
			</div>
		</div>
	</div>
</div>

<!--popup-login-->
<div class="sign-up popup">
	<div id="login_popup">
		<!-- ...popup content... -->
		<h2>LOGIN IN</h2>
		<p class="pop-with">With</p>
		<?= $this->Html->link('<i class="f-icon"></i>Login with Facebook',['controller'=>'social_login','action'=>'Facebook'],['class' => 'facebook-btn', 'escape' => false]) ?>
		<p class="pop-or">or</p>
		<?= $this->Form->create('UserLogin', array('class' => 'validate_form form signup-form', 'id' => 'UserLoginForm', 'url' => array('controller' => 'users', 'action' => 'login')));
		$this->Form->templates([
		    'label' => false
		]);
		?>
		<?php 
			echo $this->Flash->render('loginbox');
		?>
		<a class="tooltips" id="loginTooltip" style="display:none;" href="#">
			<span>
			</span>
		</a>	
		<?= $this->Form->input('email',array('class' => 'form-control', 'required', 'placeholder'=> 'Email Address','type' => 'email','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

		<?= $this->Form->input('password',array('class' => 'form-control', 'required', 'placeholder'=> 'Password','type' => 'password','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

		<?= $this->Form->submit('Log in',array('class' => 'sub-btn','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
		
		<div class="form-row term-cond">
			<?= $this->Form->input('remember_me',array('id' => 'checkbox1', 'label' => false,'type' => 'checkbox','templates' => ['inputContainer' => '<div class="checkbox-div">{{content}}<label for="checkbox1"></label><div class="left-align">Remember me</div></div>'])); ?>
			<a class="forget-pass">Forgot your password</a>
			<p class="pop-or">or</p>
			<p>New member? <a href="#" class="signup_here">Sign Up Here</a></p>
		</div>
		<?= $this->Form->end(); ?>
		<button class="my_popup_close login_popup_close">Close</button>
	</div>
</div>
<!-- popup end-->

<!--popup-forgot-password-->
<div class="sign-up popup">
	<div id="forgot_popup">
		<!-- ...popup content... -->
		<?php if($type!=='reset_pass'){ ?>
			<h2>FORGOT PASSWORD</h2>
			<p class="pop-with"></p>
			
			<?= $this->Form->create('UserForgot', array('class' => 'validate_form form signup-form', 'id' => 'UserForgotForm', 'url' => array('controller' => 'users', 'action' => 'forgot_password')));
			$this->Form->templates([
			    'label' => false
			]);
			?>
			<a class="tooltips" id="forgotTooltip" style="display:none;" href="#">
				<span>
				</span>
			</a>	
			<?= $this->Form->input('email',array('class' => 'form-control', 'required', 'placeholder'=> 'Email Address','type' => 'email','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

			<?= $this->Form->submit('Submit',array('class' => 'sub-btn','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
			
			<?= $this->Form->end(); ?>
			<button class="my_popup_close forgot_popup_close">Close</button>
		<?php } else { ?>
			<h2>RESET PASSWORD</h2>
			<p class="pop-with"></p>
			<?php 
				echo $this->Flash->render('resetpass');
			?>
			<form action="<?php echo $this->Url->build([
			    "controller" => "Users",
			    "action" => "user_reset_pass"
			]);
			 ?>" method="post" class="validate_form form signup-form">
				<input type="hidden" name="user_email" value="<?php echo $user_email; ?>" class="form-control" />
				<input type="password" name="new_password" placeholder="Enter New Password" class="form-control" />
				<input type="password" name="confirm_password" placeholder="Confirm New Password" class="form-control" />
				<input type="submit" name="submit" value="submit" class="sub-btn" />
			</form>
			<button class="my_popup_close forgot_popup_close">Close</button>
		<?php } ?>
	</div>
</div>
<!-- popup end-->
<!--popup-signup-->
<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="sign-up popup">
	<div id="signup_popup">
		<!-- ...popup content... -->
		<h2>Sign Up</h2>
		<p class="pop-with">With</p>
		<?= $this->Html->link('<i class="f-icon"></i>Sign UP with Facebook',['controller'=>'social_login','action'=>'Facebook'],['class' => 'facebook-btn', 'escape' => false]) ?>
		<p class="pop-or">or</p>
		<?= $this->Form->create('UserSignup', array('class' => 'validate_form form signup-form', 'id' => 'UserSignupForm', 'url' => array('controller' => 'users', 'action' => 'register')));
			$this->Form->templates([
			    'label' => false
			]);
		?>
		<?php 
			echo $this->Flash->render('signupbox');
		?>
		<a class="tooltips" id="signupTooltip" style="display:none;" href="#">
			<span>
			</span>
		</a>	
		<?= $this->Form->input('first_name',array('class' => 'form-control', 'required', 'placeholder' => 'First Name','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

		<?= $this->Form->input('last_name',array('class' => 'form-control', 'required', 'placeholder' => 'Last Name','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
		
		<?= $this->Form->input('email',array('class' => 'form-control', 'required', 'placeholder'=> 'Email Address','type' => 'email','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

		<?= $this->Form->hidden('trainer_id'); ?>

		<?= $this->Form->input('password',array('class' => 'form-control', 'required', 'placeholder'=> 'Password','type' => 'password','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

		<?= $this->Form->input('confirm_password',array('class' => 'form-control', 'required', 'placeholder'=> 'Confirm Password','type' => 'password','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
		
		<div class="form-row">
			<div class="g-recaptcha" data-sitekey="<?php echo ReCAPTCHA_PublicKey;?>"></div>
		</div>

                <?= $this->Form->submit('Sign up',array('class' => 'sub-btn','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
		
		<div class="form-row term-cond">
			<?= $this->Form->input('terms',array('id' => 'checkbox2', 'label' => false,'type' => 'checkbox','templates' => ['inputContainer' => '<div class="checkbox-div">{{content}}<label for="checkbox2"></label></div>'])); ?>
				By clicking sign up you are agree to our <?= $this->Html->link('Terms and Services',['controller' => 'terms']); ?>
			<p class="pop-or">or</p>
			<p>Existing members? <a href="#" class="login_here">Login Here</a></p>
		</div>
		<?= $this->Form->end(); ?>
			
		

	
		<button class="my_popup_close signup_popup_close">Close</button>
	</div>
</div>
<!-- popup end-->

<!-- Header element start -->
