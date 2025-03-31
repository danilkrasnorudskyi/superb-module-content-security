<?php

namespace Superb\ContentSecurity\Helper;

class Content
{
    /**
     * Filters the provided content by removing disallowed tags and attributes.
     *
     * @param string $content The HTML content to be filtered.
     * @param array $allowedTags An array of allowed HTML tags.
     * @param array $allowedAttributes An array of allowed attributes within the HTML tags.
     * @return string The filtered HTML content.
     */
    public function filterContent($content, array $allowedTags = [], array $allowedAttributes = [])
    {
        if ($content) {
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->loadHTML(
                mb_encode_numericentity($content, [0x80, 0x10FFFF, 0, 0x1FFFFF], 'UTF-8'),
                LIBXML_NOENT | LIBXML_HTML_NODEFDTD
            );
            foreach ((new \DOMXPath($dom))->query('//@*') as $node) {
                if (!in_array($node->nodeName, $allowedAttributes)) {
                    $node->parentNode->removeAttribute($node->nodeName);
                }
            }
            $content = $dom->saveHTML();
            $content = strip_tags($content, '<' . implode('><', $allowedTags) . '>');
        }
        return $content;
    }
}
