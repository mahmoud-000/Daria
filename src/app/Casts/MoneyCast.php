<?php


namespace App\Casts;

use Brick\Math\RoundingMode;
use Brick\Money\Context\CashContext;
use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;
class MoneyCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return Money::ofMinor($value, Str::upper($attributes['currency']), new CashContext(5), RoundingMode::UP);
    }

    public function set($model, $key, $value, $attributes)
    {
        if (! $value instanceof \Brick\Money\Money) {
            return Money::of($value, Str::upper($attributes['currency']), new CashContext(5), RoundingMode::UP)->getMinorAmount()->toInt();
        }

        return $value->getMinorAmount()->toInt();
    }
}
