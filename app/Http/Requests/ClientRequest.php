<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            // Client Profile fields
            'subscription_type' => 'required|in:basic,premium,enterprise',
            'subscription_status' => 'required|in:active,inactive,suspended,expired,trial',
            'subscription_start_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date|after:subscription_start_date',
            'device_limit' => 'required|integer|min:1|max:50',
            'payment_status' => 'required|in:paid,pending,overdue,failed',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'client_notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
            'subscription_end_date.after' => 'تاريخ نهاية الاشتراك يجب أن يكون بعد تاريخ البداية.',
        ];
    }
}
