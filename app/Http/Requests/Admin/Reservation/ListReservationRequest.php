<?php

namespace App\Http\Requests\Admin\Reservation;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ListReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('view-any', Reservation::class);
    }

    public function prepareForValidation(): void
    {
        $this->mergeIfMissing([
            'date' => today()->format('Y-m-d'),
        ]);
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date:Y-m-d'],
        ];
    }
}
