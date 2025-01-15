<?php /** @noinspection PhpUnused */

namespace DefStudio\FilamentMoney\Actions;

use Filament\Support\Colors\Color;

class MoneyColor
{
    public function get_color_class(float|null|string $value, bool $inverted = false): string
    {
        return $this->compute_color($value, 'class', $inverted) ?? '';
    }

    public function get_filament_color(float|null|string $value, bool $inverted = false): array|null
    {
        return $this->compute_color($value, 'filament', $inverted);
    }

    protected function compute_color(float|null|string $value, string $type, bool $inverted = false): string|array|null
    {
        if (empty($value)) {
            $value = 0;
        }

        if(!is_numeric($value)) {
            return null;
        }

        if ($inverted) {
            $value *= -1;
        }

        if (floatval($value) < 0) {
            return $this->red($type);
        }

        if (floatval($value) > 0) {
            return $this->green($type);
        }

        return null;
    }

    protected function red(string $type): string|array
    {
        return match ($type) {
            'class' => '!text-red-600 ![-webkit-text-fill-color:theme(colors.red.600)]',
            'filament' => Color::Red
        };
    }

    protected function green(string $type): string|array
    {
        return match ($type) {
            'class' => '!text-green-600 ![-webkit-text-fill-color:theme(colors.green.600)]',
            'filament' => Color::Green
        };
    }
}
