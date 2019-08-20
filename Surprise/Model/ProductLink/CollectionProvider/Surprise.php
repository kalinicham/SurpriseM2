<?php

namespace TSG\Surprise\Model\ProductLink\CollectionProvider;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductLink\CollectionProviderInterface;

class Surprise implements CollectionProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLinkedProducts(Product $product)
    {
        return $product->getSurpriseProducts();
    }
}
