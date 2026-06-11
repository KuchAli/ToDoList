# Setup Table Supabase

## Langkah-langkah membuat table di Supabase:

### 1. Login ke Supabase Dashboard
- Buka https://app.supabase.com
- Pilih project Anda

### 2. Buka SQL Editor
- Di sidebar kiri, pilih "SQL Editor"
- Klik "New Query"

### 3. Copy dan jalankan query ini:

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

-- Create index untuk faster queries
CREATE INDEX idx_tanggal_kegiatan ON kegiatan(tanggal_kegiatan);
CREATE INDEX idx_status ON kegiatan(status);

-- Enable RLS (Row Level Security)
ALTER TABLE kegiatan ENABLE ROW LEVEL SECURITY;

-- Policy untuk SELECT (semua user bisa baca)
CREATE POLICY "Enable read access for all users" ON kegiatan
  FOR SELECT USING (true);

-- Policy untuk INSERT (semua user bisa insert)
CREATE POLICY "Enable insert for all users" ON kegiatan
  FOR INSERT WITH CHECK (true);

-- Policy untuk UPDATE (semua user bisa update)
CREATE POLICY "Enable update for all users" ON kegiatan
  FOR UPDATE USING (true) WITH CHECK (true);

-- Policy untuk DELETE (semua user bisa delete)
CREATE POLICY "Enable delete for all users" ON kegiatan
  FOR DELETE USING (true);
```

### 4. Klik "Run" untuk menjalankan query

### 5. Verifikasi Table
- Di sidebar, pilih "Database" > "Tables"
- Pastikan table `kegiatan` sudah muncul dengan struktur yang benar

## Struktur Table:
- **id_kegiatan**: Primary key, auto increment
- **nama_kegiatan**: Nama kegiatan, UNIQUE
- **jenis_kegiatan**: Tipe kegiatan (olahraga, belajar, hobi)
- **tanggal_kegiatan**: Tanggal kegiatan
- **status**: Status (proses, selesai, tidak_selesai)
- **created_at**: Waktu dibuat (otomatis)
- **updated_at**: Waktu diubah (otomatis)

## Row Level Security (RLS)
RLS diaktifkan untuk keamanan. Policy di atas memungkinkan semua user untuk:
- Membaca (SELECT)
- Menambah (INSERT)
- Mengubah (UPDATE)
- Menghapus (DELETE)

Jika ingin lebih ketat di kemudian hari, bisa diupdate policynya.
