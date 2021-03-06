<?php
/* @var $this IssueController */
/* @var $model Issue */

$this->breadcrumbs=array(
	'Project'=>array('/project/view','id'=>$model->project->id),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Issue', 'url'=>array('index','pid'=>$model->project->id)),
	array('label'=>'Create Issue', 'url'=>array('create','pid'=>$model->project->id)),
// 	array('label'=>'Update Issue', 'url'=>array('update', 'id'=>$model->id,'pid'=>$model->project->id)),
// 	array('label'=>'Delete Issue', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
// 	array('label'=>'Manage Issue', 'url'=>array('admin','pid'=>$model->project->id)),
);
if($model->create_user_id===Yii::app()->user->id){
	$this->menu[]=array('label'=>'Update Issue', 'url'=>array('update', 'id'=>$model->id,'pid'=>$model->project->id)
	);
}
?>

<h1>View Issue #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'project_id',
// 		'type_id',
		array(
			'name'=>'type_id',
			'value'=>CHtml::encode($model->getTypeText())
		),
// 		'status_id',
		array(
			'name'=>'status_id',
			'value'=>CHtml::encode($model->getStatusText())
		),
// 		'owner_id',
		array(
			'name'=>'owner_id',
			'value'=>CHtml::encode($model->owner->username)	
		),
// 		'requester_id',
		array(
			'name'=>'requester_id',
			'value'=>CHtml::encode($model->requester->username)
		),
// 		'create_time',
// 		'create_user_id',
// 		'update_time',
// 		'update_user_id',
	),
)); ?>
<div id="comments">
	<?php if ($model->commentCount>=1):?>
		<h3>
			<?php echo $model->commentCount>1?$model->commentCount . ' comments':'one comment';?>
		</h3>
	
		<?php $this->renderPartial('_comments',array('comments'=>$model->comments,));?>
	<?php endif;?>
	
	<h3>Leave a Comment</h3>
	<?php $project=Project::model()->findbyPk($model->project_id);?>
	<?php if(Yii::app()->user->checkAccess('member',array('project'=>$project)))://只有加入project的才能在这里发表评论 ?>
		
	
		<?php if(Yii::app()->user->hasFlash('commentSubmitted')):?>
		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('commentSubmitted');?>
		</div>
		<?php else:?>
			<?php $this->renderPartial('/comment/_form',array('model'=>$comment,));?><?php //$comment在IssueController中actionView中定义?>
		<?php endif;?>
	<?php else:?>
		<p class="hint">you must join project then can leave a comment</p>
		<?php echo CHtml::link('Join Project',array('project/joinproject','id'=>$model->project->id)); 
// 			$this->redirect(array('view','id'=>$model->id));
		?>
<!-- 		$this->redirect(array('view','id'=>$model->id)); -->
	<?php endif;?>
</div>
