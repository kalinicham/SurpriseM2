<?php


namespace TSG\Surprise\Plugin;

use Magento\Framework\App\Request\Http;

class AddSurprise
{
    private $request;

    public function __construct(
        Http $request
    ) {
        $this->request = $request;
    }

    public function beforeExecute()
    {
        $params = $this->getRequest()->getParams();
        $a =1;
    }

}