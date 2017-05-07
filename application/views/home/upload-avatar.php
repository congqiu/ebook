<div class="row">
  <div class="col-md-12">
    <?php echo $error; ?>
      <?php echo form_open_multipart('/home/avatar/'); ?>
        <div class="form-group">
          <label for="username">用户名: <?php echo $user->username; ?></label>
        </div>
        <div class="form-group">
            <label for="email">新头像</label>
            <input type="file" name="avatar" size="20" />
        </div>
        <button class="btn btn-default" type="submit">上传新头像</button>
      </form>
  </div>
</div>