<div class="row blogs-board">
	<div class="col-md-12">
		<div class="row bookinfo">
			<div class="col-md-7 name df">
				<h1><?php echo $book->name; ?></h1>
				<span>作者：<?php echo $book->author; ?></span>
			</div>
			<div class="col-md-5 operation">
				<a class="add-bookcase" href="/ajax/add/bookcase/<?php echo $book->id; ?>" title="">加入书架</a>
				<a href="#footer" title="">直达底部</a>
				<?php if ($user && $user->type == 'admin') { ?>
				<a href="/book/add/source/<?php echo $book->id; ?>" title="">添加来源</a>
				<?php } ?>
			</div>
		</div>
		<div class="row latest">
			<?php if ($latest): ?>
			<div class="col-md-8">
				<div>
					最新章节：<a href="/book/<?php echo $book->id; ?>/<?php echo $latest->num; ?>"><?php echo $latest->title; ?></a>
				</div>
			</div>
			<div class="col-md-4">
				<span>状态：<?php echo $book->source_status; ?></span>
				<span>更新时间：<?php echo date('Y-m-d' , $latest->create_time); ?></span>
			</div>
			<?php endif ?>
		</div>
		<div class="row desc">
			<div class="col-md-12">
				内容简介： <?php echo $book->source_desc; ?>		
			</div>
		</div>
		<div class="row catalogs">
			<div class="col-md-12">
				<div>
					<?php echo $message; ?>
				</div>
				<ul class="df">
					<?php foreach ($catalogs as $catalog): ?>
						<li><a href="/book/<?php echo $book->id; ?>/<?php echo $catalog->num; ?>"><?php echo $catalog->title; ?></a></li>
					<?php endforeach ?>
				</ul>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function($) {
		$('.add-bookcase').click(function(event) {
			event.preventDefault();
			var url = $(this).attr('href') + '/0?' + Math.random();
			$.get(url, function(data) {
				addPopup(data);
			});
		});
	});
</script>