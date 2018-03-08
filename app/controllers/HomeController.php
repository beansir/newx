<?php
/**
 * @author: bean
 * @version: 1.0
 */
namespace app\controllers;

use newx\base\BaseController;

class HomeController extends BaseController
{
    public function actionIndex()
    {
        return $this->view('index');
    }
}