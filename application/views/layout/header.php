<html lang="zh-CN">
    <head>
        <title><?php echo isset($meta_title) ? $meta_title : '书友小说--无广告在线小说阅读'; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="keywords" content="<?php echo isset($meta_key) ? $meta_key : '小说，最新小说，玄幻，科幻，书友小说'; ?>">
        <meta name="description" content="<?php echo isset($meta_desc) ? $meta_desc : '最新最热的小说尽在书友小说，无广告在线阅读体验'; ?>">
        <link rel="shortcut icon" href="/static/image/icon.ico">
        <link href="//cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="/static/css/style.css" rel="stylesheet">

        <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
        <script src="//cdn.bootcss.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
        <script src="/static/js/base.js"></script>
    </head>
    <body class="<?php echo isset($snow) ? '' : 'snow'; ?>">
    	<div class="masthead">
        <div class="container">
          <nav class="df">
            <div>
              <a class="nav-item active" href="/">首页</a>
              <a class="nav-item active" href="/books/bookcase">我的书架</a>
              <?php if ($this->session->userdata('username') || (isset($user) && $user)) { ?>
                <a class="nav-item" href="/home"><?php echo $this->session->userdata('username') ? $this->session->userdata('username') : $user->username; ?></a>
                <a class="nav-item" href="/logout">退出</a>
              <?php } else { ?>
                <a class="nav-item" href="/login">登录</a>
              <?php } ?>
            </div>
            <div>
              <form action="/books/search" method="post">
                <input type="search" name="name">
                <input type="submit">
              </form>
            </div>
          </nav>
        </div>
      </div>
      <div id="custom-popup-board">
        <div class="popup-board">
          <i class="popup-close-btn">x</i>
          <div class="title">消息提示</div>
          <div class="popup-content">没有新消息</div>
          <button class="popup-close-btn">关闭</button>
        </div>
      </div>
      <div class="container page-content" id="<?php echo isset($page) ? $page : 'default'; ?>">