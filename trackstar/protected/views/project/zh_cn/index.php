<?php
/* @var $this ProjectController */
/* @var $dataProvider CActiveDataProvider */

// $this->breadcrumbs=array(
// 	'Projects',
// );

$this->menu=array(
	array('label'=>'创建项目', 'url'=>array('create')),
	array('label'=>'修改密码', 'url'=>array('/user/updatepass','id'=>Yii::app()->user->id)),
// 	array('label'=>'Manage Project', 'url'=>array('admin')),
);
?>
<?php //显示系统信息?>
<?php if($sysMessage!=null):?>
	<div class="sys-message">
		<?php echo $sysMessage;?>
	</div>
	<?php
		Yii::app()->clientScript->registerScript(
			'fadeAndHideEffect',
			'$(".sys-message").animate({opacity:1.0},5000).fadeOut("slow");'
		); 
	?>
<?php endif;?>

<h1>项目</h1>
<?php //显示项目?>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>




<div style="clear:both"></div>
<?php /**

<?php //显示评论?>

<div class="view2">
 <?php  //利用片段缓存来显示
// 	$key="TrackStar.ProjectListing.RecentComments";
// 	if($this->beginCache($key,array('duration'=>120))){
// 		$this->beginWidget('zii.widgets.CPortlet', array( 
//     		'title'=>'Recent Comments',
// 		)); 
// 		$this->widget('RecentComments'); 
// 		$this->endWidget();
// 		$this->endCache();
// 	} 
 ?>
<?php  	
		$this->beginWidget('zii.widgets.CPortlet', array( 
    		'title'=>'Recent Comments',
		)); 
		$this->widget('RecentComments'); 
		$this->endWidget(); 
?>
</div>
*/?>