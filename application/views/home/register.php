<html lang="zh-CN">
  <head>
      <title>用户注册</title>
      <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
      <link rel="stylesheet" href="/static/css/user.css">
      <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
      <script src="//cdn.bootcss.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container">
      <form class="form-signin" role="form" action="/home/register" method="post">
        <h2 class="form-signin-heading"><?php echo $title; ?></h2>
        <?php echo validation_errors(); ?>
        <input type="text" name="username" class="form-control" placeholder="用户名" required="" autofocus="" value="<?php echo set_value('username'); ?>">
        <input type="password" name="password" class="form-control" placeholder="密码" required>
        <input type="email" name="email" class="form-control" placeholder="邮箱" required value="<?php echo set_value('email'); ?>">
        
        <button class="btn btn-lg btn-primary btn-block" type="submit">注册</button>
      </form>
    </div>
  </body>
</html>