<?php

/** @noinspection PhpUnused */

namespace DefStudio\FilamentMoney\Tables\Columns;

use Closure;
use DefStudio\FilamentMoney\Actions\MoneyColor;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Number;

class MoneyColumn extends TextColumn
{
    protected bool | Closure $invertedValue = false;

    protected bool | Closure $invertedColor = false;

    protected bool | Closure $hiddenZeros = false;

    protected bool | Closure $absolute = false;

    protected string | Closure | null $currency = null;

    protected string | Closure | null $locale = null;

    protected function setUp(): void
    {
        $this->money();

        $this->alignRight();

        $this->color(
            fn ($state) => app(MoneyColor::class)
                ->getFilamentColor($state, $this->evaluate($this->invertedColor))
        );

        $this->formatStateUsing(function (MoneyColumn $column, $state) {
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

            $currency = $column->evaluate($column->currency) ?? Table::$defaultCurrency;
            $locale = $column->evaluate($column->locale) ?? Table::$defaultNumberLocale ?? config('app.locale');

            return Number::currency($state, $currency, $locale);
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

    public function currency(string | Closure | null $currency = null): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function locale(string | Closure | null $locale = null): self
    {
        $this->locale = $locale;

        return $this;
    }
}
