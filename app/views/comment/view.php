<h1><?php enquote_string($thread->title) ?></h1>
<hr />
<!--<pre><?php print_r($comments); ?></pre>-->
<?php foreach ($display as $k => $v): ?>
	<div class="well">
		<div style="font-size:23px;"><?php echo readable_text($v['body']) ?></div>
		<div><small><?php enquote_string($v['created']) ?></small></div>
		<div class="meta">
			by: <?php enquote_string($v['username']) ?> 
		</div>
	</div>
<?php endforeach ?>

<!--pagination-->
<?php if($pagination->current > 1): ?>
<a class="btn btn-danger" href='?page=<?php echo $pagination->prev ?>&thread_id=<?php enquote_string($thread->id)?>'>Previous</a>
<?php endif ?>

<?php for ($i=1; $i <= $count_chunks; $i++): ?>
  <a class="btn btn-danger" href="?page=<?php echo $i ?>&thread_id=<?php enquote_string($thread->id)?>"><?php echo $i; ?></a>&nbsp;
<?php endfor ?>  


<?php if(!$pagination->is_last_page): ?>
<a class="btn btn-danger" href='?page=<?php echo $pagination->next ?>&thread_id=<?php enquote_string($thread->id)?>'>Next</a>
<?php endif ?>
<hr>

<form class="well" method="post" action="<?php enquote_string(url('comment/write')) ?>">
	<label>COMMENT</label>
	<textarea name="body" class="span11"><?php enquote_string(Param::get('body'))?></textarea>
	<br />
	<input type="hidden" name="thread_id" value="<?php enquote_string($thread->id) ?>">
	<input type="hidden" name="page_next" value="write_end">
	<button type="submit" class="btn btn-danger"> Submit</button>
</form>