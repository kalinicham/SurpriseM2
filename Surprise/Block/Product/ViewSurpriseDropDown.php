<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.05.19
 * Time: 12:21
 */

namespace TSG\Surprise\Block\Product;

use TSG\Surprise\Model\ProductFactory;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\Request\Http;
use Magento\Catalog\Model\Product\Visibility;

class ViewSurpriseDropDown extends \Magento\Catalog\Block\Product\AbstractProduct
{


    /**
     * @var \TSG\Surprise\Model\Product
     */
    private $productSurprise;

    /**
     * @var Collection
     */
    protected $_itemCollection;

    /**
     * @var Http
     */
    protected $request;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * @param \Magento\Framework\App\Request\Http
     * @param \TSG\Surprise\Model\ProductFactory
     * @param Context $context
     * @param array $data
     *
     */

    public function __construct(
        Http $request,
        ProductFactory $productSurprise,
        Visibility $catalogProductVisibility,
        Context $context,
        array $data = [])
    {
        $this->request = $request;
        $this->productSurprise = $productSurprise;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        parent::__construct($context, $data);
    }

    /**
     * Get id post value
     *
     * @return integer
     */

    public function getIdPost()
    {
        return $this->request->getParam('id');
    }

    /**
     * @return $this
     */
    protected function getSurpriseCollection()
    {
        $id = $this->getIdPost();

        /* @var $productSurprise \TSG\Surprise\Model\Product */
        $productSurprise = $this->productSurprise->create()->load($id);

        $this->_itemCollection = $productSurprise->getSurpriseProductCollection()->addAttributeToSelect(
            'required_options'
        )->setPositionOrder()->addStoreFilter();

        $this->_itemCollection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $this->_itemCollection->load();

        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        $this->_itemCollection->load();

       return $this;
    }


    /**
     * Before to html handler
     *
     * @return $this
     */
  /*  protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }*/



}