<?php

namespace phprad\fishcaptcha;

/**
 * Class FishRepository
 * @package phprad\fishcaptcha
 */
class FishRepository
{
    /** fishes */
    const FISHES = [
        'карась' => 'karas',
        'карп' => 'karp',
        'подлещик' => 'podleshik',
        'щука' => 'shuka',
        'сом' => 'som',
        'судак' => 'sudak',
        'язь' => 'yaz'
    ];

    /** fish to images map. One fish to many images */
    const FISH_CODES_TO_IMAGES = [
        'karas' => ['1.jpeg', '1.jpeg', '1.jpeg',],
        'karp' => ['2.jpeg', '2.jpeg', '2.jpeg',],
        'podleshik' => ['3.jpeg', '3.jpeg', '3.jpeg',],
        'shuka' => ['4.jpeg', '4.jpeg', '4.jpeg',],
        'som' => ['5.jpeg', '5.jpeg', '5.jpeg',],
        'sudak' => ['6.jpeg', '6.jpeg', '6.jpeg',],
        'yaz' => ['7.jpeg', '7.jpeg', '7.jpeg',],
    ];

    /**
     * @param $name
     * @return string|null
     */
    public static function getCodeByFishName($name)
    {
        $name = mb_strtolower($name);

        return self::FISHES[$name];
    }

    /**
     * @param $code
     * @return string|null
     */
    public static function getFishNameByCode($code)
    {
        $fishes = array_flip(self::FISHES);
        $code = mb_strtolower($code);

        return $fishes[$code];
    }

    /**
     * @param $code
     * @return string|null
     */
    public static function getFileNameByFishCode($code)
    {
        $imagesWithThatFish = self::FISH_CODES_TO_IMAGES[$code];
        // return random fish image
        return $imagesWithThatFish[rand(0, count($imagesWithThatFish) - 1)];
    }

    /**
     * @return mixed
     */
    public static function getRandomFishCode()
    {
        return array_rand(self::FISH_CODES_TO_IMAGES);
    }
}