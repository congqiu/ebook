<div class="row">
	<div class="col-md-12">
		<form role="form" action="/forgot-password" method="post" accept-charset="utf-8">
			<?php echo validation_errors(); ?>
			<span><?php echo isset($message) ? $message : ""; ?></span>
			<div class="form-group">
				<label for="username">用户名</label>
				<input type="text" name="username" class="form-control" placeholder="用户名" required="">
			</div>
	        <div class="form-group">
	            <label for="email">邮箱</label>
	            <input type="email" name="email" class="form-control" placeholder="邮箱" required="">
	        </div>
			<div class="form-group">
				<label for="captcha"><img src="/static/captcha/<?php echo $captcha; ?>" alt=""></label>
				<input type="text" name="captcha" class="form-control" placeholder="验证码" required="" autocomplete="off">
			</div>
        	<button class="btn btn-default" type="submit">更新</button>
		</form>
	</div>
</div>