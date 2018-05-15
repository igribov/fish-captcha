**How to install Fish Captcha**
Add git repository into you composer.json file and set "minimum-stability" : "dev".

```php
"repositories": [
    {
        "type": "composer",
        "url": "https://asset-packagist.org"
    },
    {
        "type": "vcs",
        "url": "https://github.com/igribov/fish-captcha.git"
    }
]
```
run in cmd line
```php
composer require phprad/fish-captcha
```

**How to use Fish Captcha**

Add fish-captcha stand-alone actions into your project controller
```php
public function actions()
{
    return [
        // ...
        'fish-captcha' => [
            'class' => 'phprad\fishcaptcha\FishCaptchaAction',
            'fixedVerifyCode' => YII_ENV_TEST ? 'som' : null,
        ],
    ];
}
```

Add fish captcha validator to your Model

```php
public function rules()
{
    return [
        ...
        ['verifyCode', \phprad\fishcaptcha\FishCaptchaValidator::class],
    ];
}
```

You can use fish captcha widget in your views. Use correct controller name (step 1).

```php

<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
// ...
<?= $form->field($model, 'verifyCode')->widget(\phprad\fishcaptcha\FishCaptcha::class, [
    'captchaAction' => 'site/fish-captcha'
]) ?>

```