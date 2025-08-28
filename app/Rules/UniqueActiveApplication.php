<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Application;

class UniqueActiveApplication implements ValidationRule
{
    protected $email;
    protected $userId;

    public function __construct($email = null, $userId = null)
    {
        $this->email = $email;
        $this->userId = $userId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Application::whereIn('status', ['menunggu', 'dalam_review', 'wawancara', 'diterima']);

        if ($this->email) {
            $query->where('email', $this->email);
        } elseif ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        $existingApplication = $query->with('division')->first();

        if ($existingApplication) {
            $message = $existingApplication->status === 'diterima' 
                ? 'Anda sudah diterima di divisi ' . $existingApplication->division->name . '. Anda tidak bisa mendaftar ke divisi lain.'
                : 'Anda sudah memiliki pendaftaran yang sedang diproses di divisi ' . $existingApplication->division->name . '. Tunggu hasil proses tersebut sebelum mendaftar ke divisi lain.';
            
            $fail($message);
        }
    }
}
