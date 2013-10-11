<?php 
 
class RegionSingleton extends CApplicationComponent
{
    private $_model=null;
 
 
    public function setModel($id)
    {
        $this->_model=Region::model()->findByPk($id);
    }
 
    public function getModel()
    {
        if (!$this->_model)
        {
            if (isset($_GET['region']))
                $this->_model=Region::model()->findByAttributes(array('url_name'=> $_GET['region']));
            else
                $this->_model=Region::model()->find();
        }
        return $this->_model;
    }
 
    public function getId()
    {
        return "heelo";
    }
 
    public function getName()
    {
        return $this->model->name;
    }
}