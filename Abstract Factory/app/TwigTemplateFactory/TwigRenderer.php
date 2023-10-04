<?php

namespace App\TwigTemplateFactory;

use App\TemplateRenderer;

/**
 * Отрисовщик шаблонов Twig.
 */
class TwigRenderer implements TemplateRenderer
{
    public function render(string $templateString, array $arguments = []): string
    {
        $loader = new \Twig\Loader\FilesystemLoader('/var/www/html/patterns/Abstract Factory/app/');
        $twig = new \Twig\Environment($loader, []);
        $template = $twig->createTemplate($templateString);
//        $template = $twig->load($templateString);
        return $template->render($arguments);
    }
}