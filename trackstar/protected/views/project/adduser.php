<?php
$this->pageTitle=Yii::app()->name.'-Add User To Project';
$this->breadcrumbs=array(
		$model->project->name=>array('view','id'=>$model->project->id),
		'Add User',
);

$this->menu=array(
		array('label'=>'Back To Project', 'url'=>array('view','id'=>$model->project->id)),
		
);
?>

<h1>Add User To<?php echo $model->project->name;?></h1>
<?php //验证表单?>
<?php if(Yii::app()->user->hasFlash('success')):?>
	<div class="successMessage">
		<?php echo Yii::app()->user->getFlash('success');?>
	</div>
<?php endif;?>

<?php //实际表单显示?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); //自动填补username的小控件?>
		<?php $this->widget('CAutoComplete',array(
			'model'=>$model,
			'attribute'=>'username',
			'data'=>$usernames,
			'multiple'=>false,
			'htmlOptions'=>array('size'=>25),
		)) ; ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role'); ?>
		<?php echo $form->dropDownList($model,'role',Project::getUserRoleOptions()); ?>
		<?php echo $form->error($model,'role'); ?>
	</div>

	

	<div class="row buttons">
		<?php echo CHtml::submitButton('Add User'); ?>
	</div>

<?php $this->endWidget(); ?>
</div>