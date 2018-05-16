<?php

namespace phprad\fishcaptcha;

use phprad\fishcaptcha\FishCaptchaAction;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;


class FishCaptcha extends InputWidget
{
    /**
     * @var string
     */
    public $captchaAction = 'site/fish-captcha';
    /**
     * @var array
     */
    public $imageOptions = [];
    /**
     * @var string
     */
    public $template = '<div id="{id}"></div>{input}';
    /**
     * @var array
     */
    public $options = ['class' => 'form-control'];


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        if (!isset($this->imageOptions['id'])) {
            $this->imageOptions['id'] = $this->options['id'] . '-fish-captcha';
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->registerClientScript();
        $input = Html::activeInput('hidden', $this->model, $this->attribute, $this->options);
        $route = $this->captchaAction;
        if (is_array($route)) {
            $route['v'] = uniqid('', true);
        } else {
            $route = [$route, 'v' => uniqid('', true)];
        }

        echo strtr($this->template, [
            '{input}' => $input,
            '{id}' => $this->imageOptions['id'],
        ]);
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $options = $this->getClientOptions();
        $options = empty($options) ? '' : Json::htmlEncode($options);
        $id = $this->imageOptions['id'];
        $view = $this->getView();
        FishCaptchaAsset::register($view);
        $view->registerJs("window.fishCaptcha('#{$id}', $options);");
    }

    /**
     * @return array
     */
    protected function getClientOptions()
    {
        $route = $this->captchaAction;
        if (is_array($route)) {
            $route[FishCaptchaAction::REFRESH_GET_VAR] = 1;
        } else {
            $route = [$route, FishCaptchaAction::REFRESH_GET_VAR => 1];
        }

        $inputId = Html::getInputId($this->model, $this->attribute);

        $options = [
            'refreshUrl' => Url::toRoute($route),
            'hashKey' => 'fishCaptcha/' . trim($route[0], '/'),
            'input' => '#' . $this->options['id'],
            'onInputChange' => "$('#{$inputId}').parents('form').yiiActiveForm('validateAttribute', '{$inputId}');",
        ];

        return $options;
    }
}