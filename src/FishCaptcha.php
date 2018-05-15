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
     * @var string|array the route of the action that generates the CAPTCHA images.
     * The action represented by this route must be an action of [[CaptchaAction]].
     * Please refer to [[\yii\helpers\Url::toRoute()]] for acceptable formats.
     */
    public $captchaAction = 'site/fish-captcha';
    /**
     * @var array HTML attributes to be applied to the CAPTCHA image tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $imageOptions = [];
    /**
     * @var string the template for arranging the CAPTCHA image tag and the text input tag.
     * In this template, the token `{image}` will be replaced with the actual image tag,
     * while `{input}` will be replaced with the text input tag.
     */
    public $template = '<div id="{id}"></div>{input}';
    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
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
     * Returns the options for the captcha JS widget.
     * @return array the options
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