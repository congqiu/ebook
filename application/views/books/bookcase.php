<div class="row">
	<div class="col-md-12">
		<div class="row bookcase-lists">
			<div class="col-md-12">
				<div class="items">
					<?php foreach ($books as $index => $book): ?>
						<div class="item clearfix">
							<div class="name">
								<span>书名：</span>
								<a href="/book/<?php echo $book->id; ?>" title="<?php echo $book->name; ?>"><?php echo $book->name; ?></a>
							</div>
							<div class="latest">
								<span>最新：</span>
								<?php if (isset($latests[$index])) {?>
									<a href="/book/<?php echo $book->id; ?>/<?php echo $latests[$index]->num; ?>"><?php echo $latests[$index]->title; ?></a>
								<?php } ?>
							</div>
							<div class="bookmark">
								<span>书签：</span>
								<?php if (isset($bookmarks[$index])) {?>
									<a href="/book/<?php echo $book->id; ?>/<?php echo $bookmarks[$index]->num; ?>"><?php echo $bookmarks[$index]->title; ?></a>
								<?php } ?>
							</div>
							<div class="operation">
								<a class="remove-bookcase" href="/ajax/remove/bookcase/<?php echo $book->id; ?>">移除</a>
								<?php if (isset($bookmarks[$index]) && $bookmarks[$index]->id != $latests[$index]->id) {
									echo "<span style='color:red;'>New</span>";
								} ?>
							</div>
							<!-- <?php echo date('m-d', $latests[$index]->create_time); ?>
							 -->
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function($) {
		$('.remove-bookcase').click(function(event) {
			event.preventDefault();
			var url = $(this).attr('href') + '/0?' + Math.random();
			$.get(url, function(data) {
				addPopup(data);
			});
			$(this).parents('.item').remove();
		});
	});
</script>