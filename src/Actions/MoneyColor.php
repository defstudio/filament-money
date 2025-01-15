<?php /** @noinspection PhpUnused */

namespace DefStudio\FilamentMoney\Actions;

use Filament\Support\Colors\Color;

class MoneyColor
{
    public function getColorClass(float|null|string $value, bool $inverted = false): string
    {
        return $this->computeColor($value, 'class', $inverted) ?? '';
    }

    public function getFilamentColor(float|null|string $value, bool $inverted = false): array|null
    {
        return $this->computeColor($value, 'filament', $inverted);
    }

    protected function computeColor(float|null|string $value, string $type, bool $inverted = false): string|array|null
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
            'class' => 'defstudio-money-color-red',
            'filament' => Color::Red
        };
    }

    protected function green(string $type): string|array
    {
        return match ($type) {
            'class' => 'defstudio-money-color-green',
            'filament' => Color::Green
        };
    }
}
