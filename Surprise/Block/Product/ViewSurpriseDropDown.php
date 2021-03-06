<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.05.19
 * Time: 12:21
 */

namespace TSG\Surprise\Block\Product;

use TSG\Surprise\Model\Product;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Request\Http;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;


class ViewSurpriseDropDown extends Template
{

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var \TSG\Surprise\Model\Product
     */
    protected $productSurprise;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $_itemCollection;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\ProductFactory
     */
    protected $productResource;



    protected $item;


    protected $serializer;

    /**
     * @param Http $request
     * @param Product $productSurprise
     * @param Visibility $_catalogProductVisibility
     * @param ProductFactory $productResource
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Http $request,
        Product $productSurprise,
        Visibility $_catalogProductVisibility,
        ProductFactory $productResource,
        Context $context,
        Session $item,
        Json $serializer = null,
        array $data = []
    ) {
        $this->request = $request;
        $this->productSurprise = $productSurprise;
        $this->_catalogProductVisibility = $_catalogProductVisibility;
        $this->productResource = $productResource;
        $this->item = $item;
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
        parent::__construct($context, $data);
    }

    /**
     *
     * Get surprise product items collection
     *
     * @return object
     */

    public function getSurpriseCollection()
    {
        $id = $this->request->getParam('id');

        /* @var $productSurprise \TSG\Surprise\Model\Product */
        $this->productResource->create()->load($this->productSurprise,$id);

        $productSurprise = $this->productSurprise;

        $this->_itemCollection = $productSurprise->getSurpriseProductCollection()
            ->addAttributeToSelect('*')
            ->setPositionOrder()
            ->addStoreFilter();

        $this->_itemCollection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $this->_itemCollection->load();
        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        $this->_itemCollection->load();

        return $this->_itemCollection;
    }

    public function isVisible(): bool
    {
        $quote = $this->item->getQuote()->getAllItems();

        foreach ($quote as $item){
          foreach ($item->getOptionsByCode() as $option) {
            if ($option->getCode() == 'info_buyRequest') {
                $param = $this->serializer->unserialize($option->getValue());
                if (array_key_exists('is_surprise', $param)) {
                    return false;
                }
            }
          }
        }

        return true;
    }
}