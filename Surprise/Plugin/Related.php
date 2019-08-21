<?php

namespace TSG\Surprise\Plugin;

use Magento\Catalog\Model\Product as ProductMagento;
use TSG\Surprise\Model\ProductFactory;

class Related
{
    /**
     * @var ProductFactory
     */
    private $_product;

    /**
     * Related constructor.
     * @param ProductFactory $_product
     */
    public function __construct(ProductFactory $_product)
    {
        $this->_product = $_product;
    }

    /**
     * @param $provider
     * @param ProductMagento $product
     * @return array
     */
    public function beforeGetLinkedProducts($provider, $product)
    {
        $currentProduct = $this->_product->create()->load($product->getId());
        return [$currentProduct];
    }
}
