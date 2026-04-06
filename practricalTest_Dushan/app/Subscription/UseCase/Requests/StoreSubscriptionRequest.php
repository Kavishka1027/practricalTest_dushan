<?php

namespace App\Subscription\UseCase\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_id' => ['required', 'integer', 'exists:websites,id'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('subscriptions', 'email')->where(
                    fn ($query) => $query->where('website_id', $this->website_id)
                ),
            ],
        ];
    }
}
