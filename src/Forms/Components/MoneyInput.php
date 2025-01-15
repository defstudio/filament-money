<?php /** @noinspection PhpUnused */

namespace DefStudio\FilamentMoney\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;
use Symfony\Component\Intl\Currencies;

class MoneyInput extends TextInput
{
    private bool|Closure $inverted_colors = false;
    private bool|Closure $inverted_values = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->numeric()->step(0.01)->prefix(Currencies::getSymbol(config('app.currency')));

        $this->extraInputAttributes(function(MoneyInput $component) {
            $inverted_colors = $this->evaluate($this->inverted_colors) ? 1 : 0;
            $path = $component->getStatePath();
            return [
                'x-data' => "{path: '$path', state: \$wire.\$entangle('$path')}",
                'x-bind:class' => "money_color_class(state, $inverted_colors)",
            ];
        });

        $this->dehydrateStateUsing(function($state) {
            if (empty($state)) {
                return $state;
            }

            $multiplier = $this->evaluate($this->inverted_values) ? -1 : 1;

            return $state * $multiplier;
        });

        $this->formatStateUsing(function($state) {
            if (empty($state)) {
                return $state;
            }

            $multiplier = $this->evaluate($this->inverted_values) ? -1 : 1;

            return $state * $multiplier;
        });
    }

    public function inverted_colors(bool|Closure $inverted_colors): static
    {
        $this->inverted_colors = $inverted_colors;
        return $this;
    }

    public function inverted_values(bool|Closure $inverted_values): static
    {
        $this->inverted_values = $inverted_values;
        return $this;
    }
}
