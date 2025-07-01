<?php

namespace Lkn\HookNotification\Core\Shared\Infrastructure\I18n;

use Lkn\HookNotification\Core\Shared\Infrastructure\Singleton;

final class I18n extends Singleton
{
    /**
     * @var array<string, string>
     */
    private static array $strings = [];

    public static function load(string $langCode): void
    {
        $customTranslationFile = __DIR__ . './../../../../Notifications/Custom/lang/' . $langCode . '.php';

        require $customTranslationFile;

        /** @var array<string, string> $_ADDONLANG */
        $customTranslations = $_ADDONLANG ?? [];

        $internalTranslationFile = __DIR__ . '/../../../../../lang/' . $langCode . '.php';

        require $internalTranslationFile;

        $internalTranslations = $_ADDONLANG ?? [];

        self::$strings = array_merge($customTranslations, $internalTranslations);
    }

    /**
     * @param  string $language
     *
     * @return array<string, string>
     */
    public static function getTranslationsForCurrentLanguage(string $language): array
    {
        if ($language === 'portuguese-br') {
            $language ='portugues-br';
        } elseif ($language === 'portuguese-pt') {
            $language ='portugues-pt';
        }

        self::load($language);

        return self::$strings;
    }

    /**
     * @param  string        $key
     * @param  array<string> $replacements
     *
     * @return string
     */
    public static function get(string $key, array $replacements = []): string
    {
        $string = self::$strings[$key] ?? $key;

        foreach ($replacements as $k => $v) {
            $string = str_replace(':' . $k, $v, $string);
        }

        return $string;
    }
}
