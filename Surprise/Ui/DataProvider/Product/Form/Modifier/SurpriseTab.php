<?php
namespace TSG\Surprise\Ui\DataProvider\Product\Form\Modifier;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Container;
use Magento\Catalog\Model\Locator\LocatorInterface;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Fieldset;

class SurpriseTab extends AbstractModifier
{

    const CUSTOM_TAB_INDEX = 'surprise_tab';

    public function modifyData(array $data)
    {
        return $data;
    }

    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->addCustomTab();
        return $this->meta;
    }

    protected function addCustomTab()
    {
        $this->meta = array_merge_recursive(
            $this->meta,
            [
                static::CUSTOM_TAB_INDEX => $this->getTabConfig(),
            ]
        );
    }

    protected function getTabConfig()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Fieldset::NAME,
                        'label' => __('Surprise Tab'),
                        'collapsible' => true,
                        'dataScope' => 'data.product',
//                        'provider' => static::FORM_NAME . '.product_form_data_source',
//                        'ns' => static::FORM_NAME,
                        'sortOrder' => '1',
                    ],
                ],
            ],
        ];
    }


}