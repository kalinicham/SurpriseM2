<?php




namespace TSG\Surprise\Model;

use TSG\Surprise\Model\Product\Link;

class Product Extends \Magento\Catalog\Model\Product
{
    const LINK_TYPE_SURPRISE = 7;

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
        $collection = $this->getLinkInstance()->setLinkTypeId(Link::LINK_TYPE_SURPRISE)->getProductCollection()->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }



}