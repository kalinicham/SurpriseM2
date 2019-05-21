<?php


namespace TSG\Surprise\Plugin;

use Magento\Framework\App\Request\Http;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Api\ProductRepositoryInterface;

class AddSurprise
{
    private $request;

    protected $productRepository;

    public function __construct(
        Http $request,
        Cart $cart,
        ProductRepositoryInterface $productRepository

    ) {
        $this->request = $request;
        $this->cart = $cart;
        $this->productRepository = $productRepository;
    }

    public function afterExecute()
    {
        $idsSurprise = $this->request->getParams()['surprise_product'];
         if (!empty($idsSurprise)) {
            // $surpriseProduct = $this->productRepository->getById($idsSurprise);
             $params = [
                 'price' => 0,
                 'is_surprise' => 1,
             ];
             $this->cart->addProduct($idsSurprise, $params);
             $this->cart->save();
         }
    }
}