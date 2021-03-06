<?php
namespace TSG\Surprise\Test\Integration\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Form\FormKey;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\Interception\PluginList;
use TSG\Surprise\Plugin\AddSurprise;

class AddSurpriseTest extends \Magento\TestFramework\TestCase\AbstractController
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
    /**
     * @var Bootstrap;
     */
    private $bootstrap;
    /**
     * @var FormKey
     */
    private $formKey;

    protected function setUp()
    {
        parent::setUp();
        $this->bootstrap = Bootstrap::getObjectManager();
        $this->cart = $this->bootstrap->create(Cart::class);
        $this->request = $this->bootstrap->create(Http::class);
    }
    /**
     * @magentoDataFixture createSimpleProduct
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testAfterAddProduct()
    {
        $moduleList = $this->moduleList;
        $productRepository = $this->bootstrap->create(ProductRepositoryInterface::class);
        $product = $productRepository->get('simple');
        $this->request->setParams(['surprise_product' => $product->getId()]);
        $this->addSurprise = $this->bootstrap
            ->create(AddSurprise::class, [
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
     * @magentoDataFixture createSimpleProductSurprise
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoAppArea frontend
     */
    public function testAddProduct()
    {
       /* $expectedProducts = array(
         0 => [

         ]
        );*/

        $this->formKey = $this->_objectManager->get(FormKey::class);
        $request = [
            'product' => 1,
            'qty' => 2,
            'surprise_product' => 2
        ];
        $this->getRequest()->setPostValue($request);
        $this->bootstrap->create(Cart::class)->addProduct($request['product']);
        $items = $this->cart->getQuote()->getAllItems();
        $this->assertEquals(2, count($items));
       /* foreach ($items as $item) {
            $this->assertEquals( , $item->getQty());
        }*/



        $item = $items[0];
        $this->assertEquals(2, $item->getQty());
        $this->assertEquals('simple', $item->getSku());
        $item = $items[1];
        $this->assertEquals(1, $item->getQty());
        $this->assertEquals(0, $item->getPrice());
        $this->assertEquals('surprise', $item->getSku());
    }

    public static function createSimpleProduct()
    {
        $product = Bootstrap::getObjectManager()->create(\TSG\Surprise\Model\Product::class);
        $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
            ->setId(1)
            ->setName('Simple')
            ->setSku('simple')
            ->setPrice(10)
            ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
            ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
            ->setWebsiteIds([1])
            ->setStockData(['qty' => 100, 'is_in_stock' => 1, 'manage_stock' => 1])
            ->save();
    }

    public static function createSimpleProductSurprise()
    {
        $product = Bootstrap::getObjectManager()->create(\TSG\Surprise\Model\Product::class);
        $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
            ->setId(2)
            ->setName('surprise')
            ->setSku('surprise')
            ->setPrice(10)
            ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
            ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
            ->setWebsiteIds([1])
            ->setStockData(['qty' => 100, 'is_in_stock' => 1, 'manage_stock' => 1])
            ->save();
    }
}
