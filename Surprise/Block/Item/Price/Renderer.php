<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.05.19
 * Time: 15:42
 */

namespace TSG\Surprise\Block\Item\Price;


use Magento\Framework\View\Element\Template;
use Magento\Quote\Model\Quote\Item\AbstractItem as QuoteItem;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;

class Renderer extends Template
{

    /**
     * @var QuoteItem
     */
    protected $item;

    /**
     * @var string|int|null
     */
    protected $storeId = null;


    protected $serializer;

    public function __construct(
        Template\Context $context,
        array $data = [],
        \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
        parent::__construct($context, $data);
    }


    /**
     * Set item for render
     *
     * @param QuoteItem
     * @return $this
     */



    public function setItem($item)
    {
        $this->item = $item;
        $this->storeId = $item->getStoreId();
        return $this;
    }

    public function getItem()
    {
        $this->isSurprise();
        return $this->item;
    }

    public function isSurprise()
    {
        foreach ($this->item->getOptions() as $option){
            if ($option->getCode() == 'info_buyRequest') {
                $param = $this->serializer->unserialize($option->getValue());
                if (array_key_exists('is_surprise', $param)) {
              /*      $this->item->setData('price',0);
                    $this->item->setData('base_price',0);
                    $this->item->setData('row_total',0);
                    $this->item->setData('base_row_total',0);
                    $this->item->setData('original_price',0);
                    $this->item->setData('calculation_price',0);*/
                    return $this->item->setData('surprise',true);
                }
            }
        }
        return $this->item->setData('surprise',false);
    }

}