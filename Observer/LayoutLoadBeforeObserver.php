<?php

namespace Clerk\Clerk\Observer;

use Clerk\Clerk\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Layout as LayoutAlias;
use Magento\Store\Model\ScopeInterface;

class LayoutLoadBeforeObserver implements ObserverInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * LayoutGenerateBlocksAfterObserver constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var LayoutAlias $layout */
        $layout = $observer->getLayout();
        $actionName = $observer->getFullActionName();

        //Add custom layout handle if clerk search is enabled
        if ($this->isClerkSearchEnabled() && $actionName === 'catalogsearch_result_index') {
            $layout->getUpdate()->addHandle('clerk_result_index');
        }
    }

    /**
     * Determine if Clerk search is enabled
     *
     * @return bool
     */
    private function isClerkSearchEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(Config::XML_PATH_SEARCH_ENABLED, ScopeInterface::SCOPE_STORE);
    }
}
