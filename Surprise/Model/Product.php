<?php

namespace TSG\Surprise\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\ObjectManager;
use TSG\Surprise\Model\Product\Link;

class Product extends \Magento\Catalog\Model\Product implements ProductInterface
{
    /**
     * Retrieve array of related products
     *
     * @return array
     */
    public function getSurpriseProducts(): array
    {
        if (!$this->hasSurpriseProducts()) {
            $products = [];
            $collection = $this->getSurpriseProductCollection();
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $this->setSurpriseProducts($products);
        }
        return $this->getData('surprise_products');
    }

    /**
     * Retrieve related products identifiers
     *
     * @return array
     */
    public function getSurpriseProductIds()
    {
        if (!$this->hasSurpriseProductIds()) {
            $ids = [];
            foreach ($this->getSurpriseProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setSurpriseProductIds($ids);
        }
        return [$this->getData('surprise_products_ids')];
    }

    /**
     * Retrieve collection related product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getSurpriseProductCollection()
    {
        $collection = $this->getLinkInstance()->useSurpriseLinks()->getProductCollection()->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }

    public function getLinkInstance()
    {
        return ObjectManager::getInstance()->create(Link::class);
    }
}
