<?php

namespace MyExample\TermLib;

use function Termwind\render;

class Term
{
    public static function title(...$args): void
    {
        $format = count($args) < 1 ? "" : array_shift($args);

        $formattedString = sprintf($format, ...$args);

        render(<<<HTML
            <div class="space-x-1">
                <span class="px-2 py-1 bg-blue-500 text-white">{$formattedString}</span>
            </div>
        HTML);
    }

    public static function sectionTitle(...$args): void
    {
        $format = count($args) < 1 ? "" : array_shift($args);

        $formattedString = sprintf($format, ...$args);

        render(<<<HTML
            <div class="space-x-1">
                <span class="px-2 py-1  text-blue-300">{$formattedString}</span>
            </div>
        HTML);
    }

    public static function formattedString(...$args): string
    {
        $format = count($args) < 1 ? "" : array_shift($args);

        return sprintf($format, ...$args);
    }

    public static function warning(...$args): void
    {
        $formattedString = self::formattedString(...$args);

        render(<<<HTML
            <div class="space-x-1 py-1">
                <span class="px-1 py-0  bg-yellow-500 text-gray-900">{$formattedString}</span>
            </div>
        HTML);
    }

    public static function labelValue($label, $value, $colorValue = 'green'): void
    {
        render(<<<HTML
        <div class="flex space-x-1">
            <span class="font-bold">{$label}</span>
            <span class="flex-1 content-repeat-[.] text-gray"></span>
            <span class="font-bold text-{$colorValue}">{$value}</span>
        </div>
        HTML);
    }

    public static function table(): void
    {
        render(<<<HTML
    <table>
        <thead>
            <tr>
                <th>Task</th>
                <th>Status</th>
            </tr>
        </thead>
        <tr>
            <th>Termwind</th>
            <td>✓ Done</td>
        </tr>
    </table>
HTML);
    }
}
