# Quick Start Guide - Supabase Integration

## Setup (5 menit)

### 1️⃣ Setup Table di Supabase
Buka Supabase SQL Editor dan jalankan query ini:

```sql
CREATE TABLE kegiatan (
  id_kegiatan BIGINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
  nama_kegiatan VARCHAR(255) NOT NULL UNIQUE,
  jenis_kegiatan VARCHAR(50) NOT NULL,
  tanggal_kegiatan DATE NOT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'proses',
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

ALTER TABLE kegiatan ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Enable all operations" ON kegiatan
  FOR ALL USING (true) WITH CHECK (true);

CREATE INDEX idx_tanggal_kegiatan ON kegiatan(tanggal_kegiatan);
CREATE INDEX idx_status ON kegiatan(status);
```

### 2️⃣ Check `.env` file
File `.env` sudah ada dengan credentials Anda. Jangan share dengan siapa pun!

### 3️⃣ Test aplikasi
- Buka http://localhost/todolist/list.php
- Coba tambah kegiatan
- Verifikasi di Supabase Table Editor

## Fitur yang tersedia

### ✅ Tambah Kegiatan
- Form di `list.php`
- Simpan ke Supabase otomatis
- Duplicate name akan error

### ✅ Lihat Kegiatan
- Filter by tanggal
- Pagination (3 per page)
- Update status ke "Selesai" atau "Tidak Selesai"

### ✅ Update Status
- Click tombol di kartu kegiatan
- Status otomatis update di Supabase

## Files Penting

| File | Fungsi |
|------|--------|
| `.env` | Credentials Supabase |
| `config/supabase.php` | REST API Client |
| `includes/function.php` | Business logic (updated) |
| `index.php` | Main page (updated) |
| `list.php` | Add activity form |

## Error Troubleshooting

**"Data tidak muncul"**
→ Check apakah table `kegiatan` sudah dibuat di Supabase

**"401 Unauthorized"**
→ Periksa API key di `.env`

**"Curl error"**
→ Enable PHP curl extension di server

**"Duplicate entry"**
→ Nama kegiatan harus unik, gunakan nama berbeda

## Support Docs

- `SUPABASE_SETUP.md` - Detail setup table
- `SUPABASE_INTEGRATION.md` - Technical documentation
- `CHECKLIST.md` - Step-by-step checklist

## Next (Optional)

Fitur yang bisa ditambahkan:
- [ ] Delete kegiatan
- [ ] Edit kegiatan
- [ ] Real-time updates dengan Supabase Realtime
- [ ] User authentication
- [ ] Export data

---

**Questions?** Check documentation files untuk detail lebih lanjut.

Happy coding! 🎉
