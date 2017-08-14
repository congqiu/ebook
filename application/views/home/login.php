<div class="auth-form">
  <form class="form-signin" role="form" method="post">
    <h2 class="form-signin-heading">登录</h2>
    <div class="form-errors"><?php echo isset($message) ? $message : ""; ?></div>
    <div class="form-group">
      <label for="username">用户名</label>
      <input type="text" name="username" class="form-control" placeholder="用户名" required>
    </div>
    <div class="form-group">
      <label for="username">密码<a href="/forgot-password" title="" class="forgot">忘记密码</a></label>
      <input type="password" name="password" class="form-control" placeholder="密码" required="">
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
  </form>
  <div class="change-action">
    还没有账号？
    <a href="/register" title="">注册一个</a>
  </div>
</div>