<?php


namespace TSG\Surprise\Plugin;

use Magento\Framework\App\Request\Http;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\DataObject\Factory as ObjectFactory;
use Magento\Framework\DataObject;
use Magento\Checkout\Model\Cart\CartInterface;
use Magento\Quote\Model\Quote;
use Magento\Checkout\Model\Cart;


class AddSurprise
{
    /**
     * @var Http
     */

    private $request;

    /**
     * @var ProductRepositoryInterface
     */

    protected $productRepository;

    /**
     * @var ObjectFactory
     */

    protected $objectFactory;

    /**
     * AddSurprise constructor.
     * @param Http $request
     * @param ProductRepositoryInterface $productRepository
     * @param ObjectFactory $objectFactory
     */

    public function __construct(
        Http $request,
        ProductRepositoryInterface $productRepository,
        ObjectFactory $objectFactory
    ) {
        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->objectFactory = $objectFactory;
    }

    /**
     * Add surprise product to shopping cart (quote)
     *
     * @param $result \Magento\Checkout\Model\Cart
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public function afterAddProduct($result)
    {
        $params = $this->request->getParams();
        if (array_key_exists("surprise_product", $params)) {
            $surpriseProduct = $this->productRepository->getById($params['surprise_product']);
            $request = $this->objectFactory->create([
                 'qty' => 1,
                 'price' => 0,
                 'is_surprise' => 1,
             ]);
            $item = $result->getQuote()->addProduct($surpriseProduct,$request);
            $item->setPrice(0);
            $item->setCustomPrice(0);
            $item->setOriginalCustomPrice(0);
            $item->getProduct()->setIsSuperMode(true);
         }
    }
}