<!--Admin logn section end Here-->
<?= $this->Form->create('', ['class' => 'login-form']) ?>
	<h3 class="form-title">Sign In</h3>
	<div class="alert alert-danger display-hide">
		<button class="close" data-close="alert"></button>
		<span>
		Invalid email and password. </span>
	</div>
	<div class="form-group">
		<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
		<label class="control-label visible-ie8 visible-ie9">Email</label>
		<div class="input-icon">
			<?php echo $this->Form->input('email', array('placeholder' => 'Email', 'class' => 'form-control placeholder-no-fix'));?>
		</div>
	</div>
	<div class="form-group">
		<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
		<label class="control-label visible-ie8 visible-ie9">Password</label>
		<div class="input-icon">
			<?php echo $this->Form->input('password', array('placeholder' => 'Password', 'class' => 'form-control placeholder-no-fix'));?>
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-success uppercase">Login</button>
		<label class="rememberme check">
			<input type="checkbox" value="1" name="remember_me" class="checkbox">Remember 
		</label>
		<a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
	</div>
</form>
<!-- END LOGIN FORM -->
<!-- BEGIN FORGOT PASSWORD FORM -->
<form class="forget-form" action="index.html" method="post">
	<h3>Forget Password ?</h3>
	<p>
		 Enter your e-mail address below to reset your password.
	</p>
	<div class="form-group">
		<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email"/>
	</div>
	<div class="form-actions">
		<button type="button" id="back-btn" class="btn btn-default">Back</button>
		<button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
	</div>
<?= $this->Form->end() ?>