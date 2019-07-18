<?php

namespace TSG\Surprise\Test\Integration\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Magento\Checkout\Model\Cart;
use Magento\Framework\App\Request\Http;
use \TSG\Surprise\Plugin\AddSurprise;

class AddSurpriseTest extends TestCase
{
    /**
     * @var Cart;
     */
    private $cart;
    /**
     * @var AddSurprise;
     */
    private $addSurprise;
    /**
     * @var Http;
     */
    private $request;



    protected function setUp()
    {
        $this->cart = Bootstrap::getObjectManager()->create(Cart::class);
        $this->request = Bootstrap::getObjectManager()->create(Http::class);
    }

    /**
     * @magentoDataFixture createSimpleProduct
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */

    public function testAfterAddProduct()
    {
        $productRepository = Bootstrap::getObjectManager()->create(ProductRepositoryInterface::class);
        $product = $productRepository->get('simple');

        $this->request->setParams(['surprise_product' => $product->getId()]);

        $this->addSurprise = Bootstrap::getObjectManager()
            ->create(AddSurprise::class,[
                'request' => $this->request
            ]);
        $this->addSurprise->afterAddProduct($this->cart);

        $items = $this->cart->getQuote()->getAllItems();
            $this->assertEquals(1, count($items));

        $item = $items[0];
            $this->assertEquals(1, $item->getQty());
            $this->assertEquals(0, $item->getPrice());
            $this->assertEquals(0, $item->getCustomPrice());
            $this->assertEquals($product->getId(), $item->getOptionByCode('info_buyRequest')->getProductId());
            $this->assertEquals('surprise', $item->getOptionByCode('product_type')->getValue());
    }


    /**
     * @magentoDataFixture createSimpleProduct
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */

    public function testAddProduct ()
    {
        $productRepository = Bootstrap::getObjectManager()->create(ProductRepositoryInterface::class);
        $product = $productRepository->get('simple');
        $this->request->setParams(['surprise_product' => $product->getId()]);

        $cart = Bootstrap::getObjectManager()->create(Cart::class);

        $cart->addProduct(3000,2);

        $a = 1;
    }


    public static function createSimpleProduct()
    {

        $product = Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\Product::class);
        $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
            ->setId(3000)
            ->setName('Simple')
            ->setSku('simple')
            ->setPrice(10)
            ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
            ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
            ->setWebsiteIds([1])
            ->setStockData(['qty' => 100, 'is_in_stock' => 1, 'manage_stock' => 1])
            ->save();
    }
}