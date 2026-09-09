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

### US-01 — Peserta Didik

> Sebagai peserta didik, saya ingin melakukan absensi secara digital melalui sistem, sehingga kehadiran saya dapat tercatat tanpa menggunakan daftar hadir manual.

- [ ] AC-1: Peserta didik dapat masuk ke sistem menggunakan akun yang telah terdaftar.
- [ ] AC-2: Peserta didik dapat memilih kegiatan pembelajaran yang sedang diikuti dan melakukan absensi.
- [ ] AC-3: Setelah absensi berhasil, sistem menyimpan data kehadiran dan menampilkan informasi bahwa absensi berhasil dilakukan.
- [ ] AC-4: Peserta didik tidak dapat melakukan absensi lebih dari satu kali pada kegiatan pembelajaran yang sama.
- [ ] AC-5: Peserta didik dapat melihat riwayat absensi pribadi yang telah dilakukan.

### US-02 — Pengajar/Tutor

> Sebagai pengajar/tutor, saya ingin melihat data kehadiran peserta didik, sehingga saya dapat memantau kehadiran peserta didik pada kegiatan pembelajaran yang saya ampu.

- [ ] AC-1: Pengajar/tutor dapat masuk ke sistem menggunakan akun dengan hak akses pengajar.
- [ ] AC-2: Pengajar/tutor dapat melihat daftar peserta didik beserta status kehadirannya.
- [ ] AC-3: Data kehadiran menampilkan informasi yang diperlukan, seperti nama peserta didik, tanggal, dan status kehadiran.
- [ ] AC-4: Pengajar/tutor hanya dapat melihat data absensi yang sesuai dengan kegiatan pembelajaran yang diampunya.

### US-03 — Admin/Pengelola PKBM

> Sebagai admin/pengelola PKBM, saya ingin mengelola data peserta didik dan melihat rekapitulasi absensi, sehingga data peserta didik dan kehadiran dapat dikelola secara terorganisir.

- [ ] AC-1: Admin/pengelola dapat menambahkan, melihat, mengubah, dan menghapus data peserta didik.
- [ ] AC-2: Admin/pengelola dapat melihat data absensi peserta didik yang tersimpan di dalam sistem.
- [ ] AC-3: Admin/pengelola dapat melihat rekapitulasi absensi berdasarkan periode tertentu.
- [ ] AC-4: Data yang ditampilkan dalam rekapitulasi sesuai dengan data absensi yang tersimpan di dalam sistem.

---

## 6. Narrative Use Case

### UC-01 — Pencatatan Absensi Peserta Didik

**Nama Use Case:** Pencatatan Absensi

**Kode:** UC-01

**Aktor Utama:** Peserta Didik

**Tujuan:** Memungkinkan peserta didik mencatat kehadiran pada kegiatan pembelajaran secara digital.

**Prasyarat:**
1. Peserta didik telah memiliki akun yang terdaftar pada sistem.
2. Peserta didik telah berhasil melakukan login.
3. Peserta didik memiliki kegiatan pembelajaran yang dapat diakses untuk melakukan absensi.

**Alur Utama:**
1. Peserta didik membuka menu Absensi.
2. Sistem menampilkan kegiatan pembelajaran yang tersedia untuk peserta didik.
3. Peserta didik memilih kegiatan pembelajaran yang sedang diikuti.
4. Sistem menampilkan informasi kegiatan pembelajaran dan pilihan untuk melakukan absensi.
5. Peserta didik melakukan konfirmasi kehadiran.
6. Sistem memeriksa data absensi peserta didik pada kegiatan yang dipilih.
7. Sistem memastikan peserta didik belum melakukan absensi pada kegiatan tersebut.
8. Sistem mencatat data kehadiran peserta didik ke dalam sistem.
9. Sistem menampilkan pemberitahuan bahwa absensi berhasil dilakukan.
10. Data absensi yang berhasil dicatat dapat dilihat pada riwayat absensi pribadi peserta didik.

**Alur Alternatif:**

**A. Peserta didik telah melakukan absensi**
1. Peserta didik memilih kegiatan pembelajaran.
2. Sistem memeriksa data absensi peserta didik.
3. Sistem menemukan bahwa peserta didik telah melakukan absensi pada kegiatan tersebut.
4. Sistem tidak mencatat absensi kembali.
5. Sistem menampilkan pemberitahuan bahwa peserta didik telah melakukan absensi.

**B. Terjadi kegagalan dalam penyimpanan data**
1. Peserta didik melakukan konfirmasi kehadiran.
2. Sistem mengalami kegagalan dalam menyimpan data absensi.
3. Sistem tidak mencatat absensi sebagai kehadiran yang berhasil.
4. Sistem menampilkan pemberitahuan bahwa absensi gagal disimpan.
5. Peserta didik dapat mencoba melakukan absensi kembali.

**Kondisi Akhir:**

Jika proses berhasil, data kehadiran peserta didik tersimpan dalam sistem dan dapat dilihat kembali melalui riwayat absensi. Data tersebut juga dapat digunakan oleh pengajar/tutor untuk memantau kehadiran serta oleh admin/pengelola PKBM untuk melakukan rekapitulasi absensi.
