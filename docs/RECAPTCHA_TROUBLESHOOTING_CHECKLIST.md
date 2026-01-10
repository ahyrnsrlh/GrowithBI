# reCAPTCHA v3 - Checklist Troubleshooting

## ✅ Checklist Implementasi

### 1. Environment Configuration
```bash
# Pastikan .env sudah ada key-nya
cat .env | grep RECAPTCHA

# Output yang diharapkan:
# RECAPTCHA_SITE_KEY=6Lfmx0QsAAAAAEDF5HBb0zpadAsp261K7UjBaDnG
# RECAPTCHA_SECRET_KEY=6Lfmx0QsAAAAALOG08_ILrIukFlrYdLvfMTw6Z7U
```

**Action:**
```bash
# Clear config cache
php artisan config:clear
php artisan config:cache
```

### 2. Verify Backend Service
```bash
# Test if service is accessible
php artisan tinker
```

```php
// Di tinker, jalankan:
$service = new App\Services\CaptchaVerificationService();
$service->isEnabled(); // Should return true
$service->getSiteKey(); // Should return your site key
```

### 3. Check Frontend Props

Buka browser DevTools > Vue Devtools > Components > Login

**Check props:**
- `recaptchaSiteKey`: Harus ada value (bukan null)
- `recaptchaEnabled`: Harus true

### 4. Browser Console Check

Buka halaman login, cek console:

**Yang diharapkan:**
```
reCAPTCHA script loaded successfully
```

**Error umum:**
```javascript
// Error 1: Site key missing
"reCAPTCHA not enabled or site key missing"
// Solution: Check .env and config:cache

// Error 2: Script blocked
"Failed to load reCAPTCHA script"
// Solution: Check network tab, disable ad blocker

// Error 3: grecaptcha undefined
"grecaptcha is not defined"
// Solution: Wait for script to load, check isReady
```

### 5. Network Tab Check

**Saat load halaman login:**
1. Cek request ke: `https://www.google.com/recaptcha/api.js?render=YOUR_SITE_KEY`
   - Status: 200 OK
   - Response: JavaScript file

**Saat submit form:**
1. Cek POST request ke `/login`
2. Payload harus include: `captcha_token: "03AGdBq2..."` 
3. Response jika gagal: Error "Login gagal. Silakan coba kembali."

---

## 🔧 Langkah Troubleshooting

### Step 1: Verify .env Configuration
```bash
# Check if RECAPTCHA keys exist in .env
php -r "echo 'Site Key: ' . env('RECAPTCHA_SITE_KEY') . PHP_EOL;"
php -r "echo 'Secret Key: ' . env('RECAPTCHA_SECRET_KEY') . PHP_EOL;"
```

Expected output:
```
Site Key: 6Lfmx0QsAAAAAEDF5HBb0zpadAsp261K7UjBaDnG
Secret Key: 6Lfmx0QsAAAAALOG08_ILrIukFlrYdLvfMTw6Z7U
```

If empty or null:
```bash
# Copy from .env.example
cp .env.example .env.backup
# Add keys to .env manually
```

### Step 2: Clear All Caches
```bash
# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild config
php artisan config:cache

# Clear browser cache
# Press Ctrl+Shift+R in browser (hard reload)
```

### Step 3: Verify Config Loaded
```bash
php artisan tinker
```

```php
// In tinker:
config('services.recaptcha.site_key');
config('services.recaptcha.secret_key');

// Both should return your keys
```

### Step 4: Test Backend Service
```bash
php artisan tinker
```

```php
use App\Services\CaptchaVerificationService;

$service = new CaptchaVerificationService();

// Test 1: Is enabled?
$service->isEnabled(); // Should be true

// Test 2: Get site key
$service->getSiteKey(); // Should return your site key

// Test 3: Check role enforcement
$service->shouldRequireForEmail('peserta@example.com'); // true
$service->shouldRequireForEmail('admin@example.com'); // false
```

### Step 5: Test Controller Props
```bash
# Add dd() to controller temporarily
```

Edit `app/Http/Controllers/Auth/AuthenticatedSessionController.php`:

```php
public function create(Request $request): Response
{
    $data = [
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status'),
        'success' => session('success'),
        'recaptchaSiteKey' => $this->captchaService->getSiteKey(),
        'recaptchaEnabled' => $this->captchaService->isEnabled(),
    ];
    
    dd($data); // Temporary - remove after check
    
    return Inertia::render('Auth/Login', $data);
}
```

Expected output:
```php
array:5 [
  "canResetPassword" => true
  "status" => null
  "success" => null
  "recaptchaSiteKey" => "6Lfmx0QsAAAAAEDF5HBb0zpadAsp261K7UjBaDnG"
  "recaptchaEnabled" => true
]
```

**Remove dd() after verification!**

### Step 6: Frontend Console Logging

Add temporary logging to `Login.vue`:

```vue
<script setup>
// ... existing code

// Add after props definition
console.log('reCAPTCHA Config:', {
    siteKey: props.recaptchaSiteKey,
    enabled: props.recaptchaEnabled
});

// Add in submit function
const submit = async () => {
    console.log('Submit triggered');
    
    if (props.recaptchaEnabled && props.recaptchaSiteKey) {
        console.log('Executing reCAPTCHA...');
        const token = await executeRecaptcha("login");
        console.log('reCAPTCHA token:', token ? 'Generated' : 'Failed');
        form.captcha_token = token;
    }
    
    // ... rest of code
};
</script>
```

### Step 7: Check Browser Network

1. Open browser DevTools (F12)
2. Go to Network tab
3. Filter: `recaptcha`
4. Reload login page

**Expected requests:**
1. `api.js?render=YOUR_SITE_KEY` - Status: 200
2. `anchor?ar=...` - Status: 200
3. `reload?k=...` - Status: 200

**When submitting:**
1. POST `/login` with captcha_token in payload

### Step 8: Test with Different User

```bash
php artisan tinker
```

```php
// Test with admin (should skip captcha)
use App\Models\User;
$admin = User::where('role', 'admin')->first();
echo $admin->email;

// Test with peserta (should require captcha)
$peserta = User::where('role', 'peserta')->first();
echo $peserta->email;
```

Try login with both and check console logs.

---

## 🐛 Common Issues & Solutions

### Issue 1: "captcha_token is null"

**Cause:** Script not loaded or site key missing

**Solution:**
```bash
# 1. Verify .env
cat .env | grep RECAPTCHA_SITE_KEY

# 2. Clear config
php artisan config:clear

# 3. Restart server
php artisan serve
```

### Issue 2: "reCAPTCHA script blocked"

**Cause:** Ad blocker or CSP policy

**Solution:**
- Disable ad blocker temporarily
- Test in Incognito/Private mode
- Check CSP headers

### Issue 3: "grecaptcha is not defined"

**Cause:** Script loading race condition

**Solution:** Already handled in useRecaptcha.js with `isReady` ref

### Issue 4: "Login gagal" for all users

**Cause:** Captcha validation failing

**Solution:**
```bash
# Check logs
tail -f storage/logs/laravel.log | grep Captcha

# Look for:
# - "Missing captcha token"
# - "Low captcha score"
# - "Google API unreachable"
```

### Issue 5: Admin gets captcha error

**Cause:** Role-based bypass not working

**Solution:**
```bash
php artisan tinker
```

```php
use App\Models\User;
$user = User::where('email', 'your-admin@email.com')->first();
echo $user->role; // Should be 'admin', not 'Admin' or 'ADMIN'
```

---

## 🧪 Manual Testing Steps

### Test 1: Admin Login (Should Skip Captcha)
1. Open browser DevTools > Network tab
2. Navigate to `/login`
3. Enter admin credentials
4. Submit form
5. **Check:** Request payload should NOT have `captcha_token`
6. **Expected:** Successful login without captcha

### Test 2: Peserta Login (Should Require Captcha)
1. Open browser DevTools > Console + Network tab
2. Navigate to `/login`
3. Enter peserta credentials
4. **Check Console:** Should see "Executing reCAPTCHA..."
5. Submit form
6. **Check Network:** Request payload MUST have `captcha_token: "03AGdBq2..."`
7. **Expected:** Successful login with captcha

### Test 3: Invalid Token
1. Open DevTools > Console
2. Navigate to `/login`
3. Enter peserta credentials
4. In console, before submit, run:
   ```javascript
   // Override the token
   window.grecaptcha.execute = () => Promise.resolve('invalid_token_123');
   ```
5. Submit form
6. **Expected:** Error message "Login gagal. Silakan coba kembali."

### Test 4: Forgot Password
1. Navigate to `/forgot-password`
2. Enter peserta email
3. **Check:** Should execute captcha
4. Submit form
5. **Expected:** Works with captcha validation

---

## 📊 Quick Test Script

Save as `test-recaptcha.sh`:

```bash
#!/bin/bash

echo "=== reCAPTCHA Configuration Test ==="
echo ""

echo "1. Checking .env keys..."
if grep -q "RECAPTCHA_SITE_KEY" .env; then
    echo "✅ RECAPTCHA_SITE_KEY found"
else
    echo "❌ RECAPTCHA_SITE_KEY missing"
fi

if grep -q "RECAPTCHA_SECRET_KEY" .env; then
    echo "✅ RECAPTCHA_SECRET_KEY found"
else
    echo "❌ RECAPTCHA_SECRET_KEY missing"
fi

echo ""
echo "2. Clearing caches..."
php artisan config:clear > /dev/null 2>&1
php artisan cache:clear > /dev/null 2>&1
echo "✅ Caches cleared"

echo ""
echo "3. Rebuilding config..."
php artisan config:cache > /dev/null 2>&1
echo "✅ Config cached"

echo ""
echo "4. Testing service..."
php artisan tinker --execute="
    \$service = new App\Services\CaptchaVerificationService();
    echo 'Enabled: ' . (\$service->isEnabled() ? 'Yes' : 'No') . PHP_EOL;
    echo 'Site Key: ' . (\$service->getSiteKey() ?: 'Missing') . PHP_EOL;
"

echo ""
echo "=== Test Complete ==="
echo "Next: Open browser and check login page"
```

Run:
```bash
chmod +x test-recaptcha.sh
./test-recaptcha.sh
```

---

## 📞 Still Not Working?

### Collect Debug Information

```bash
# 1. Environment
cat .env | grep RECAPTCHA

# 2. Config
php artisan tinker --execute="
    echo 'Site Key: ' . config('services.recaptcha.site_key') . PHP_EOL;
    echo 'Secret Key: ' . substr(config('services.recaptcha.secret_key'), 0, 20) . '...' . PHP_EOL;
"

# 3. Recent logs
tail -n 50 storage/logs/laravel.log | grep -i captcha

# 4. Browser console errors
# (Copy from browser DevTools console)

# 5. Network request
# (Copy failed request from Network tab)
```

### Contact Information

Provide debug info above to tech team with:
- PHP version: `php -v`
- Laravel version: `php artisan --version`
- Browser: Chrome/Firefox/Safari
- Screenshot of error

---

**Document Version:** 1.0  
**Last Updated:** January 9, 2026  
**Purpose:** Troubleshooting reCAPTCHA v3 implementation
