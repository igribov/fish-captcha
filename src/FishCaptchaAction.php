<?php

namespace phprad\fishcaptcha;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\ServerErrorHttpException;


class FishCaptchaAction extends Action
{
    /**
     * The name of the GET parameter indicating whether the CAPTCHA image should be regenerated.
     */
    const REFRESH_GET_VAR = 'refresh';

    /**
     * @var string the fixed verification code. When this property is set,
     * [[getVerifyCode()]] will always return the value of this property.
     * This is mainly used in automated tests where we want to be able to reproduce
     * the same verification code each time we run the tests.
     * If not set, it means the verification code will be randomly generated.
     */
    public $fixedVerifyCode;

    /**
     * @var int how many times should the same CAPTCHA be displayed. Defaults to 3.
     * A value less than or equal to 0 means the test is unlimited (available since version 1.1.2).
     */
    public $testLimit = 3;

    /**
     * @return array|bool|string
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        if (Yii::$app->request->getQueryParam(self::REFRESH_GET_VAR) !== null) {
            // AJAX request for regenerating code
            $code = $this->getVerifyCode(true);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'hash1' => $this->generateValidationHash($code),
                'hash2' => $this->generateValidationHash(strtolower($code)),
                // we add a random 'v' parameter
                // when src attribute of image tag is changed
                'url' => Url::to([$this->id, 'v' => uniqid('', true)]),
            ];
        }

        $this->setHttpHeaders();
        Yii::$app->response->format = Response::FORMAT_RAW;

        $img = $this->renderImage($this->getVerifyCode());

        if (!$img) {
            throw new ServerErrorHttpException();
        }

        return $img;
    }

    /**
     * Generates a hash code that can be used for client-side validation.
     * @param string $code the CAPTCHA code
     * @return string a hash code generated from the CAPTCHA code
     */
    public function generateValidationHash($code)
    {
        for ($h = 0, $i = strlen($code) - 1; $i >= 0; --$i) {
            $h += ord($code[$i]);
        }

        return $h;
    }

    /**
     * @param bool $regenerate
     * @return mixed|string
     */
    public function getVerifyCode($regenerate = false)
    {
        if ($this->fixedVerifyCode !== null) {
            return $this->fixedVerifyCode;
        }

        $session = Yii::$app->getSession();
        $session->open();
        $name = $this->getSessionKey();
        $newFishCode = $previousFishCode = $session[$name];
        while ($previousFishCode === $newFishCode) {
            $newFishCode = $this->generateFishCode();
        }
        if ($session[$name] === null || $regenerate) {
            $session[$name] = $newFishCode;
            $session[$name . 'count'] = 1;
        }

        return $session[$name];
    }

    /**
     * @param $input
     * @param $caseSensitive
     * @return bool
     */
    public function validate($input, $caseSensitive)
    {
        $code = $this->getVerifyCode();
        $input = FishRepository::getCodeByFishName($input);
        $valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;

        $session = Yii::$app->getSession();
        $session->open();
        $name = $this->getSessionKey() . 'count';
        $session[$name] += 1;
        if ($valid || $session[$name] > $this->testLimit && $this->testLimit > 0) {
            $this->getVerifyCode(true);
        }

        return $valid;
    }

    /**
     * @return string
     */
    protected function generateFishCode()
    {
        return FishRepository::getRandomFishCode();
    }

    /**
     * Returns the session variable name used to store verification code.
     * @return string the session variable name
     */
    protected function getSessionKey()
    {
        return '__fish_captcha/' . $this->getUniqueId();
    }

    /**
     * @param $code
     * @return bool|string
     */
    protected function renderImage($code)
    {
        $fileName = FishRepository::getFileNameByFishCode($code);
        $filePath = __DIR__ . '/frontend/dist/img/' . $fileName;

        if (is_file($filePath)) {
            return file_get_contents($filePath);
        }

        return false;
    }

    /**
     * Sets the HTTP headers needed by image response.
     */
    protected function setHttpHeaders()
    {
        Yii::$app->getResponse()->getHeaders()
            ->set('Pragma', 'public')
            ->set('Expires', '0')
            ->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->set('Content-Transfer-Encoding', 'binary')
            ->set('Content-type', 'image/png');
    }
}
