<html lang="zh-CN">
  <head>
      <title>用户登录</title>
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
      <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
      <link rel="stylesheet" href="/static/css/user.css">
      <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
      <script src="//cdn.bootcss.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container">
      <form class="form-signin" role="form" method="post" action="/home/login">
        <h2 class="form-signin-heading">登录 <span><?php echo isset($message) ? $message : ""; ?></span></h2>
        <input type="text" name="username" class="form-control" placeholder="用户名" required="" autofocus="">
        <input type="password" name="password" class="form-control" placeholder="密码" required="">
        <div class="checkbox">
          <label>
            <a href="/forgot-password" title="">忘记密码</a>
          </label>
          <label><a href="/register" title="">注册</a></label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
      </form>
    </div>
  </body>
</html>