<?php

namespace Superb\ContentSecurity\Helper;

use Magento\Framework\App\DeploymentConfig;

class Config
{
    protected $defaultAllowedTags = [
        'div',
        'p',
        'a',
        'ul',
        'li',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
    ];

    protected $defaultAllowedAttributes = [
        'id',
        'class',
        'style',
        'src',
        'href'
    ];

    const ESCAPE_CONFIG = 'superb/content_security/escape_config';
    const ESCAPE_DEBUG_LOG_ENABLED = 'superb/content_security/escape_debug_log_enabled';

    protected $deploymentConfig;
    protected $escapeConfig;

    public function __construct(
        DeploymentConfig $deploymentConfig
    ) {
        $this->deploymentConfig = $deploymentConfig;
    }

    /**
     * Determines if the debug log is enabled.
     *
     * @return bool True if the debug log is enabled, false otherwise.
     */
    public function isDebugLogEnabled()
    {
        return $this->deploymentConfig->get(self::ESCAPE_DEBUG_LOG_ENABLED);
    }

    /**
     * Checks if escaping is enabled for the specified page.
     *
     * @param string $page The name of the page to check.
     * @return bool Returns true if escaping is enabled for the given page, otherwise false.
     */
    public function isEscapeEnabled($page)
    {
        return $this->getEscapeConfig()[$page]['enabled'] ?? false;
    }

    /**
     * Retrieves the allowed tags configuration for a specific page.
     *
     * @param string $page The name or identifier of the page to get the allowed tags for.
     * @return array The list of allowed tags for the specified page. Returns the default allowed tags if no specific configuration exists.
     */
    public function getPageAllowedTags($page)
    {
        return $this->getEscapeConfig()[$page]['allowed_tags'] ?? $this->defaultAllowedTags;
    }

    /**
     * Retrieves the allowed attributes for a specific page.
     *
     * @param string $page The name of the page for which to get the allowed attributes.
     * @return array The list of allowed attributes for the specified page.
     */
    public function getPageAllowedAttributes($page)
    {
        return $this->getEscapeConfig()[$page]['allowed_attributes'] ?? $this->defaultAllowedAttributes;
    }

    /**
     * Retrieves the escape configuration settings.
     *
     * The method initializes and returns the configuration for pages, including
     * details such as whether escaping is enabled, allowed tags, and allowed attributes.
     *
     * @return array The escape configuration settings mapped by page.
     */
    protected function getEscapeConfig()
    {
        if (null === $this->escapeConfig) {
            $this->escapeConfig = [];
            $arr = $this->deploymentConfig->get(self::ESCAPE_CONFIG, []);
            if (!is_array($arr)) {
                $arr = [];
            }
            foreach ($arr as $page => $value) {
                $this->escapeConfig[$page] = [
                    'enabled' => $value['enabled'] ?? false,
                    'allowed_tags' => $this->getConfigValue(
                        $value['allowed_tags'] ?? [],
                        $this->defaultAllowedTags
                    ),
                    'allowed_attributes' => $this->getConfigValue(
                        $value['allowed_attributes'] ?? [],
                        $this->defaultAllowedAttributes
                    ),
                ];


            }
        }
        return $this->escapeConfig;
    }

    /**
     * Retrieves the configuration value based on the provided environment value.
     *
     * @param array $envValue The environment value to evaluate. Expected to be an array of strings or null/invalid.
     * @param array $defaultValue The default value to return if the environment value is invalid or empty.
     * @return array The result array containing valid string values from the environment value, or the default value if invalid or empty.
     */
    protected function getConfigValue($envValue, $defaultValue)
    {
        if (!$envValue || !is_array($envValue)) {
            return $defaultValue;
        }
        $result = [];
        foreach ($envValue as $value) {
            if (is_string($value)) {
                $result[] = $value;
            }
        }
        if (!$result) {
            return $defaultValue;
        }
        return $result;
    }
}