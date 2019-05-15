<?php
namespace TSG\Surprise\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class SurpriseStat extends AbstractModel implements SurpriseStatInterface, IdentityInterface
{

    const CACHE_TAG = 'tsg_surprise_product';

        protected function _construct()
        {

          $this->_init(\TSG\Surprise\Model\ResourceModel\SurpriseStatResource::class);

        }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }


    public function getProductId($index = null)
    {
    return parent::getData('product_id', $index);
        // TODO: Implement getProductId() method.
    }

    public function getLinkedProductId($index = null)
    {
        return $this->getData( 'linked_product_id',$index);
        // TODO: Implement getLinkedProduct() method.
    }

    public function getCountSold()
    {
        return $this->getData('count_sold');
        // TODO: Implement getCountSold() method.
    }

    public function setProductId($value = null)
    {
        return parent::setData('product_id',$value);
    }

    public function setLinkedProductId($value = null)
    {
        return parent::setData('linked_product_id',$value);
    }

    public function setCountSold()
    {
        // TODO: Implement setCountSold() method.
    }

}
