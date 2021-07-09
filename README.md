Yii2 cas module
=================

Installation
------------

```
php composer.phar require --prefer-dist sindll/yii2-cas:"^1.0"
```

or add

```json
"sindll/yii2-cas": "^1.0"
```

Configuration
-------------

```php
return [
    //....
    'bootstrap' => [
        //...
        'cas',
    ],
    'modules' => [
        //...
        'cas' => [
            'class' => 'sindll\cas\Module',
        ],
    ],
    'components' => [
        //...
        'user' => [
            //...
            'loginUrl' => ['cas/passport/login'],
        ],
    ]
];
```