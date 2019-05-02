<?php

namespace TSG\Surprise\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Fieldset;

class SurpriseTab extends AbstractModifier {

    const FIELDSET_NAME = 'surprise_tab';
    const FILED_NAME = 'surprise';

    protected $_backendUrl;
    protected $_productloader;
    protected $_modelCustomgridFactory;

     /**
     * @var \Magento\Catalog\Model\Locator\LocatorInterface
     */
     protected $locator;

     /**
      * @var ArrayManager
      */
     protected $arrayManager;

     /**
      * @var UrlInterface
      */
     protected $urlBuilder;

     /**
      * @var array
      */
     protected $meta = [];

     /**
      * @param LocatorInterface $locator
      * @param ArrayManager $arrayManager
      * @param UrlInterface $urlBuilder
      */

     public function __construct(
         LocatorInterface $locator,
         ArrayManager $arrayManager,
         UrlInterface $urlBuilder,
         \Magento\Catalog\Model\ProductFactory $_productloader,
         \Magento\Backend\Model\UrlInterface $backendUrl
     ) {
         $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
        $this->_productloader = $_productloader;
        $this->_backendUrl = $backendUrl;
        }

    public function modifyData(array $data)
    {
        return $data;
    }

    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->addSurpriseTab();
        return $this->meta;
    }

    protected function addSurpriseTab()
    {
        $this->meta = array_merge_recursive(
            $this->meta,
            [
                static::FIELDSET_NAME => $this->getTabConfig(),
            ]
        );
    }

    protected function getTabConfig()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Surprise Grid Tab'),
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                   //    'provider' => static::FORM_NAME . '.product_form_data_source',
                   //     'ns' => static::FORM_NAME,
                        'collapsible' => true,
                        'sortOrder' => 10,
                    ],
                ],
            ],
            'children' => [
                static::FILED_NAME => [
                    'arguments' => [
                        'data' => [


                            /*'config' => [
                                'label' => __('Table'),
                                'componentType' => 'insertListing',
                                'dataScope' => 'surprise_grid_listing',
                                'ns' => 'surprise_grid_listing',
                            ],*/

                            'config' => [
//                                'autoRender' => true,
                                'componentType' => 'insertListing',
                                'dataScope' => 'surprise_grid_listing',
                                'externalProvider' => 'surprise_grid_listing.surprise_grid_listing_data_source',
                                'selectionsProvider' => 'surprise_grid_listing.surprise_grid_listing.product_columns.ids',
                                'ns' => 'surprise_grid_listing',
//                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
//                                'realTimeLink' => false,
//                                'behaviourType' => 'simple',
//                                'externalFilterMode' => true,
//                                'imports' => [
//                                    'productId' => '${ $.provider }:data.product.current_product_id'
//                                ],
//                                'exports' => [
//                                    'productId' => '${ $.externalProvider }:params.current_product_id'
//                                ],

                            ],



                        ],
                    ],
                    'children' => [],
                ],
            ],
        ];
    }

}