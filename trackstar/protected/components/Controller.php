<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public function init(){
		if(isset($_GET['lang']) && $_GET['lang']!=""){
			//更改语言
			Yii::app()->language=$_GET['lang'];//如果有请求，则设置语言为请求语言并设置cookie
			//设置cookie
			Yii::app()->request->cookies['lang']=new CHttpCookie('lang', $_GET['lang']);
		}elseif (isset(Yii::app()->request->cookies['lang']) && Yii::app()->request->cookies['lang']->value!=""){
			Yii::app()->language=Yii::app()->request->cookies['lang']->value;//如果有cookie，则用cookie值
		}else{//其它情况则根据浏览器语言来定
			//Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3
			$lang=explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);//explode以,为分割，将其转换为数组，
			Yii::app()->language=strtolower(str_replace('-', '_', $lang[0]));//将其中的-全部用_来替换，且小写，我们只需要取第一个数组就行。
		}
	}
	public function langurl($lang='en_us'){
		if($lang==Yii::app()->language){
			return null;
		}
		$current_uri=Yii::app()->request->requestUri;
		if(strrpos($current_uri,'lang=')){//防止重复传值？？
			$langstr= 'lang=' . Yii::app()->language;
			$current_uri=str_replace('?' . $langstr . '&','?', $current_uri);
			$current_uri=str_replace('?' . $langstr,'', $current_uri);
			$current_uri=str_replace('&' . $langstr,'', $current_uri);
		}
		if(strripos($current_uri, '?')){
			return $current_uri . '$lang=' . $lang;
		}else{
			return $current_uri . '?lang=' . $lang;
		}
	}
}