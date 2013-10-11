<?php
/**
 * Yii RESTful API
 *
 * @link      https://github.com/paysio/yii-rest-api
 * @copyright Copyright (c) 2012 Pays I/O Ltd. (http://pays.io)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT license
 * @package   REST_Service_Demo
 */

/**
 * @method bool isPost()
 * @method bool isPut()
 * @method bool isDelete()
 * @method string renderRest(string $view, array $data = null, bool $return = false, array $fields = array())
 * @method void redirectRest(string $url, bool $terminate = true, int $statusCode = 302)
 * @method bool isRestService()
 * @method \rest\Service getRestService()
 */
class RestUserController extends ApiController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return array(
            'restAPI' => array('class' => '\rest\controller\Behavior')
        );
    }

    public function actionIndex()
    {
        $model = new User;
		$model->attributes = array('id' => 1, 'email' => 'admin@mulodo.com', 'password' => '123456');
        $data = array(
            'count' => 1,
            'data' => array($model)
        );
        $this->render('empty', $data);
    }

    public function actionView()
    {
        $model = new RestUser();
        $model->attributes = array('email' => 'admin@mulodo.com', 'name' => 'admin');
        $data = array(
            'count' => 1,
            'data' => $model
        );
        $this->render('empty', $data);
    }

    public function actionCreate()
    {
        $model = new RestUser();

        if ($this->isPost() && ($data = $_POST)) {
            $model->attributes = $data;
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model), true, 201);
            }
        }
        $this->render('empty', array('model' => $model));
    }

    public function actionUpdate()
    {
        $model = $this->loadModel();
        $data = array(
            'email' => Yii::app()->request->getPut('email'),
            'name' => Yii::app()->request->getPut('name'),
        );

        if ($this->isPut() && $data) {
            $model->attributes = $data;
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model));
            }
        }
        $this->render('empty', array('model' => $model));
    }

    public function actionDelete()
    {
        if ($this->isDelete()) {
            $model = $this->loadModel();
            $this->redirect(array('index', $model));
        } else {
            throw new \CHttpException(400, Yii::t('app', 'Invalid delete request'));
        }
    }

    /**
     * @return RestUser
     * @throws CHttpException
     */
    public function loadModel()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $object = new RestUser();
        if ($id != $object->id) {
            throw new CHttpException(404, Yii::t('app', 'Object not found'));
        }
        return $object;
    }

}