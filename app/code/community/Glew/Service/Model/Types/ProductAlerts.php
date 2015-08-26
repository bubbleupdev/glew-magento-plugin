<?php

class Glew_Service_Model_Types_ProductAlerts
{
    public $alerts;
    private $pageNum;

    public function load($pageSize,$pageNum,$startDate = null,$endDate = null)
    {
        $config =  Mage::helper('glew')->getConfig();
        if($startDate && $endDate) {
            $condition = "add_date BETWEEN '" . date('Y-m-d 00:00:00', strtotime($startDate)) . "' AND '" . date('Y-m-d 23:59:59', strtotime($endDate)) . "'";
            $alerts = Mage::getModel('productalert/stock')->getCollection()
                ->addFilter('add_date', $condition, 'string');
        } else {
            $alerts = Mage::getModel('productalert/stock')->getCollection();
        }
        $this->pageNum = $pageNum;
        $alerts->setCurPage($pageNum);
        $alerts->setPageSize($pageSize);

        if($alerts->getLastPageNumber() < $pageNum){
            return $this;
        }

        foreach ($alerts as $alert){
            $model = Mage::getModel('glew/types_productAlert')->parse($alert);
            if ($model) {
                $this->alerts[] = $model;
            }
        }
        return $this;
    }

}
