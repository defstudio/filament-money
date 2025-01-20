<?php

/** @noinspection PhpUnused */

namespace DefStudio\FilamentMoney\Tables\Columns;

use Closure;
use DefStudio\FilamentMoney\Actions\MoneyColor;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Number;

class MoneyColumn extends TextColumn
{
    protected bool | Closure $invertedValue = false;

    protected bool | Closure $invertedColor = false;

    protected bool | Closure $hiddenZeros = false;

    protected bool | Closure $absolute = false;

    protected function setUp(): void
    {
        $this->money();

        $this->alignRight();

        $this->color(
            fn ($state) => app(MoneyColor::class)
                ->getFilamentColor($state, $this->evaluate($this->invertedColor))
        );

        $this->formatStateUsing(function ($state) {
            if ($state == 0 && $this->evaluate($this->hiddenZeros)) {
                return null;
            }

            if (blank($state)) {
                return null;
            }

            if (! is_numeric($state)) {
                return $state;
            }

            if ($this->evaluate($this->invertedValue)) {
                $state = -$state;
            }

            if ($this->evaluate($this->absolute)) {
                $state = abs($state);
            }

            return Number::currency($state, config('app.currency'), config('app.locale'));
        });
    }

    public function hideZeros(bool | Closure $hide = true): self
    {
        $this->hiddenZeros = $hide;

        return $this;
    }

    public function invertValue(bool | Closure $invert = true): self
    {
        $this->invertedValue = $invert;

        return $this;
    }

    public function invertColor(bool | Closure $invert = true): self
    {
        $this->invertedColor = $invert;

        return $this;
    }

    public function absolute(bool | Closure $absolute = true): self
    {
        $this->absolute = $absolute;

        return $this;
    }
}
