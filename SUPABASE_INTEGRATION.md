# Dokumentasi Integrasi Supabase

## Overview
Aplikasi ToDoList ini sekarang terhubung ke Supabase menggunakan REST API + cURL. Semua operasi database sekarang menggunakan Supabase sebagai backend.

## File-file yang berubah

### 1. **config/supabase.php** (BARU)
Kelas `SupabaseClient` yang menangani semua komunikasi dengan Supabase REST API.

**Fitur:**
- `insertKegiatan()` - Tambah kegiatan baru
- `updateStatusKegiatan()` - Update status kegiatan
- `getKegiatan()` - Ambil kegiatan dengan filter & pagination
- `countKegiatan()` - Hitung total kegiatan
- `getAllKegiatan()` - Ambil semua kegiatan

### 2. **.env** (BARU)
File konfigurasi yang menyimpan credentials Supabase:
- `SUPABASE_URL` - Project URL
- `SUPABASE_ANON_KEY` - Public/Anonymous key (untuk query publik)
- `SUPABASE_SERVICE_ROLE_KEY` - Service role key (untuk operasi admin)

**PENTING:** Jangan share `.env` ke public/repository!

### 3. **.env.php** (BARU)
Script helper untuk load variabel dari `.env` ke `$_ENV` dan `putenv()`.

### 4. **includes/function.php** (DIUPDATE)
Wrapper functions yang mempertahankan kompatibilitas dengan kode lama:
- `kegiatan()` - Sekarang menggunakan `$supabase->insertKegiatan()`
- `updateStatus()` - Sekarang menggunakan `$supabase->updateStatusKegiatan()`
- `getKegiatan()` - Sekarang menggunakan `$supabase->getKegiatan()`
- `countKegiatan()` - Sekarang menggunakan `$supabase->countKegiatan()`

**Compatibility Layer:**
- `SupabaseResult` class - Membuat result dari Supabase kompatibel dengan `mysqli_fetch_array()`
- Wrapper functions `mysqli_num_rows()` dan `mysqli_fetch_array()`

## Flow Aplikasi

### Tambah Kegiatan
```
list.php (form)
    ↓
proccess/tambah_kegiatan.php
    ↓
includes/function.php::kegiatan()
    ↓
config/supabase.php::SupabaseClient::insertKegiatan()
    ↓
Supabase REST API
    ↓
Database Supabase
```

### Update Status
```
index.php (form)
    ↓
proccess/update_status.php
    ↓
includes/function.php::updateStatus()
    ↓
config/supabase.php::SupabaseClient::updateStatusKegiatan()
    ↓
Supabase REST API
    ↓
Database Supabase
```

### Tampilkan Data
```
index.php
    ↓
includes/function.php::getKegiatan()
    ↓
config/supabase.php::SupabaseClient::getKegiatan()
    ↓
Supabase REST API
    ↓
Database Supabase
    ↓
SupabaseResult (wrap hasil)
    ↓
mysqli_fetch_array() compatibility
    ↓
Tampilkan di template
```

## Penjelasan Supabase REST API

### Endpoints yang digunakan

#### 1. INSERT (Tambah Data)
```
POST /rest/v1/kegiatan
Header: Authorization: Bearer <SERVICE_ROLE_KEY>
Body: {
  "nama_kegiatan": "...",
  "jenis_kegiatan": "...",
  "tanggal_kegiatan": "...",
  "status": "..."
}
```

#### 2. UPDATE (Ubah Data)
```
PATCH /rest/v1/kegiatan?id_kegiatan=eq.<ID>
Header: Authorization: Bearer <SERVICE_ROLE_KEY>
Body: {
  "status": "..."
}
```

#### 3. SELECT (Ambil Data)
```
GET /rest/v1/kegiatan?tanggal_kegiatan=eq.<TANGGAL>&order=tanggal_kegiatan.desc&limit=3&offset=0
Header: Authorization: Bearer <ANON_KEY>
```

#### 4. COUNT (Hitung Data)
```
GET /rest/v1/kegiatan?select=id_kegiatan&tanggal_kegiatan=eq.<TANGGAL>
Header: Authorization: Bearer <ANON_KEY>
```

## Konfigurasi & Security

### Keys Explanation
- **ANON_KEY** - Digunakan untuk operasi publik (SELECT). Row Level Security akan membatasi data yang bisa diakses.
- **SERVICE_ROLE_KEY** - Digunakan untuk operasi admin (INSERT, UPDATE, DELETE). Bypass RLS policy.

### Row Level Security (RLS)
Di Supabase, RLS policies sudah dikonfigurasi di setup untuk memungkinkan semua operasi.

Jika ingin lebih aman:
```sql
-- Hanya admin yang bisa INSERT/UPDATE/DELETE
CREATE POLICY "Only admins can insert" ON kegiatan
  FOR INSERT TO authenticated
  WITH CHECK (auth.jwt() ->> 'email' = 'admin@example.com');
```

## Troubleshooting

### Masalah: "CORS Error"
**Solusi:** Pastikan Supabase Project Setting > Security > CORS sudah dikonfigurasi atau disable CORS restrictions.

### Masalah: "401 Unauthorized"
**Solusi:** Periksa API key di `.env`. Pastikan key sudah benar.

### Masalah: "Permission Denied"
**Solusi:** Periksa RLS policies di Supabase. Pastikan policy memungkinkan operasi yang diinginkan.

### Masalah: "Duplicate entry"
**Solusi:** Nama kegiatan harus unik. Gunakan nama yang berbeda.

## Testing

### Test Insert
Buka `list.php`, isi form, klik kirim. Periksa apakah data muncul di Supabase.

### Test Update Status
Buka `index.php`, klik tombol "Selesai" atau "Tidak Selesai". Periksa status berubah di Supabase.

### Test Get Data
Buka `index.php`, ganti filter tanggal. Pastikan data terupdate.

## Next Steps (Optional)
- Tambah authentication (Supabase Auth)
- Tambah delete kegiatan
- Tambah edit kegiatan
- Tambah real-time updates dengan Supabase Realtime
