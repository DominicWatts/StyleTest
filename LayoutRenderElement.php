<?php

namespace Xigen\DeliveryComment\Observer\Frontend\Core;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\TemplateFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class LayoutRenderElement
 * @package Xigen\DeliveryComment\Observer\Frontend\Core
 * @author someone
 */
class LayoutRenderElement implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var TemplateFactory
     */
    protected $templateFactory;

    /**
     * LayoutRenderElement constructor.
     * @param TemplateFactory $templateFactory
     */
    public function __construct(
        TemplateFactory $templateFactory
    ) {
        $this->templateFactory = $templateFactory;
    }

    /**
     * Execute observer
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        if ($observer->getElementName() == 'sales.order.info') {
            $orderShippingViewBlock = $observer->getLayout()->getBlock($observer->getElementName());
            $order = $orderShippingViewBlock->getOrder();
            $deliveryCommentBlock = $this->templateFactory->create();
            $deliveryCommentBlock->setDeliveryComment($order->getDeliveryComment());
            $deliveryCommentBlock->setTemplate('Xigen_DeliveryComment::order_info_shipping_info.phtml');
            $html = $observer->getTransport()->getOutput() . $deliveryCommentBlock->toHtml();
            $observer->getTransport()->setOutput($html);
        }
        return $this;
    }
}
