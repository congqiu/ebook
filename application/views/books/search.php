<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div class="row books-block">
					<div class="items">
					<?php foreach ($books as $book): ?>
						<div class="col-md-3 item">
							<h3><a href="/book/<?php echo $book->id; ?>" title="<?php echo $book->name; ?>"><?php echo $book->name; ?></a></h3>
							<div class="author">作者： <?php echo $book->author; ?></div>
							<div class="desc"><?php echo $book->source_desc; ?></div>
						</div>
					<?php endforeach ?>
					<?php if (sizeof($books) <= 0) {
						echo "没有该名字的书籍";
					} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>