# Dokumen Kebutuhan Perangkat Lunak — Versi Awal
**Nama Proyek:** Sistem Aplikasi Absensi Digital pada PKBM "PEMUDA"
**Nama Kelompok:** Kelompok 3
| No | Nama Anggota | NIM | Peran |
|---:|---|---|---|
| 1 | Muhammad Nurul Mudzakkir | 2495114014 | Project Manager |
| 2 | Mochamad Basa | 2495114005 | Requirement Analyst |
| 3 | Bowie Al-Ghoffar | 2495114011 | Reviewer / QA |
---
## 1. Daftar Aktor & Peran

1. **Peserta Didik:** Melakukan absensi kehadiran dan melihat riwayat absensi pribadi.
2. **Pengajar/Tutor:** Memantau dan mengelola data absensi peserta didik pada kegiatan pembelajaran yang diampu.
3. **Admin/Pengelola PKBM:** Mengelola data peserta didik dan tutor, serta melihat dan merekap data absensi.

---

## 2. Kebutuhan Fungsional

- **F-01: Login Pengguna**  
  Sistem harus menyediakan fitur login yang memungkinkan peserta didik, pengajar/tutor, dan admin/pengelola PKBM masuk ke dalam sistem menggunakan akun yang telah terdaftar. Sistem harus membedakan hak akses setiap pengguna berdasarkan perannya.

- **F-02: Pengelolaan Data Peserta Didik**  
  Sistem harus menyediakan fitur bagi admin/pengelola PKBM untuk mengelola data peserta didik yang terdaftar di PKBM Pemuda. Admin/pengelola dapat menambahkan, melihat, mengubah, dan menghapus data peserta didik.

- **F-03: Pencatatan Absensi**  
  Sistem harus menyediakan fitur bagi peserta didik untuk melakukan pencatatan kehadiran pada kegiatan pembelajaran. Data absensi yang dilakukan oleh peserta didik harus tersimpan di dalam sistem dan dapat digunakan sebagai data kehadiran.

- **F-04: Pemantauan Data Absensi**  
  Sistem harus menyediakan fitur bagi pengajar/tutor untuk melihat dan memantau data kehadiran peserta didik pada kegiatan pembelajaran yang diampu.

- **F-05: Rekapitulasi Data Absensi**  
  Sistem harus menyediakan fitur bagi admin/pengelola PKBM untuk melihat dan melakukan rekapitulasi data absensi peserta didik berdasarkan periode tertentu.

---
## 3. Kebutuhan Nonfungsional

Kebutuhan nonfungsional menjelaskan kualitas dan karakteristik sistem yang harus dipenuhi agar aplikasi absensi dapat digunakan dengan baik.

### NFR-01: Performance

Sistem harus memberikan respons yang cepat ketika pengguna melakukan aktivitas pada aplikasi, seperti login, melakukan absensi, melihat data absensi, dan membuka rekapitulasi.

**Indikator terukur:**
- Waktu respons sistem maksimal 5 detik untuk setiap permintaan pada kondisi penggunaan normal.
- Proses penyimpanan data absensi maksimal 5 detik setelah pengguna melakukan absensi.

### NFR-02: Security

Sistem harus menjaga keamanan akun dan data absensi yang tersimpan. Setiap pengguna harus memiliki akses sesuai dengan perannya.

**Indikator terukur:**
- Sistem menyediakan login menggunakan username/email dan password.
- Sistem memiliki 3 tingkat hak akses, yaitu peserta didik, pengajar/tutor, dan admin/pengelola.
- Pengguna hanya dapat mengakses fitur sesuai dengan hak akses yang dimiliki.
- Password pengguna harus disimpan dalam bentuk hash dan tidak disimpan sebagai teks biasa.

### NFR-03: Usability

Sistem harus memiliki tampilan dan alur penggunaan yang sederhana sehingga dapat digunakan oleh pengguna tanpa membutuhkan pengetahuan teknis yang tinggi.

**Indikator terukur:**
- Pengguna dapat melakukan proses absensi melalui maksimal 3 langkah utama setelah berhasil login.
- Menu utama menampilkan fitur sesuai dengan peran pengguna.
- Sistem menampilkan pesan berhasil atau gagal setelah pengguna melakukan proses penting, seperti login dan absensi.
- Tampilan dapat digunakan pada komputer maupun perangkat mobile.

### NFR-04: Reliability

Sistem harus mampu menyimpan data absensi secara konsisten sehingga data yang telah berhasil dicatat tidak hilang atau berubah tanpa melalui proses yang sesuai.

**Indikator terukur:**
- Setiap absensi yang berhasil dilakukan harus tersimpan di database dan dapat ditampilkan kembali.
- Sistem memberikan notifikasi keberhasilan setelah data absensi berhasil disimpan.
- Sistem tidak boleh menyimpan data absensi ganda untuk peserta didik yang sama pada kegiatan pembelajaran yang sama.
- Data absensi yang sudah tersimpan dapat diakses kembali oleh pengguna yang memiliki hak akses.

---

## 5. User Story & Acceptance Criteria
### US-01
> Sebagai [Aktor], saya ingin [fitur], sehingga [manfaat].
- [ ] AC-1: [kriteria]
- [ ] AC-2: [kriteria]
- [ ] AC-3: [kriteria]
