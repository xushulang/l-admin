<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PhoneVerificationCodeController extends Controller
{
    /**
     * Get verification code.
     */
    public function store(Request $request): array
    {
        $request->validate([
            'phone' => ['required', 'string', new Phone],
        ]);

        if (!auth()->check()) {
            $exists = User::where('phone', $request->phone)->exists();

            if (!$exists) {
                $data = [
                    'phone' => $request->phone,
                    'password' => Hash::make(Str::random(16)),
                ];

                User::create($data);
            }
        }

        if (!Cache::has('phone_code_' . $request->phone)) {
            $code = mt_rand(100000, 999999);
            $validityPeriod = 180;
            Cache::put('phone_code_' . $request->phone, $code, $validityPeriod);

            try {
                app('sms')->send($request->phone, [
                    'content'  => '您的验证码为: ' . $code,
                    'template' => config('sms.gateways.aliyun.template_code_verification'),
                    'data' => [
                        'code' => $code
                    ],
                ]);
            } catch (\Throwable $th) {
                return [
                    'status' => 'failure',
                    'message' => trans('Verification code sending failed.'),
                ];
            }

            return [
                'status' => 'success',
                'message' => trans('Valid for :count minutes.', ['count' => floor($validityPeriod / 60)]),
            ];
        } else {
            return [
                'status' => 'failure',
                'message' => trans('Verification code has been sent.'),
            ];
        }
    }
}
