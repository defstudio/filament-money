<?php /** @noinspection PhpUnused */

namespace DefStudio\FilamentMoney\Tables\Columns;

use Closure;
use DefStudio\FilamentMoney\Actions\MoneyColor;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Number;

class MoneyColumn extends TextColumn
{
    protected bool|Closure $invert_value = false;
    protected bool|Closure $invert_color = false;
    protected bool $hide_zeros = false;

    protected function setUp(): void
    {
        $this->money();

        $this->alignRight();

        $this->color(fn($state) => app(MoneyColor::class)->get_filament_color($state, $this->evaluate($this->invert_color)));

        $this->formatStateUsing(function($state) {
            if($state == 0){
                return null;
            }

            if (blank($state)) {
                return null;
            }

            if (!is_numeric($state)) {
                return $state;
            }

            if ($this->evaluate($this->invert_value)) {
                $state = -$state;
            }

            return Number::currency($state, 'EUR', config('app.locale'));
        });
    }

    public function hide_zeros(): self
    {
        $this->hide_zeros = true;
        return $this;
    }

    public function invert_value(bool|Closure $invert = true): self
    {
        $this->invert_value = $invert;
        return $this;
    }

    public function invert_color(bool|Closure $invert = true): self
    {
        $this->invert_color = $invert;
        return $this;
    }
}
