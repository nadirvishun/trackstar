<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<?php /**
		显示最近登录时间，之所以不能直接从数据库读取，是因为你只能读取到这次登录的时间而不是上次的。
	*/

?>
<?php if(!Yii::app()->user->isGuest):?>
<p>
	You last logged in on<?php echo date('l,F d,Y,g:i a',Yii::app()->user->lastLoginTime);?>
</p>
<?php endif;?>		

<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>
