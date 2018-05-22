<?php

namespace app\tests\_pages;

/**
 * Селекторы для страницы Новости.
 */
class NewsPage
{
    /**
     * @var string Селектор блока с новостью id=27.
     */
    public static $blockNewsSelector = 'div[data-key=27]';

    /**
     * @var string Селектор даты на странице новости.
     */
    public static $newsDateNewsDetailSelector = '.news-date';

    /**
     * @var string Селектор контента на странице новости.
     */
    public static $contentNewsDetailSelector = '.news-content';
}
