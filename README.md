**What is Fish Captcha ?**

![alt text](./preview.png?raw=true)

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

You can use fish captcha widget in your views. Use correct controller name equals controller with fish-captcha action (step 1).

```php

<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
// ...
<?= $form->field($model, 'verifyCode')->widget(\phprad\fishcaptcha\FishCaptcha::class, [
    'captchaAction' => 'site/fish-captcha'
]) ?>

```

**How rebuild frontend**

install dependencies

``` cd ./frontend ```
``` npm i ```

rebuild frontend

``` npm run build ```

If you want to dev and debug fish captcha with yii2 :

- set `assetManager['forceCopy']` to `true` in config;
```
'assetManager' => [
    'forceCopy' => true
]
```
- run this command

``` npm run watch ```

Every changes in frontend/src will call rebuild process.