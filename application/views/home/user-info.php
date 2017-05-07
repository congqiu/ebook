<div class="row">
	<div class="col-md-6">
		用户名: <?php echo $user->username; ?>
	</div>
	<?php if ($user->type == 'admin') { ?>
		<div class="col-md-6">
			<a href="/book/add/book" title="">添加书籍</a>
		</div>
	<?php } ?>
	<div class="col-md-6">
		<a href="/home/update/" title="">修改用户信息</a>
	</div>
	<div class="col-md-6">
		<a href="/home/password/" title="">修改密码</a>
	</div>
	<div class="col-md-6">
		<?php if ($user->is_cookie) { ?>
			<a class="is-auto-set active" href="/ajax/user/is-auto-login" title="">取消自动登录</a>
		<?php } else { ?>
			<a class="is-auto-set" href="/ajax/user/is-auto-login" title="">设置自动登录</a>
		<?php } ?>
	</div>
	<div class="col-md-6">
		<?php if ($user->is_auto_bookmark) { ?>
			<a class="is-auto-set active" href="/ajax/user/is-auto-bookmark">取消自动保存书签（已经加入书架的书）</a>
		<?php } else { ?>
			<a class="is-auto-set" href="/ajax/user/is-auto-bookmark">设置自动保存书签（已经加入书架的书）</a>
		<?php } ?>
	</div>
	<div class="col-md-6">
		<a href="/home/avatar/" title=""> <img src="/static/avatar/<?php echo $user->avatar; ?>" alt=""></a>
	</div>
</div>

<script>
	jQuery(document).ready(function($) {
		$('.is-auto-set').click(function(event) {
			event.preventDefault();
			var active = 1;
			if ($(this).hasClass('active')) {
				active = 0;
				$(this).removeClass('active').text($(this).text().replace('取消', '设置'));
			} else {
				$(this).addClass('active').text($(this).text().replace('设置', '取消'));
			}
			var url = $(this).attr('href') + '/' + active + '?' + Math.random();
			$.get(url, function(data) {
				addPopup(data);
			});
		});
	});
</script>