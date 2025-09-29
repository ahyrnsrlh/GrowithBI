# Maps Real-Time Feature - GrowithBI

## Overview

Fitur Maps Real-Time memungkinkan admin untuk monitoring lokasi absensi peserta magang secara real-time menggunakan peta interaktif dengan Leaflet.js dan broadcasting events.

## Features

1. **Real-time Location Tracking**: Monitor lokasi check-in/check-out peserta secara langsung
2. **Interactive Map**: Peta interaktif dengan markers untuk setiap absensi
3. **Location Validation**: Validasi lokasi berdasarkan radius kantor (500m)
4. **Statistics Dashboard**: Statistik real-time jumlah hadir, terlambat, dan tidak hadir
5. **Live Updates**: Update otomatis tanpa refresh halaman menggunakan WebSocket

## File Structure

### Backend Files

```
app/
├── Events/
│   └── AttendanceUpdated.php              # Broadcasting event untuk real-time updates
├── Http/Controllers/
│   ├── Admin/AttendanceController.php     # Controller dengan maps() method dan API endpoint
│   └── Peserta/AttendanceController.php   # Updated dengan event firing
└── Providers/
    └── BroadcastServiceProvider.php       # Service provider untuk broadcasting

config/
└── broadcasting.php                       # Konfigurasi broadcasting

routes/
├── channels.php                          # Broadcasting channels authorization
└── web.php                              # Routes untuk maps feature
```

### Frontend Files

```
resources/js/
├── Pages/
│   └── Admin/Maps/Index.vue              # Main maps interface component
├── Layouts/
│   └── AdminLayout.vue                   # Updated navigation dengan Maps menu
└── bootstrap.js                          # Echo configuration
```

## Technical Details

### Broadcasting Configuration

-   **Driver**: Pusher (untuk production) / Log (untuk development)
-   **Channel**: `admin-maps` (hanya admin yang bisa access)
-   **Event**: `AttendanceUpdated` dengan data attendance lengkap

### Map Configuration

-   **Library**: Leaflet.js
-   **Center**: Bank Indonesia office location
-   **Radius**: 500m untuk validasi lokasi
-   **Markers**: Green (valid location), Red (invalid location), Blue (office)

### Real-time Updates

-   **Event Listener**: Mendengarkan `AttendanceUpdated` events
-   **Auto-refresh**: Polling setiap 30 detik sebagai fallback
-   **Data Sync**: Update markers dan statistik secara real-time

## API Endpoints

### GET /admin/attendance/maps

Menampilkan halaman Maps dashboard

### GET /admin/attendance/locations/api

API endpoint untuk data lokasi absensi

```json
{
    "attendances": [
        {
            "id": 1,
            "user_name": "John Doe",
            "division": "IT",
            "check_in_time": "08:00:00",
            "status": "On-Time",
            "latitude": -6.2,
            "longitude": 106.816666,
            "is_valid_location": true
        }
    ],
    "stats": {
        "total": 10,
        "on_time": 7,
        "late": 2,
        "absent": 1
    },
    "office_location": {
        "name": "Bank Indonesia",
        "latitude": -6.2,
        "longitude": 106.816666,
        "radius": 500
    }
}
```

## Setup Instructions

### 1. Install Dependencies

```bash
# Backend - install Pusher PHP SDK (optional untuk production)
composer require pusher/pusher-php-server

# Frontend - install JavaScript packages
npm install laravel-echo pusher-js
```

### 2. Configure Broadcasting

Update `.env` file:

```env
# For development (logs events)
BROADCAST_CONNECTION=log

# For production (requires Pusher account)
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster

# Vite variables
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 3. Enable WebSocket (Production)

Uncomment di `resources/js/bootstrap.js`:

```javascript
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost:
        import.meta.env.VITE_PUSHER_HOST ||
        `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusherapp.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT || 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME || "https") === "https",
    enabledTransports: ["ws", "wss"],
});
```

### 4. Build Assets

```bash
npm run build
# atau untuk development
npm run dev
```

## Usage

### Access Maps Dashboard

1. Login sebagai Admin
2. Navigasi ke "Dashboard" > "Attendance" > "Maps"
3. Peta akan menampilkan lokasi absensi hari ini
4. Markers akan update secara real-time saat ada absensi baru

### Real-time Monitoring

-   Green markers: Absensi di lokasi valid
-   Red markers: Absensi di luar radius kantor
-   Blue marker: Lokasi kantor Bank Indonesia
-   Circle: Radius valid 500m dari kantor

## Troubleshooting

### WebSocket Not Working

-   Pastikan Pusher credentials benar di `.env`
-   Check browser console untuk error
-   Fallback ke polling jika WebSocket gagal

### Map Not Loading

-   Check Leaflet.js CDN connection
-   Pastikan koordinat office location benar
-   Check browser geolocation permissions

### Broadcasting Issues

-   Check log di `storage/logs/laravel.log`
-   Pastikan BroadcastServiceProvider terdaftar
-   Verify channels.php authorization

## Security Notes

-   Channel `admin-maps` hanya bisa diakses admin
-   Validasi lokasi server-side dengan Haversine formula
-   CSRF protection pada semua API endpoints
-   Authorization middleware pada routes

## Performance Considerations

-   Polling fallback limited ke 30 detik
-   Map markers dibatasi data hari ini saja
-   Lazy loading untuk komponen peta
-   Efficient re-rendering hanya untuk data yang berubah

## Future Enhancements

1. **Historical Data**: View attendance maps untuk tanggal sebelumnya
2. **Geofencing**: Multiple office locations support
3. **Notifications**: Toast notifications untuk real-time updates
4. **Export**: Export attendance locations ke PDF/Excel
5. **Analytics**: Heat maps dan attendance patterns
