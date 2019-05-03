<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace TSG\Surprise\Ui\DataProvider\Product\Surprise;

use Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider;

/**
 * Class CrossSellDataProvider
 *
 * @api
 * @since 101.0.0
 */
class SurpriseDataProvider extends ProductDataProvider
{
    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    protected function getLinkType()
    {
        return 'surprise';
    }
}
