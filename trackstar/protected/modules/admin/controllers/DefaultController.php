<?php

class DefaultController extends Controller
{
	public  $layout = 'application.modules.admin.views.layouts.main';
	public function actionIndex()
	{
		$this->render('index');
	}
}