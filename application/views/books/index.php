<div>
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
			</div>
		</div>
		<div class="row">
			<div class="book-types books-block">
			<?php foreach ($types as $type): ?>
				<div class="book-type">
					<h3><a href="/book/category/<?php echo $type->name; ?>" title="<?php echo $type->name; ?>"><?php echo $type->name; ?></a></h3>
					<div class="type-books clearfix">
						<?php foreach ($type_books[$type->id] as $book): ?>
							<div class="col-md-3 item">
								<h3><a href="/book/<?php echo $book->id; ?>" title="<?php echo $book->name; ?>"><?php echo $book->name; ?></a></h3>
								<div class="author">作者： <?php echo $book->author; ?></div>
								<div class="desc"><?php echo $book->source_desc; ?></div>
							</div>
						<?php endforeach ?>
					</div>
				</div>
			<?php endforeach ?>
			</div>
		</div>
	</div>
</div>
