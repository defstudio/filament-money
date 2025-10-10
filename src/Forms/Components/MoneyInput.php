<?php

/** @noinspection PhpUnused */

namespace DefStudio\FilamentMoney\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Number;
use Symfony\Component\Intl\Currencies;

class MoneyInput extends TextInput
{
    private bool|Closure $invertedColors = false;

    private bool|Closure $invertedValues = false;

    private bool|Closure $noColors = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->numeric()->step(0.01)->prefix(Currencies::getSymbol(Number::defaultCurrency()));

        $this->afterStateUpdatedJs(function(MoneyInput $component) {
            if ($component->evaluate($this->noColors)) {
                return null;
            }

            $inverted_colors = $component->evaluate($component->invertedColors) ? 1 : 0;

            return <<<JS
                const input = \$el.querySelector('input');

                input.classList.remove(money_color_class(-\$state, $inverted_colors));
                input.classList.add(money_color_class(\$state, $inverted_colors));
            JS;
        });

        $this->dehydrateStateUsing(function($state) {
            if (empty($state)) {
                return $state;
            }

            $multiplier = $this->evaluate($this->invertedValues) ? -1 : 1;

            return $state * $multiplier;
        });

        $this->formatStateUsing(function($state) {
            if (empty($state)) {
                return $state;
            }

            $multiplier = $this->evaluate($this->invertedValues) ? -1 : 1;

            return $state * $multiplier;
        });
    }

    public function invertColors(bool|Closure $inverted): static
    {
        $this->invertedColors = $inverted;

        return $this;
    }

    public function invertValues(bool|Closure $invert): static
    {
        $this->invertedValues = $invert;

        return $this;
    }

    public function noColors(bool|Closure $value = true): static
    {
        $this->noColors = $value;

        return $this;
    }
}
