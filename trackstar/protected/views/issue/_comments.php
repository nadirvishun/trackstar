<?php foreach ($comments as $comment):?>
<div class="comment">
	
	<div class="comment1">
	<div class="author">
		<?php echo $comment->author->username;?>:
	</div>
	<div class="time">
		on <?php echo date('F j,Y \a\t h:i a',strtotime($comment->create_time));?>
	</div>
	</div>
	<div class="updatecomment">
	<?php if($comment->create_user_id===Yii::app()->user->id):?>
		<?php echo CHtml::link('update',array('/comment/update','id'=>$comment->id)); ?>
	<?php endif;?>
	</div>
	
	<div style="clear: both"></div>
	<div class="content">
		<?php echo nl2br(CHtml::encode($comment->content));?>
	</div>
	<hr>
</div>
<?php endforeach;?>
