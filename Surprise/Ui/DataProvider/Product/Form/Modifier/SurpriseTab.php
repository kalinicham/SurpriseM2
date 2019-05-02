<?php
namespace TSG\Surprise\Ui\DataProvider\Product\Form\Modifier;




use Magento\Ui\Component\Container;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductLinkInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Ui\Component\Modal;

use Magento\Framework\UrlInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Ui\Component\DynamicRows;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;
use Magento\Framework\Phrase;
use Magento\Catalog\Api\ProductLinkRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;



class SurpriseTab extends AbstractModifier
{

    const DATA_SCOPE = '';
    const DATA_SCOPE_SURPRICE = 'surprisetab';
    const GROUP_SURPRISETAB = 'surprisetab';

    private static $previousGroup = 'content';
    private static $sortOrder = 5;

    protected $locator;
    protected $urlBuilder;
    protected $productLinkRepository;
    protected $productRepository;
    protected $imageHelper;
    protected $status;
    protected $attributeSetRepository;
    protected $scopeName;
    protected $scopePrefix;
    protected $CustomerInterface;
    private $collectionFactory;

    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
        ProductLinkRepositoryInterface $productLinkRepository,
        ProductRepositoryInterface $productRepository,
        ImageHelper $imageHelper,
        Status $status,
        AttributeSetRepositoryInterface $attributeSetRepository,
        CustomerInterface $CustomerInterface,
        CollectionFactory $collectionFactory,
        $scopeName = '',
        $scopePrefix = ''
    ) {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
        $this->CustomerInterface = $CustomerInterface;
        $this->collectionFactory = $collectionFactory;
        $this->productLinkRepository = $productLinkRepository;
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
        $this->status = $status;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->scopeName = $scopeName;
        $this->scopePrefix = $scopePrefix;
    }



    public function modifyData(array $data)
    {


        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->locator->getProduct();
        $productId = $product->getId();

        if (!$productId) {
            return $data;
        }

       // $priceModifier = $this->getPriceModifier();
        /**
         * Set field name for modifier
         */
       // $priceModifier->setData('name', 'price');

        foreach ($this->getDataScopes() as $dataScope) {
//            $data[$productId]['links'][$dataScope] = [];
//            foreach ($this->productLinkRepository->getList($product) as $linkItem) {
//                if ($linkItem->getLinkType() !== $dataScope) {
//                    continue;
//                }
//
//                /** @var \Magento\Catalog\Model\Product $linkedProduct */
//                $linkedProduct = $this->productRepository->get(
//                    $linkItem->getLinkedProductSku(),
//                    false,
//                    $this->locator->getStore()->getId()
//                );
//                $data[$productId]['links'][$dataScope][] = $this->fillData($linkedProduct, $linkItem);
//            }
//            if (!empty($data[$productId]['links'][$dataScope])) {
//                $dataMap = $priceModifier->prepareDataSource([
//                    'data' => [
//                        'items' => $data[$productId]['links'][$dataScope]
//                    ]
//                ]);
//                $data[$productId]['links'][$dataScope] = $dataMap['data']['items'];
//            }
        }

        $data[$productId][self::DATA_SOURCE_DEFAULT]['current_product_id'] = $productId;
        $data[$productId][self::DATA_SOURCE_DEFAULT]['current_store_id'] = $this->locator->getStore()->getId();

        return $data;

    }

    protected function getDataScopes()
    {
        return [
            static::DATA_SCOPE_SURPRICE,
        ];
    }

    protected function fillData($linkItem)
    {
        return [
            'id' => $linkItem->getId(),
            'name' => $linkItem->getName(),
        ];
    }

    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->meta = array_replace_recursive(
        $this->meta,
            [
                static::GROUP_SURPRISETAB => [
                    'children' => [
                        $this->scopePrefix . static::DATA_SCOPE_SURPRICE => $this->getCustomerFieldset(),
                    ],
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Customer_Surprise'),
                                'collapsible' => true,
                                'componentType' => Fieldset::NAME,
                                'dataScope' => static::DATA_SCOPE,
                                'sortOrder' =>
                                    $this->getNextGroupSortOrder(
                                        $this->meta,
                                        self::$previousGroup,
                                        self::$sortOrder
                                    ),
                            ],
                        ],

                    ],
                ],
            ]
        );
        return $this->meta;
    }



    protected function getCustomerFieldset()
    {
        $content = __(
            'This products are shown to customers in addition to the item the customer is looking at.'
        );

        return [
            'children' => [
              /*  'button_set' => $this->getButtonSet(
                    $content,
                    __('Add Specific Customer'),
                    $this->scopePrefix . static::DATA_SCOPE_CUSTOMER
                ),
                'modal' => $this->getGenericModal(
                    __('Add Specific Customer'),
                    $this->scopePrefix . static::DATA_SCOPE_CUSTOMER
                ),*/
               static::DATA_SCOPE_SURPRICE => $this->getGrid($this->scopePrefix . static::DATA_SCOPE_SURPRICE),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Добавити сюрпризи'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 10,
                    ],
                ],
            ]
        ];
    }


    /**
    * Retrieve button set
    *
    * @param Phrase $content
    * @param Phrase $buttonTitle
    * @param string $scope
    * @return array
    */
    protected function getButtonSet(Phrase $content, Phrase $buttonTitle, $scope)
    {
        $modalTarget = $this->scopeName . '.' . static::GROUP_SURPRISETAB . '.' . $scope . '.modal';

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => false,
                        'content' => $content,
                        'template' => 'ui/form/components/complex',
                    ],
                ],
            ],
            'children' => [
                'button_' . $scope => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'container',
                                'componentType' => 'container',
                                'component' => 'Magento_Ui/js/form/components/button',
                                'actions' => [
                                    [
                                        'targetName' => $modalTarget,
                                        'actionName' => 'toggleModal',
                                    ],
                                    [
                                        'targetName' => $modalTarget . '.' . $scope . '_customer_listing',
                                        'actionName' => 'render',
                                    ]
                                ],
                                'title' => $buttonTitle,
                                'provider' => null,
                            ],
                        ],
                    ],

                ],
            ],
        ];
    }


    /**
     * Prepares config for modal slide-out panel
     *
     * @param Phrase $title
     * @param string $scope
     * @return array
     */
    protected function getGenericModal(Phrase $title, $scope)
    {
        $listingTarget = $scope . '_customer_listing';


        $modal = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Modal::NAME,
                        'dataScope' => '',
                        'options' => [
                            'title' => $title,
                            'buttons' => [
                                [
                                    'text' => __('Cancel'),
                                    'actions' => [
                                        'closeModal'
                                    ]
                                ],
                                [
                                    'text' => __('Add Selected Customers'),
                                    'class' => 'action-primary',
                                    'actions' => [
                                        [
                                            'targetName' => 'index = ' . $listingTarget,
                                            'actionName' => 'save'
                                        ],
                                        'closeModal'
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'children' => [
                $listingTarget => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => false,
                                'componentType' => 'insertListing',
                                'dataScope' => $listingTarget,
                                'externalProvider' => $listingTarget . '.' . $listingTarget . '_data_source',
                                'selectionsProvider' => $listingTarget . '.' . $listingTarget . '.customer_columns.ids',
                                'ns' => $listingTarget,
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => true,
                                'dataLinks' => [
                                    'imports' => false,
                                    'exports' => true
                                ],
                                'behaviourType' => 'simple',
                                'externalFilterMode' => true,
                                'imports' => [
                                    'productId' => '${ $.provider }:data.product.current_product_id',
                                    'storeId' => '${ $.provider }:data.product.current_store_id',
                                ],
                                'exports' => [
                                    'productId' => '${ $.externalProvider }:params.current_product_id',
                                    'storeId' => '${ $.externalProvider }:params.current_store_id',
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $modal;
    }

    protected function getGrid($scope)
    {
        $dataProvider = $scope . '_surprise_listing';
        $a = 'stop line';
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__field-wide',
                        'componentType' => DynamicRows::NAME,
                        'label' => __('Таблица'),
                        'columnsHeader' => true,
                        'columnsHeaderAfterRender' => true,
                        'renderDefaultRecord' => false,
                        'template' => 'ui/dynamic-rows/templates/grid',
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows-grid',
                        'addButton' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => 'data.links',
                        'deleteButtonLabel' => __('Remove'),
                        'dataProvider' => $dataProvider,
                        'map' => [
                            'id' => 'entity_id',
                            'name' => 'name',
                        ],
                        'links' => [
                            'insertData' => '${ $.provider }:${ $.dataProvider }'
                        ],
                        'sortOrder' => 2,
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => 'container',
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => $this->fillMeta(),
                ],
            ],
        ];
    }

    /**
     * Retrieve meta column
     *
     * @return array
     */
    protected function fillMeta()
    {
        return [
            'id' => $this->getTextColumn('id', false, __('ID'), 0),
            'name' => $this->getTextColumn('name', false, __('Name'), 20),
            'actionDelete' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'additionalClasses' => 'data-grid-actions-cell',
                            'componentType' => 'actionDelete',
                            'dataType' => Text::NAME,
                            'label' => __('Actions'),
                            'sortOrder' => 70,
                            'fit' => true,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Retrieve text column structure
     *
     * @param string $dataScope
     * @param bool $fit
     * @param Phrase $label
     * @param int $sortOrder
     * @return array
     */
    protected function getTextColumn($dataScope, $fit, Phrase $label, $sortOrder)
    {
        $column = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'elementTmpl' => 'ui/dynamic-rows/cells/text',
                        'component' => 'Magento_Ui/js/form/element/text',
                        'dataType' => Text::NAME,
                        'dataScope' => $dataScope,
                        'fit' => $fit,
                        'label' => $label,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];

        return $column;
    }

}


