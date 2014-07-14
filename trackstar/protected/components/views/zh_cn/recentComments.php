<ul>
	<?php foreach ($this->getRecentComments() as $comment):?>
	<div>
		<?php echo $comment->author->username;?> 添加一条评论
	</div>
	<div class="issue">
		<?php echo CHtml::link(Comment::model()->mbSubstr(CHtml::encode($comment->issue->name),28),array('issue/view','id'=>$comment->issue->id));?>
	</div>
	<?php endforeach;?>
</ul>