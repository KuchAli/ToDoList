# ✅ Checklist Setup Supabase Integration

## Langkah-langkah yang sudah selesai:

### Files yang dibuat:
- ✅ `.env` - Menyimpan Supabase credentials
- ✅ `.env.php` - Load env variables
- ✅ `config/supabase.php` - Supabase REST API Client
- ✅ `includes/function.php` - Updated dengan Supabase integration
- ✅ `SUPABASE_SETUP.md` - Guide membuat table di Supabase
- ✅ `SUPABASE_INTEGRATION.md` - Dokumentasi lengkap

## Langkah-langkah yang perlu Anda lakukan:

### 1. Setup Table di Supabase ⚠️ PENTING
- [ ] Buka https://app.supabase.com
- [ ] Login ke project Anda
- [ ] Buka "SQL Editor"
- [ ] Copy query dari file `SUPABASE_SETUP.md`
- [ ] Jalankan query
- [ ] Verifikasi table `kegiatan` sudah dibuat

### 2. Test Aplikasi
- [ ] Buka aplikasi di browser (http://localhost/todolist/)
- [ ] Coba buka halaman "Tambah Kegiatan" (list.php)
- [ ] Isi form dan submit
- [ ] Cek apakah data muncul di Supabase (Table Editor)
- [ ] Coba filter data di halaman utama
- [ ] Coba update status kegiatan

### 3. Verifikasi di Supabase Dashboard
- [ ] Buka Table Editor
- [ ] Pilih table `kegiatan`
- [ ] Pastikan data yang Anda tambahkan muncul di table

## Struktur File Sekarang:

```
project/
├── .env                          ← NEW
├── .env.php                      ← NEW
├── config/
│   ├── database.php              (still exists, not used)
│   └── supabase.php              ← NEW
├── includes/
│   ├── function.php              ← UPDATED
│   └── header.php
├── proccess/
│   ├── tambah_kegiatan.php       (unchanged)
│   └── update_status.php         (unchanged)
├── index.php                     (unchanged)
├── list.php                      (unchanged)
├── SUPABASE_SETUP.md             ← NEW (setup guide)
├── SUPABASE_INTEGRATION.md       ← NEW (integration docs)
└── README.md
```

## Jika ada error:

### Error: "require_once failed to open stream"
- Pastikan path relatif benar
- Cek permissions folder

### Error: "CURL error"
- Pastikan PHP punya extension curl (biasanya sudah default)
- Check server error log

### Error: "401 Unauthorized"
- Verify API keys di `.env`
- Pastikan key tidak ada whitespace

### Error: "Table not found"
- Pastikan sudah jalankan SQL di Supabase
- Pastikan nama table `kegiatan` (lowercase)

## Support

Jika ada issue, check:
1. SUPABASE_SETUP.md - untuk setup table
2. SUPABASE_INTEGRATION.md - untuk troubleshooting
3. Config credentials di .env

Happy coding! 🚀
