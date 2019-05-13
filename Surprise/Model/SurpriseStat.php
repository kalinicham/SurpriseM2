<?php
namespace TSG\Surprise\Model;

use Magento\Framework\Model\AbstractModel;

class SurpriseStat extends AbstractModel
{
        protected function _construct()
        {

          $this->_init(\TSG\Surprise\Model\ResourceModel\SurpriseStatResource::class);

        }
}