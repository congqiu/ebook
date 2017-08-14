<html lang="zh-CN">
  <head>
    <title><?php echo isset($meta_title) ? $meta_title : '书友小说--无广告在线小说阅读'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="keywords" content="<?php echo isset($meta_key) ? $meta_key : '小说，最新小说，玄幻，科幻，书友小说'; ?>">
    <meta name="description" content="<?php echo isset($meta_desc) ? $meta_desc : '最新最热的小说尽在书友小说，无广告在线阅读体验'; ?>">
    <link rel="shortcut icon" href="/static/image/icon.ico">
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/css/book.css" rel="stylesheet">

    <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="/static/js/base.js"></script>
  </head>
  <body>
    <header id="header">
      <?php $_page = isset($page) ? $page : 'default' ?>
      <nav class="navbar navbar-fixed-top navbar-inverse">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">书友</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">书友</a>
            <span class="page-title"></span>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="<?php echo $_page == 'book-index' ? 'active' : '' ?>"><a href="/">首页</a></li>
              <li class="<?php echo $_page == 'bookcase' ? 'active' : '' ?>"><a href="/books/bookcase">我的书架</a></li>
              <?php if ($this->session->userdata('username') || (isset($user) && $user)) { ?>
                <li class="<?php echo $_page == 'home-page' ? 'active' : '' ?>"><a href="/home"><?php echo $this->session->userdata('username') ? $this->session->userdata('username') : $user->username; ?></a></li>
                <li><a href="/logout">退出</a></li>
              <?php } else { ?>
                <li class="<?php echo $_page == 'login-page' ? 'active' : '' ?>"><a href="/login">登录</a></li>
              <?php } ?>
            </ul>
            <div class="navbar-form navbar-right search-board">
              <form action="/books/search" method="post">
                <input type="search" name="name" class="form-control" placeholder="请输入书名">
                <input type="submit" class="btn btn-default">
              </form>
            </div>
          </div>
        </div>
      </nav>
    </header>
    <div id="custom-popup-board">
      <div class="popup-board">
        <i class="popup-close-btn">x</i>
        <div class="title">消息提示</div>
        <div class="popup-content">没有新消息</div>
        <button class="popup-close-btn">关闭</button>
      </div>
    </div>
    <div class="page-content" id="<?php echo $_page; ?>">
      <div class="container">
