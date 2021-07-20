<?php 
namespace PriorNotify\UpwardConnector\Model\ResourceModel\DataExample;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{
	public function _construct(){
		$this->_init("PriorNotify\UpwardConnector\Model\DataExample","PriorNotify\UpwardConnector\Model\ResourceModel\DataExample");
	}
}
?>