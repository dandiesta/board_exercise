
<html>
	<body>
		<h1>SimplePagination Example</h1>
		
		<?php if($pagination->current > 1): ?>
			<a href='?page=<?php echo $pagination->prev ?>'>Previous</a>
		<?php endif ?>
		
		
		<?php foreach ($items as $item): ?>
			<a href="<?php echo $item['id'] ?>"><?php echo $item['id'] ?></a>&nbsp;
		<?php endforeach ?>
		
		<?php if(!$pagination->is_last_page): ?>
			<a href='?page=<?php echo $pagination->next ?>'>Next</a>
		<?php endif ?>
	</body>
</html>
