<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'用户',
	$model->username=>array('view','id'=>$model->id),
	'修改密码',
);

$this->menu=array(
// 	array('label'=>'List User', 'url'=>array('index')),
// 	array('label'=>'Create User', 'url'=>array('create')),
// 	array('label'=>'View User', 'url'=>array('view', 'id'=>$model->id)),
// 	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h2 style="color:#f67600">修改用户"<?php echo $model->username; ?>"的密码</h2>

<?php echo $this->renderPartial('_formpass', array('model'=>$model)); ?>