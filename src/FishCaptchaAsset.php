<?php

namespace phprad\fishcaptcha;

use yii\web\AssetBundle;

class FishCaptchaAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/frontend/dist';

    public $js = [
        'fishcaptcha.js',
    ];

    public $css = [
        'fishcaptcha.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}