<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('language_name', [$this, 'getLanguageName']),
        ];
    }

    public function getLanguageName(string $code, string $locale = 'fr'): string
    {
        return \Locale::getDisplayLanguage($code, $locale);
    }
}
