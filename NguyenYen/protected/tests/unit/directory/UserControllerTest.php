<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserControllerTest
 *
 * @author nguyenthihaiyen
 */
class UserControllerTest extends CDbTestCase{
    //put your code here
    public function testViewRouteResolves()
    {
        $_SERVER['SCRIPT_FILENAME'] = 'index.php';
        $_SERVER['SCRIPT_NAME'] =  '/index.php';
        $_SERVER['REQUEST_URI'] = 'user/1';

        $_SERVER['REQUEST_METHOD'] = 'GET';

        $route = Yii::app()->getUrlManager()->parseUrl(new CHttpRequest());

        list($controller, $action) = Yii::app()->createController($route);

        $this->assertInstanceOf('UserController', $controller);
        $this->assertEquals('view', $action);
    }
}

?>
