<div class="row bookpage-board">
	<div class="book-set">
		<div class="page-set df">
			<?php if ($isMobile) { ?>
				<div class="color df">
					<span>阅读背景:</span>
					<a style="background-color: #e7f4fe" title="碧云" data-color="#e7f4fe" href="javascript:;"></a>
					<a style="background-color: #fffec0" title="蛋黄" data-color="#fffec0" href="javascript:;"></a>
					<a style="background-color: #efe5db" title="卡其" data-color="#efe5db" href="javascript:;"></a>
					<a style="background-color: #ffffff" title="白色" data-color="#ffffff" href="javascript:;"></a>
					<a style="background-color: #ffe7e7" title="粉红" data-color="#ffe7e7" href="javascript:;"></a>
				</div>
				<div class="df size">
					<span>字体大小:</span>
					<a data-size="12px" href="javascript:;">很小</a>
					<a data-size="16px" href="javascript:;">正常</a>
					<a data-size="28px" href="javascript:;">很大</a>
				</div>
			<?php } else { ?>
			<div class="color df">
				<span>阅读背景:</span>
				<a style="background-color: #e7f4fe" title="碧云" data-color="#e7f4fe" href="javascript:;"></a>
				<a style="background-color: #fffec0" title="蛋黄" data-color="#fffec0" href="javascript:;"></a>
				<a style="background-color: #f2f2f2" title="冷灰" data-color="#f2f2f2" href="javascript:;"></a>
				<a style="background-color: #efe5db" title="卡其" data-color="#efe5db" href="javascript:;"></a>
				<a style="background-color: #ffffff" title="白色" data-color="#ffffff" href="javascript:;"></a>
				<a style="background-color: #ffe7e7" title="粉红" data-color="#ffe7e7" href="javascript:;"></a>
			</div>
			<div class="df family">
				<span>字体选择:</span>
				<a style="font-family: 黑体" title="黑体" data-family="黑体" href="javascript:;">黑体</a>
				<a style="font-family: arial" title="宋体" data-family="arial" href="javascript:;">宋体</a>
				<a style="font-family: 楷体" title="楷体" data-family="楷体" href="javascript:;">楷体</a>
				<a style="font-family: microsoft yahei" title="雅黑" data-family="microsoft yahei" href="javascript:;">雅黑</a>
				<a style="font-family: 方正启体简体" title="启体" data-family="方正启体简体" href="javascript:;">启体</a>
			</div>
			<div class="df size">
				<span>字体大小:</span>
				<a data-size="16px" href="javascript:;">很小</a>
				<a data-size="20px" href="javascript:;">较小</a>
				<a data-size="24px" href="javascript:;">中等</a>
				<a data-size="28px" href="javascript:;">较大</a>
				<a data-size="32px" href="javascript:;">很大</a>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class="col-md-12 book-content">
		<div class="row">
			<div class="col-md-12">
				<h2><?php echo $bookpage->title; ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="operation">
					<a class="pre" href="/book/<?php echo $book; ?>/<?php echo $bookpage->pre; ?>">上一章</a>
					<a href="/book/<?php echo $book; ?>">回目录</a>
					<?php if ($bookpage->nex == -2) { ?>
						<a class="next" href="/books/bookcase">回书架</a>
					<?php } else { ?>
						<a class="next" href="/book/<?php echo $book; ?>/<?php echo $bookpage->nex; ?>">下一章</a>
					<?php } ?>
					<a class="add-mark" href="/ajax/add/bookmark/<?php echo $bookpage->id; ?>">加书签</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<p><?php echo $bookpage->content; ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="operation">
					<a class="pre" href="/book/<?php echo $book; ?>/<?php echo $bookpage->pre; ?>">上一章</a>
					<a href="/book/<?php echo $book; ?>">回目录</a>
					<?php if ($bookpage->nex == -2) { ?>
						<a class="next" href="/books/bookcase">回书架</a>
					<?php } else { ?>
						<a class="next" href="/book/<?php echo $book; ?>/<?php echo $bookpage->nex; ?>">下一章</a>
					<?php } ?>
					<a class="add-mark" href="/ajax/add/bookmark/<?php echo $bookpage->id; ?>">加书签</a>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
		initPage();
	jQuery(document).ready(function($) {
		$('.page-set .color a').click(function(event) {
			var color = $(this).attr('data-color');
			$('.book-content').css('backgroundColor', color);
			setCookie('backgroundColor', color);
		});
		$('.page-set .family a').click(function(event) {
			var family = $(this).attr('data-family');
			$('.book-content').css('fontFamily', family);
			setCookie('fontFamily', family);
		});
		$('.page-set .size a').click(function(event) {
			var fontSize = $(this).attr('data-size');
			$('.book-content').css('fontSize', fontSize);
			setCookie('fontSize', fontSize);
		});
		$('.add-mark').click(function(event) {
			event.preventDefault();
			var url = $(this).attr('href');
			$.get(url, function(data) {
				addPopup(data);
			});
		});
		$(document).keydown(function(event) {
			if (event.keyCode === 39) {
				event.preventDefault();
				$('.operation .next')[0].click();
			} else if (event.keyCode === 37) {
				event.preventDefault();
				console.log($('.operation .pre'))
				$('.operation .pre')[0].click();
			}
		});
	});
	function initPage() {
		if (getCookie('backgroundColor')) {
			$('.book-content').css('backgroundColor', getCookie('backgroundColor'));
		}
		if (getCookie('fontFamily')) {
			$('.book-content').css('fontFamily', getCookie('fontFamily'));
		}
		if (getCookie('fontSize')) {
			$('.book-content').css('fontSize', getCookie('fontSize'));
		}
	}
</script>