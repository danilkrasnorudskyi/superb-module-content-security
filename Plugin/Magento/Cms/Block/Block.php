<?php

namespace Superb\ContentSecurity\Plugin\Magento\Cms\Block;

use Psr\Log\LoggerInterface;
use Magento\Cms\Block\Block as Subject;
use Superb\ContentSecurity\Helper\Config;
use Superb\ContentSecurity\Helper\Content;
use Magento\Framework\App\RequestInterface;

class Block
{
    protected $content;
    protected $config;
    protected $request;
    protected $logger;

    public function __construct(
        Config $config,
        Content $content,
        RequestInterface $request,
        LoggerInterface $logger
    ) {
        $this->content = $content;
        $this->config = $config;
        $this->request = $request;
        $this->logger = $logger;
    }

    public function afterToHtml(
        Subject $block,
        $html
    ) {
        $getConfigBy = null;
        if ($this->config->isEscapeEnabled($this->request->getFullActionName())) {
            if ($this->config->isEscapeEnabled($block->getBlockId())) {
                $getConfigBy = $block->getBlockId();
            } elseif ($this->config->isEscapeEnabled($block->getIdentifier())) {
                $getConfigBy = $block->getIdentifier();
            } else {
                $getConfigBy = $this->request->getFullActionName();
            }
            if ($getConfigBy) {
                $html = $this->content->filterContent(
                    $html,
                    $this->config->getPageAllowedTags($getConfigBy),
                    $this->config->getPageAllowedAttributes($getConfigBy)
                );
            }
        }
        if ($this->config->isDebugLogEnabled()) {
            $this->logger->info(json_encode([
                'full_action_name' => $this->request->getFullActionName(),
                'block_id' => $block->getBlockId() ?? $block->getIdentifier(),
                'allowed_tags' => $getConfigBy ? $this->config->getPageAllowedTags($getConfigBy) : null,
                'allowed_attributes' => $getConfigBy ? $this->config->getPageAllowedAttributes($getConfigBy) : null,
            ]));
        }
        return $html;
    }
}
