# Google reCAPTCHA v3 Implementation Guide

## 📋 Overview

This document details the implementation of Google reCAPTCHA v3 in the GrowithBI application. reCAPTCHA v3 provides invisible bot protection specifically for **user (peserta)** authentication flows without requiring user interaction.

**Version:** reCAPTCHA v3  
**Implementation Date:** January 9, 2026  
**Scope:** User (peserta) authentication only  
**Status:** ✅ Production Ready

---

## ⚠️ CRITICAL: Fixing browser-error Issue

If you're getting `browser-error` when verifying reCAPTCHA tokens, follow these steps:

### Root Cause
The reCAPTCHA site key is **not registered for localhost** in Google reCAPTCHA Admin Console.

### Solution: Create New reCAPTCHA v3 Keys

1. **Go to Google reCAPTCHA Admin Console:**
   - Visit: https://www.google.com/recaptcha/admin

2. **Create a NEW reCAPTCHA v3 site:**
   - Click **"+"** button to create new site
   - **Label:** `GrowithBI Development` (or any name)
   - **reCAPTCHA type:** Select **"Score based (v3)"** ⚠️ NOT v2!
   - **Domains:** Add ALL of these:
     - `localhost`
     - `127.0.0.1`
     - Your production domain (e.g., `growithbi.com`)
   - Click **Submit**

3. **Copy the Keys:**
   - **Site Key:** Copy and paste to `.env` as `RECAPTCHA_SITE_KEY`
   - **Secret Key:** Copy and paste to `.env` as `RECAPTCHA_SECRET_KEY`

4. **Update `.env`:**
   ```env
   RECAPTCHA_SITE_KEY=your_new_site_key_here
   RECAPTCHA_SECRET_KEY=your_new_secret_key_here
   ```

5. **Clear config cache:**
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

6. **Test using debug endpoint:**
   - Open: `http://localhost:8000/debug/captcha/test-page`
   - Click "Test Login Action"
   - If successful, you'll see `score: 0.9` in the response

---

## 🎯 Implementation Scope

### Protected User Flows

-   ✅ **Login** - User (peserta) login authentication
-   ✅ **Forgot Password** - Password reset request
-   ✅ **OTP Verification** - Email OTP verification during login

### Excluded Flows

-   ❌ **Admin Login** - Admin authentication (no captcha)
-   ❌ **Pembimbing Login** - Supervisor authentication (no captcha)
-   ❌ **Internal Operations** - All admin panel operations

---

## 🏗️ Architecture

### System Design

```
┌─────────────────────────────────────────────────────────────┐
│                     Frontend (Vue 3)                        │
│  ┌────────────────────────────────────────────────────┐    │
│  │  Login.vue / ForgotPassword.vue / OTPVerify.vue   │    │
│  │  - Load reCAPTCHA v3 script                        │    │
│  │  - Execute grecaptcha.execute()                    │    │
│  │  - Attach token to request payload                 │    │
│  └──────────────────┬─────────────────────────────────┘    │
└────────────────────┼──────────────────────────────────────┘
                     │ POST with captcha_token
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                   Backend (Laravel)                         │
│  ┌────────────────────────────────────────────────────┐    │
│  │  LoginController / ForgotPasswordController        │    │
│  │  - Check user role (peserta only)                  │    │
│  │  - Call CaptchaVerificationService                 │    │
│  └──────────────────┬─────────────────────────────────┘    │
│                     ▼                                       │
│  ┌────────────────────────────────────────────────────┐    │
│  │  CaptchaVerificationService                        │    │
│  │  - Verify token with Google API                    │    │
│  │  - Validate score >= 0.5                           │    │
│  │  - Validate action context                         │    │
│  │  - Return verification result                      │    │
│  └──────────────────┬─────────────────────────────────┘    │
└────────────────────┼──────────────────────────────────────┘
                     │ Google API Call
                     ▼
┌─────────────────────────────────────────────────────────────┐
│           Google reCAPTCHA API                              │
│  https://www.google.com/recaptcha/api/siteverify           │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔧 Configuration

### Environment Variables

Add these variables to your `.env` file:

```env
# Google reCAPTCHA v3
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
RECAPTCHA_ENABLED=true
RECAPTCHA_SCORE_THRESHOLD=0.5
```

### Configuration File

Location: `config/services.php`

```php
'recaptcha' => [
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    'enabled' => env('RECAPTCHA_ENABLED', true),
    'score_threshold' => env('RECAPTCHA_SCORE_THRESHOLD', 0.5),
],
```

### Obtain reCAPTCHA Keys

1. Visit [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Click **"+"** to create a new site
3. Configure:
    - **Label:** GrowithBI Production
    - **reCAPTCHA type:** Score based (v3)
    - **Domains:** Add your domain(s)
4. Copy **Site Key** and **Secret Key**
5. Add to `.env` file

---

## 💻 Backend Implementation

### 1. CaptchaVerificationService

**Location:** `app/Services/CaptchaVerificationService.php`

**Purpose:** Centralized service for verifying reCAPTCHA tokens with Google API

**Key Methods:**

-   `verify(string $token, string $action): array` - Main verification method
-   `isEnabled(): bool` - Check if captcha is enabled
-   `validateScore(float $score): bool` - Validate score threshold
-   `shouldSkipForRole(string $role): bool` - Role-based skip logic

**Usage Example:**

```php
use App\Services\CaptchaVerificationService;

$captchaService = new CaptchaVerificationService();

$result = $captchaService->verify($request->captcha_token, 'login');

if (!$result['success']) {
    return back()->withErrors([
        'email' => 'Verifikasi keamanan gagal. Silakan coba kembali.'
    ]);
}
```

**Response Structure:**

```php
[
    'success' => true|false,
    'score' => 0.0-1.0,
    'action' => 'login',
    'challenge_ts' => '2026-01-09T12:00:00Z',
    'hostname' => 'yourdomain.com',
    'error-codes' => []
]
```

### 2. LoginController Integration

**Location:** `app/Http/Controllers/Auth/LoginController.php`

**Changes:**

-   Added captcha verification before authentication
-   Role-based captcha enforcement (peserta only)
-   Proper error handling and logging

**Key Code:**

```php
public function login(Request $request)
{
    // Captcha verification for peserta only
    $user = User::where('email', $request->email)->first();

    if ($user && $user->role === 'peserta') {
        $captchaService = new CaptchaVerificationService();

        if ($captchaService->isEnabled()) {
            $result = $captchaService->verify(
                $request->captcha_token ?? '',
                'login'
            );

            if (!$result['success']) {
                Log::warning('Captcha verification failed', [
                    'email' => $request->email,
                    'score' => $result['score'] ?? 'N/A'
                ]);

                return back()->withErrors([
                    'email' => 'Verifikasi keamanan gagal.'
                ]);
            }
        }
    }

    // Continue with authentication...
}
```

### 3. ForgotPasswordController Integration

**Location:** `app/Http/Controllers/Auth/ForgotPasswordController.php`

**Changes:**

-   Added captcha verification before sending reset link
-   Same role-based enforcement as login

### 4. OTP Verification Integration

**Location:** `app/Http/Controllers/Auth/OTPController.php` (if exists)

**Changes:**

-   Added captcha verification before OTP validation
-   Prevents automated OTP attempts

---

## 🎨 Frontend Implementation

### 1. Composable for reCAPTCHA

**Location:** `resources/js/composables/useRecaptcha.js`

**Purpose:** Reusable Vue 3 composable for loading and executing reCAPTCHA

**Features:**

-   Lazy-loads reCAPTCHA script
-   Handles script loading state
-   Provides clean API for token generation

**Usage:**

```javascript
import { useRecaptcha } from "@/composables/useRecaptcha";

const { executeRecaptcha, isReady } = useRecaptcha();

const token = await executeRecaptcha("login");
```

### 2. Login Page Integration

**Location:** `resources/js/Pages/Auth/Login.vue`

**Changes:**

```vue
<script setup>
import { useRecaptcha } from "@/composables/useRecaptcha";

const { executeRecaptcha, isReady } = useRecaptcha();

const submit = async () => {
    try {
        // Get captcha token
        const captchaToken = await executeRecaptcha("login");

        // Submit with token
        form.transform((data) => ({
            ...data,
            captcha_token: captchaToken,
        })).post(route("login"));
    } catch (error) {
        console.error("Captcha error:", error);
        // Proceed or show error
    }
};
</script>
```

### 3. Forgot Password Integration

**Location:** `resources/js/Pages/Auth/ForgotPassword.vue`

**Changes:** Similar to Login.vue but with action `'forgot_password'`

### 4. OTP Verification Integration

**Location:** `resources/js/Pages/Auth/VerifyOTP.vue`

**Changes:** Similar to Login.vue but with action `'otp_verification'`

---

## 🔒 Security Considerations

### Server-Side Validation (Critical)

✅ **Always validate on backend** - Never trust client-side validation alone  
✅ **Verify token freshness** - Tokens are single-use and expire quickly  
✅ **Validate action context** - Ensure action matches expected value  
✅ **Check score threshold** - Reject requests below 0.5 score  
✅ **Rate limiting** - Combine with rate limiting for defense in depth

### Secret Key Protection

❌ **Never expose secret key** to frontend or version control  
✅ **Use environment variables** for all sensitive keys  
✅ **Rotate keys periodically** in production

### Error Handling

✅ **Generic error messages** - Don't reveal captcha failure details  
✅ **Log failures** - Monitor for suspicious patterns  
✅ **Fail securely** - Block access if verification fails

### API Failure Handling

If Google API is unreachable:

-   **Option 1:** Block login (secure but impacts availability)
-   **Option 2:** Allow with alert (less secure but maintains availability)
-   **Current Implementation:** Blocks login and logs incident

---

## 📊 Score Interpretation

reCAPTCHA v3 returns a score from 0.0 to 1.0:

| Score Range | Interpretation         | Action               |
| ----------- | ---------------------- | -------------------- |
| 0.9 - 1.0   | Very likely legitimate | ✅ Allow             |
| 0.7 - 0.9   | Likely legitimate      | ✅ Allow             |
| 0.5 - 0.7   | Neutral                | ✅ Allow (threshold) |
| 0.3 - 0.5   | Suspicious             | ❌ Block             |
| 0.0 - 0.3   | Very likely bot        | ❌ Block             |

**Default Threshold:** 0.5 (configurable via `RECAPTCHA_SCORE_THRESHOLD`)

---

## 🧪 Testing

### Manual Testing

#### Test Peserta Login with Captcha

1. Open login page as peserta user
2. Open browser DevTools > Network tab
3. Enter credentials and submit
4. Verify request payload includes `captcha_token`
5. Verify successful login

#### Test Admin Login without Captcha

1. Open login page as admin user
2. Enter credentials and submit
3. Verify request has NO `captcha_token`
4. Verify successful login

#### Test Invalid Token

1. Modify captcha token in DevTools before submit
2. Submit login form
3. Verify error message: "Verifikasi keamanan gagal"

### Automated Testing

```php
// tests/Feature/Auth/LoginWithCaptchaTest.php

public function test_peserta_login_requires_valid_captcha()
{
    $user = User::factory()->create(['role' => 'peserta']);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'captcha_token' => 'invalid_token'
    ]);

    $response->assertSessionHasErrors('email');
}

public function test_admin_login_skips_captcha()
{
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password'
        // No captcha_token
    ]);

    $response->assertRedirect('/dashboard');
}
```

---

## 🐛 Troubleshooting

### Issue: "Captcha verification failed" on valid attempts

**Possible Causes:**

1. Wrong secret key in `.env`
2. Site key doesn't match domain
3. Network connectivity to Google API
4. Score below threshold

**Solution:**

1. Verify keys in `.env` match Google Console
2. Check domain is registered in reCAPTCHA admin
3. Test Google API connectivity: `curl https://www.google.com/recaptcha/api/siteverify`
4. Lower threshold temporarily: `RECAPTCHA_SCORE_THRESHOLD=0.3`

### Issue: Captcha script not loading

**Possible Causes:**

1. Content Security Policy blocking script
2. Ad blocker or privacy extension
3. Network issues

**Solution:**

1. Add Google domains to CSP: `script-src 'self' https://www.google.com https://www.gstatic.com`
2. Test in incognito mode
3. Check browser console for errors

### Issue: "grecaptcha is not defined"

**Cause:** Script loaded but not yet ready

**Solution:** Wait for `isReady` before calling `executeRecaptcha`:

```javascript
watch(isReady, (ready) => {
    if (ready) {
        // Now safe to execute
    }
});
```

---

## 📈 Monitoring & Analytics

### Log Monitoring

Monitor these log events:

```bash
# Captcha verification failures
grep "Captcha verification failed" storage/logs/laravel.log

# Low scores
grep "score" storage/logs/laravel.log | grep "0\.[0-4]"

# API failures
grep "Google reCAPTCHA API" storage/logs/laravel.log
```

### Google reCAPTCHA Dashboard

Access analytics at: [https://www.google.com/recaptcha/admin](https://www.google.com/recaptcha/admin)

**Metrics to Monitor:**

-   Total requests
-   Score distribution
-   Verification success rate
-   Suspicious activity patterns

### Key Performance Indicators

-   **Success Rate:** Should be > 95% for legitimate users
-   **Average Score:** Should be > 0.7 for real users
-   **Failed Attempts:** Monitor spikes indicating attacks
-   **API Latency:** Should be < 500ms

---

## 🔄 Maintenance

### Regular Tasks

**Weekly:**

-   Review captcha failure logs
-   Check score distribution
-   Verify no false positives

**Monthly:**

-   Review and adjust score threshold if needed
-   Check Google API status and updates
-   Verify domain configuration

**Quarterly:**

-   Rotate reCAPTCHA keys (optional but recommended)
-   Review security incidents
-   Update documentation if logic changes

### Key Rotation

To rotate keys:

1. Generate new keys in Google Console
2. Update `.env` with new keys
3. Deploy to production
4. Monitor for issues
5. Remove old keys from Google Console after 24 hours

---

## 📝 Code References

### Backend Files

-   `app/Services/CaptchaVerificationService.php` - Main captcha service
-   `app/Http/Controllers/Auth/LoginController.php` - Login integration
-   `app/Http/Controllers/Auth/ForgotPasswordController.php` - Password reset integration
-   `config/services.php` - Configuration

### Frontend Files

-   `resources/js/composables/useRecaptcha.js` - Vue composable
-   `resources/js/Pages/Auth/Login.vue` - Login page
-   `resources/js/Pages/Auth/ForgotPassword.vue` - Forgot password page
-   `resources/js/Pages/Auth/VerifyOTP.vue` - OTP verification page

### Configuration Files

-   `.env` - Environment variables
-   `.env.example` - Environment template

---

## 🎓 Best Practices Summary

### ✅ DO

-   Verify tokens server-side always
-   Use environment variables for keys
-   Log failures for monitoring
-   Combine with rate limiting
-   Test across different scenarios
-   Monitor Google Analytics dashboard
-   Provide user-friendly error messages

### ❌ DON'T

-   Trust client-side validation only
-   Expose secret keys to frontend
-   Store or reuse captcha tokens
-   Show technical error details to users
-   Skip verification for any public endpoint
-   Ignore monitoring and logs
-   Use captcha as sole security measure

---

## 🌐 External Resources

-   [Google reCAPTCHA v3 Documentation](https://developers.google.com/recaptcha/docs/v3)
-   [reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
-   [Best Practices Guide](https://developers.google.com/recaptcha/docs/v3#best_practices)
-   [FAQ](https://developers.google.com/recaptcha/docs/faq)

---

## 📞 Support

For issues or questions:

1. Check logs: `storage/logs/laravel.log`
2. Review this documentation
3. Test in isolation (API endpoint test)
4. Contact security team if persistent issues

---

**Document Version:** 1.0  
**Last Updated:** January 9, 2026  
**Maintained By:** Security & Fullstack Engineering Team  
**Status:** ✅ Active Implementation
