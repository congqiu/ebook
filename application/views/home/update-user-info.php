<div class="row">
  <div class="col-md-12">
      <form role="form" action="/home/update/" method="post" accept-charset="utf-8">
        <?php echo validation_errors(); ?>
        <div class="form-group">
          <label for="username">用户名</label>
          <input type="text" name="username" class="form-control" placeholder="用户名" required="" value="<?php echo set_value('username', isset($user->username) ? $user->username : ''); ?>">
        </div>
        <div class="form-group">
            <label for="email">邮箱</label>
            <input type="email" name="email" class="form-control" placeholder="邮箱" required="" value="<?php echo set_value('email', isset($user->email) ? $user->email : ''); ?>">
        </div>

        <div class="form-group">
          <label for="name">姓名</label>
          <input type="text" name="name" class="form-control" placeholder="姓名" value="<?php echo set_value('name', isset($user->name) ? $user->name : ''); ?>">
        </div>
        <div class="form-group">
            <label for="sex">性别</label>
            <?php echo form_dropdown('sex', $sex, set_value('sex', isset($user->sex) ? $user->sex : ''), 'class="form-control input-lg"'); ?>
        </div>
        <?php echo form_hidden('id', $user->id) ?>
        <?php echo form_hidden('create_time', $user->create_time) ?>
        <button class="btn btn-default" type="submit">更新</button>
      </form>
  </div>
</div>