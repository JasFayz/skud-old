<?php

namespace Modules\Skud\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RangeDateRule implements Rule
{

    const RANGE_MONTH = 3;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private $start_date)
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $start_month = Carbon::parse($this->start_date)->day;
        $end_month = Carbon::parse($value)->day;

        if ($end_month === $start_month) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The range of date should in same day';
    }
}
