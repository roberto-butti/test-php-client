<?php

namespace MyExample\TermLib;

use function Termwind\render;

class Term
{
    public static function title($title)
    {
        render(<<<HTML
            <div class="space-x-1">
                <span class="px-1 bg-blue-500 text-white">{$title}</span>
            </div>
        HTML);
    }

    public static function labelValue($label, $value, $colorValue = "green")
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