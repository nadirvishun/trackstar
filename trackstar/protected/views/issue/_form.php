<?php
/* @var $this IssueController */
/* @var $model Issue */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'issue-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>2000)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<!-- 隐藏project_id -->
	<div class="row"> 
    <?php echo $form->hiddenField($model,'project_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type_id'); ?>
		<?php echo $form->dropDownList($model,'type_id',$model->getTypeOptions()); ?>
		<?php echo $form->error($model,'type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status_id'); ?>
		<?php echo $form->dropDownList($model,'status_id',$model->getStatusOptions()); ?>
		<?php echo $form->error($model,'status_id'); ?>
	</div>
	<!-- 将id替换成名字,且以下拉菜单的形式 ,为什么不用下面那个，因为这样读出来的是全部的，而我需要与Project关联的？？但我为什么不是谁登录的就是谁创建的？提出问题，然后有人将问题统计输入？-->
	<div class="row">
		<?php echo $form->labelEx($model,'owner_id'); ?>
		<?php echo $form->dropDownList($model,'owner_id',$this->getProject()->getUserOptions()); ?>
		<!--<?php echo $form->dropDownList($model,'owner_id',Project::model()->getUserOptions()); ?>-->
		<?php echo $form->error($model,'owner_id'); ?>
	</div>
	
	<!-- 将id替换成名字,且以下拉菜单的形式 -->
	<div class="row">
		<?php echo $form->labelEx($model,'requester_id'); ?>
		<?php echo $form->dropDownList($model,'requester_id',$this->getProject()->getUserOptions()); ?>
		<?php echo $form->error($model,'requester_id'); ?>
	</div>

	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->