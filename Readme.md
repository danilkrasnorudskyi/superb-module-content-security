[![Latest Stable Version](https://poser.pugx.org/superb-code/module-content-security/v/stable)](https://packagist.org/packages/superb-code/module-content-security)
[![Total Downloads](https://poser.pugx.org/superb-code/module-content-security/downloads)](https://packagist.org/packages/superb-code/module-content-security)

### Install via composer (recommend)

Run the following command in Magento 2 root folder:

```
composer require superb-code/module-content-security
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

----

### Environment variables usage (app/etc/env.php)

1. `superb/content_security/escape_config` - escape config, see example below
2. `superb/content_security/escape_debug_log_enabled` - enable/disable debug log. Helps to identify used blocks

```
'superb' => [
    'content_security' => [
        'escape_debug_log_enabled' => true,
        'escape_config' => [
            'checkout_index_index' => [//full action name
                'enabled' => true,
                'allowed_tags' => [
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
                    'h6'
                ],
                'allowed_attributes' => [
                    'id',
                    'class',
                    'style',
                    'src',
                    'href'
                ]
            ],
            'footer_bottom_links' => [//static block identifier
                'enabled' => true,
                'allowed_tags' => [
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
                    'h6'
                ],
                'allowed_attributes' => [
                    'id',
                    'class',
                    'style',
                    'src',
                    'href'
                ]
            ]
        ]
    ]
]
```