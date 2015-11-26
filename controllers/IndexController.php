<?php
/**
 * IndexController 默认控制器
 */
class IndexController extends ApiPublicController
{
    public function actionIndex()
    {
        echo "Test The YII Framework" . "\n";
        echo date("Y-m-d H:i:s", strtotime("now")) . "\n";
        echo "HELLO WORLD" . "\n";
        echo time() + strtotime('+ 30day') . "\n";
        echo date("H") . "\n";
        echo date("H-i-s");
    }
}