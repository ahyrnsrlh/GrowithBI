# ✅ Testing reCAPTCHA v3 - Instruksi Lengkap

## 🎯 Langkah Testing

### 1️⃣ Pastikan Server Berjalan

```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Queue Worker (jika diperlukan)
php artisan queue:work

# Terminal 3: Reverb (jika diperlukan)
php artisan reverb:start
```

### 2️⃣ Buka Halaman Login

1. Buka browser (Chrome/Firefox recommended)
2. Tekan **F12** untuk buka DevTools
3. Navigasi ke tab **Console**
4. Kunjungi: `http://localhost:8000/login`

### 3️⃣ Yang Harus Terlihat di Console

**Saat halaman load, harusnya muncul:**

```
🔧 useRecaptcha mounted - enabled: true siteKey: 6Lfmx0QsAAAAAEDF5HBb0zpadAsp261K7UjBaDnG
🔐 reCAPTCHA Config: {siteKey: '6Lfmx0QsAAAAAEDF5HBb0zpadAsp261K7UjBaDnG', enabled: true}
✅ reCAPTCHA script loaded successfully
```

**✅ Jika muncul log di atas = reCAPTCHA sudah AKTIF!**

---

### 4️⃣ Test Login User Peserta

1. Di halaman login, isi:
   - **Email:** email peserta Anda
   - **Password:** password peserta

2. **Sebelum klik Login**, pastikan tab **Console** terbuka

3. Klik tombol **Login**

4. **Di Console harusnya muncul:**
   ```
   🚀 Submit triggered
   ✅ reCAPTCHA enabled, executing...
   📊 reCAPTCHA ready status: true
   🎫 reCAPTCHA token: ✅ Generated
   Token length: 500+ (angka panjang)
   📤 Form data: {email: "peserta@example.com", hasCaptchaToken: true}
   ```

5. **Di tab Network (F12 > Network):**
   - Cari request **POST /login**
   - Klik request tersebut
   - Lihat tab **Payload** atau **Request**
   - Harusnya ada field: `captcha_token: "03AGdBq2..."`

6. **Login harusnya BERHASIL** ✅

---

### 5️⃣ Test Login Admin (Should Skip Captcha)

1. Logout dari akun peserta
2. Kembali ke `/login`
3. Isi credentials **admin**
4. Klik Login

**Di Console harusnya muncul:**
```
🚀 Submit triggered
⚠️ reCAPTCHA skipped - enabled: true siteKey: 6Lfmx0QsAAAAAEDF5HBb0zpadAsp261K7UjBaDnG
📤 Form data: {email: "admin@example.com", hasCaptchaToken: false}
```

**Login admin tetap BERHASIL tanpa captcha** ✅

---

## 🐛 Troubleshooting

### Problem 1: Console tidak muncul log apapun

**Penyebab:** File JS belum ter-compile

**Solusi:**
```bash
npm run build
# atau
npm run dev
```

Refresh browser dengan **Ctrl + Shift + R** (hard reload)

---

### Problem 2: Console muncul error "grecaptcha is not defined"

**Penyebab:** Script Google belum load

**Cek di tab Network:**
- Filter: `recaptcha`
- Harusnya ada request ke: `https://www.google.com/recaptcha/api.js?render=...`
- Status harus: **200**

**Jika status 404 atau blocked:**
- Matikan ad blocker
- Test di Incognito mode
- Check internet connection

---

### Problem 3: Token null atau captcha_token: false

**Console log yang muncul:**
```
🎫 reCAPTCHA token: ❌ Failed
Token length: 0
```

**Solusi:**

1. **Cek site key valid:**
   ```bash
   php artisan tinker
   ```
   
   ```php
   config('services.recaptcha.site_key');
   // Harusnya: "6Lfmx0QsAAAAAEDF5HBb0zpadAsp261K7UjBaDnG"
   ```

2. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

3. **Restart server:**
   ```bash
   # Ctrl+C di terminal server
   php artisan serve
   ```

4. **Hard refresh browser:**
   - **Ctrl + Shift + R** (Windows/Linux)
   - **Cmd + Shift + R** (Mac)

---

### Problem 4: Login gagal dengan pesan "Login gagal. Silakan coba kembali."

**Penyebab:** Captcha verification failed di backend

**Cek Laravel logs:**
```bash
tail -f storage/logs/laravel.log
```

**Kemungkinan error dan solusi:**

#### Error: "Missing captcha token"
```
[warning] CaptchaVerificationService: Missing captcha token
```

**Solusi:** Frontend tidak mengirim token. Cek console browser.

---

#### Error: "Low captcha score"
```
[warning] CaptchaVerificationService: Low captcha score {"score":0.3}
```

**Solusi:** Score terlalu rendah. Ini normal jika:
- Testing berulang-ulang (Google detect bot)
- Menggunakan VPN
- Browser dalam automation mode

**Fix sementara untuk development:**

Edit `app/Services/CaptchaVerificationService.php`:
```php
protected const SCORE_THRESHOLD = 0.3; // Turunkan dari 0.5 ke 0.3 untuk testing
```

**PENTING:** Kembalikan ke 0.5 untuk production!

---

#### Error: "Google API unreachable"
```
[error] CaptchaVerificationService: Google API unreachable
```

**Solusi:** Check internet connection atau Google API down.

---

### Problem 5: Console log: "⚠️ reCAPTCHA disabled or no site key"

**Penyebab:** Props tidak dikirim ke frontend

**Solusi:**

1. **Cek controller mengirim props:**
   
   Edit `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
   
   Tambahkan sementara:
   ```php
   public function create(Request $request): Response
   {
       $captchaService = new \App\Services\CaptchaVerificationService();
       
       dd([
           'siteKey' => $captchaService->getSiteKey(),
           'enabled' => $captchaService->isEnabled(),
       ]);
       
       // ... rest of code
   }
   ```

   Buka `/login`, harusnya muncul dump dengan nilai yang benar.
   
   **Jangan lupa hapus dd() setelah test!**

2. **Clear all caches:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan config:cache
   ```

---

## ✅ Checklist Hasil Test

### Frontend
- [ ] Console muncul log "🔧 useRecaptcha mounted"
- [ ] Console muncul log "🔐 reCAPTCHA Config"
- [ ] Console muncul log "✅ reCAPTCHA script loaded successfully"
- [ ] Network tab menunjukkan request ke Google reCAPTCHA (status 200)
- [ ] Saat submit, console muncul "🎫 reCAPTCHA token: ✅ Generated"
- [ ] Token length > 500 characters

### Backend
- [ ] POST /login payload include `captcha_token`
- [ ] Login peserta BERHASIL dengan captcha
- [ ] Login admin BERHASIL tanpa captcha (skip)
- [ ] Tidak ada error di `storage/logs/laravel.log`

### Security
- [ ] Admin tidak perlu captcha (role-based bypass works)
- [ ] Peserta wajib captcha
- [ ] Tidak ada error di production logs

---

## 🧹 Setelah Testing Selesai

**Hapus debug logging:**

### 1. Edit `resources/js/Pages/Auth/Login.vue`

Hapus semua console.log yang ditambahkan:

```vue
<script setup>
// ... imports

const props = defineProps({
    // ... props
});

// HAPUS BARIS INI:
// console.log('🔐 reCAPTCHA Config:', {...});

const { executeRecaptcha, isReady: recaptchaReady } = useRecaptcha(
    props.recaptchaSiteKey,
    props.recaptchaEnabled
);

const form = useForm({
    email: "",
    password: "",
    remember: false,
    captcha_token: null,
});

const showPassword = ref(false);

const submit = async () => {
    // HAPUS SEMUA console.log DI SINI
    
    // Execute reCAPTCHA before submitting
    if (props.recaptchaEnabled && props.recaptchaSiteKey) {
        const token = await executeRecaptcha("login");
        form.captcha_token = token;
    }

    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
            form.captcha_token = null;
        },
    });
};
</script>
```

### 2. Edit `resources/js/Composables/useRecaptcha.js`

Hapus console.log di onMounted:

```javascript
onMounted(async () => {
    // HAPUS: console.log('🔧 useRecaptcha mounted...
    
    if (!enabled || !siteKey) {
        return;
    }

    isLoading.value = true;
    try {
        await loadScript();
        // HAPUS: console.log('✅ reCAPTCHA script loaded...
    } catch (err) {
        error.value = err.message;
        console.error("Failed to initialize reCAPTCHA:", err); // KEEP THIS
    } finally {
        isLoading.value = false;
    }
});
```

### 3. Rebuild frontend
```bash
npm run build
```

---

## 📊 Expected Results Summary

| Role | Captcha Required | Console Log | Request Payload | Result |
|------|------------------|-------------|-----------------|--------|
| **peserta** | ✅ Yes | "✅ Generated" | `captcha_token: "03AG..."` | Login Success |
| **admin** | ❌ No | "⚠️ skipped" | NO captcha_token | Login Success |
| **pembimbing** | ❌ No | "⚠️ skipped" | NO captcha_token | Login Success |

---

## 🎉 Success Indicators

### Anda BERHASIL jika:

1. ✅ Console browser menunjukkan reCAPTCHA loaded
2. ✅ Token captcha ter-generate (length > 500)
3. ✅ Peserta login berhasil dengan captcha
4. ✅ Admin login berhasil tanpa captcha
5. ✅ Tidak ada error di Laravel logs
6. ✅ Network tab menunjukkan captcha_token dikirim

---

## 📞 Jika Masih Bermasalah

Kirim informasi berikut:

1. **Screenshot console browser** (dengan log errors)
2. **Screenshot Network tab** (POST /login request)
3. **Laravel log** terakhir:
   ```bash
   tail -n 50 storage/logs/laravel.log | grep -i captcha
   ```
4. **Output command:**
   ```bash
   php artisan tinker --execute="\$s = new App\Services\CaptchaVerificationService(); var_dump(\$s->isEnabled(), \$s->getSiteKey());"
   ```

---

**Status:** Ready for Testing  
**Last Updated:** January 9, 2026  
**Remove Debug Logs:** After successful testing
