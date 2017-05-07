<div class="row">
  <div class="col-md-12">
      <form role="form" method="post" accept-charset="utf-8">
        <?php echo validation_errors(); ?>
        <div class="form-group">
          <label for="source">书名</label>
          <?php echo $book->name; ?>
        </div>
        <div class="form-group">
          <label for="source">来源</label>
          <input type="text" name="source" class="form-control" placeholder="来源" >
        </div>
        <div class="form-group">
          <label for="order">权重</label>
          <select name="order" class="form-control">
            <option value="-1">备用</option>
            <option value="0" selected>正常</option>
            <option value="1">优先</option>
          </select>
        </div>
        <div class="form-group">
          <label for="alias">网站</label>
          <input type="text" name="alias" class="form-control" placeholder="网站" >
        </div>
        <?php echo form_hidden('book', $book->id) ?>
        <button class="btn btn-default" type="submit">添加</button>
      </form>
  </div>
</div>