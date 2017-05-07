<div class="row">
  <div class="col-md-12">
      <form role="form" action="/home/password/" method="post" accept-charset="utf-8">
        <?php echo validation_errors(); ?>
        <div class="form-group">
          <label for="username">用户名: <?php echo set_value('username', isset($user->username) ? $user->username : ''); ?></label>
        </div>

        <div class="form-group">
          <label for="old-password">原始密码</label>
          <input type="password" name="old-password" class="form-control" placeholder="原始密码" >
        </div>
        <div class="form-group">
          <label for="password">新密码</label>
          <input type="password" name="password" class="form-control" placeholder="新密码" >
        </div>
        <div class="form-group">
          <label for="passconf">确认密码</label>
          <input type="password" name="passconf" class="form-control" placeholder="确认密码" >
        </div>
        <?php echo form_hidden('id', $user->id) ?>
        <button class="btn btn-default" type="submit">更新</button>
      </form>
  </div>
</div>