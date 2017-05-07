<div class="row">
  <div class="col-md-12">
      <form role="form" method="post" accept-charset="utf-8">
        <?php echo validation_errors(); ?>
        <div class="form-group">
          <label for="name">书名</label>
          <input type="text" name="name" class="form-control" placeholder="书名" >
        </div>
        <div class="form-group">
          <label for="author">作者</label>
          <input type="text" name="author" class="form-control" placeholder="作者" >
        </div>
        <div class="form-group">
          <label for="status">状态</label>
          <select name="status" class="form-control">
            <option value="1" selected>连载中</option>
            <option value="2">完结</option>
            <option value="0">其他</option>
          </select>
        </div>
        <div class="form-group">
          <label for="source_status">来源状态</label>
          <select name="source_status" class="form-control" >
            <option value="连载中" selected>连载中</option>
            <option value="完结">完结</option>
            <option value="其他">其他</option>
          </select>
        </div>
        <div class="form-group">
          <label for="type">类型</label>
          <?php echo form_dropdown('type', $types, 1, 'class="form-control"'); ?>
        </div>
        <div class="form-group">
          <label for="source_desc">介绍</label>
          <textarea name="source_desc" class="form-control" rows="3"></textarea>
        </div>
        <button class="btn btn-default" type="submit">添加</button>
      </form>
  </div>
</div>