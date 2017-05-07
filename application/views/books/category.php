<div>
	<div class="col-md-12">
		<div class="row books-block">
			<ul class="items">
				<h1><?php echo $type->name; ?></h1>
			<?php foreach ($books as $book): ?>
				<li class="col-md-12 item">
					<h3><a href="/book/<?php echo $book->id; ?>" title="<?php echo $book->name; ?>"><?php echo $book->name; ?></a></h3>
					<div class="df">
						<div class="author">作者： <?php echo $book->author; ?></div>
						<div class="status"><?php echo $book->source_status; ?></div>
					</div>
					<div class="desc"><?php echo $book->source_desc; ?></div>
				</li>
			<?php endforeach ?>
			</ul>
			<div class="row pagination">
				<div class="col-md-12">
					<?php echo $pagination; ?>
				</div>
			</div>
		</div>
	</div>
</div>