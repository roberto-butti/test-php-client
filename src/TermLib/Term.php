<?php

namespace MyExample\TermLib;

use function Termwind\render;

class Term
{
    public static function title(...$args)
    {
        if (count($args) < 1) {
            $format = "";
        } else {
            $format = array_shift($args);
        }
        $formattedString = sprintf($format, ...$args);

        render(<<<HTML
            <div class="space-x-1">
                <span class="px-2 py-1 bg-blue-500 text-white">{$formattedString}</span>
            </div>
        HTML);
    }
    public static function sectionTitle(...$args)
    {
        if (count($args) < 1) {
            $format = "";
        } else {
            $format = array_shift($args);
        }
        $formattedString = sprintf($format, ...$args);

        render(<<<HTML
            <div class="space-x-1">
                <span class="px-2 py-1  text-blue-300">{$formattedString}</span>
            </div>
        HTML);
    }

    public static function labelValue($label, $value, $colorValue = 'green')
    {
        render(<<<HTML
        <div class="flex space-x-1">
            <span class="font-bold">{$label}</span>
            <span class="flex-1 content-repeat-[.] text-gray"></span>
            <span class="font-bold text-{$colorValue}">{$value}</span>
        </div>
        HTML);
    }
}
