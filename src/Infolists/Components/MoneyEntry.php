<?php

/** @noinspection PhpUnused */

namespace DefStudio\FilamentMoney\Infolists\Components;

use Closure;
use DefStudio\FilamentMoney\Actions\MoneyColor;
use Filament\Infolists\Components\TextEntry;
use Symfony\Component\Intl\Currencies;

class MoneyEntry extends TextEntry
{
    private bool | Closure $invertedColors = false;

    private bool | Closure $invertedValues = false;

    private bool | Closure $absolute = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->numeric(2)
            ->prefix(Currencies::getSymbol(config('app.currency')));

        $this->color(function (?float $state) {
            if ($state === null) {
                return null;
            }

            if ($state == 0) {
                return null;
            }

            return app(MoneyColor::class)->getFilamentColor($state, $this->evaluate($this->invertedColors));
        });

        $this->formatStateUsing(function ($state) {
            if (empty($state)) {
                return $state;
            }

            $multiplier = $this->evaluate($this->invertedValues) ? -1 : 1;

            $state = $state * $multiplier;

            if ($this->evaluate($this->absolute)) {
                $state = abs($state);
            }

            return $state;
        });
    }

    public function invertColors(bool | Closure $inverted): static
    {
        $this->invertedColors = $inverted;

        return $this;
    }

    public function invertValues(bool | Closure $invert): static
    {
        $this->invertedValues = $invert;

        return $this;
    }

    public function absolute(bool | Closure $absolute): static
    {
        $this->absolute = $absolute;

        return $this;
    }
}
