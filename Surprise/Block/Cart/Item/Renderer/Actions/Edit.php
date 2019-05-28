<?php
/**
 * Created by PhpStorm.
 * User: kalinich
 * Date: 5/26/19
 * Time: 3:19 PM
 */

namespace TSG\Surprise\Block\Cart\Item\Renderer\Actions;

use Magento\Checkout\Block\Cart\Item\Renderer\Actions\Generic;
use Magento\Framework\View\Element\Template;
use Magento\Quote\Model\Quote\Item\AbstractItem as QuoteItem;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;



class Edit extends Generic
{


    /**
     * @var QuoteItem
     */
    protected $item;

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
     * Returns current quote item
     *
     * @return QuoteItem
     * @codeCoverageIgnore
     */

    public function getItem()
    {
        $this->isSurprise();
        return $this->item;
    }

    /**
     * Set current quote item
     *
     * @param QuoteItem $item
     * @return $this
     * @codeCoverageIgnore
     */
    public function setItem(QuoteItem $item)
    {
        $this->item = $item;
        return $this;
    }

    public function isSurprise()
    {
        foreach ($this->item->getOptions() as $option){
            if ($option->getCode() == 'info_buyRequest') {
                $param = $this->serializer->unserialize($option->getValue());
                if (array_key_exists('is_surprise', $param)) {
                    return $this->item->setData('surprise',true);
                }
            }
        }
        return $this->item->setData('surprise',false);
    }

}