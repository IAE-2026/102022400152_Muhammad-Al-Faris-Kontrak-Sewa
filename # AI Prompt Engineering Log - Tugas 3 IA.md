# AI Prompt Engineering Log - Tugas 3 IAE

## Identitas

Nama Service: Kontrak Sewa Service
Pemilik Service: Muhammad Al Faris
NIM: 102022400152
Kelompok: 8
API Key: KEY-MHS-200
Akun Warga: [warga31@ktp.iae.id](mailto:warga31@ktp.iae.id)
Framework: Laravel
Transaksi Kritis: ContractApproved / Persetujuan Kontrak Sewa

---

## Tujuan Penggunaan AI

AI digunakan sebagai alat bantu eksplorasi teknis dalam mengerjakan Tugas 3 Integrasi Aplikasi Enterprise. Bantuan AI digunakan untuk memahami kebutuhan tugas, menentukan transaksi kritis, menyusun alur integrasi, memperbaiki error implementasi Laravel, serta membuat dokumentasi analisis dan sequence diagram.

AI tidak digunakan untuk menggantikan proses pengerjaan secara penuh, tetapi digunakan sebagai pendamping untuk mencari solusi teknis dan mengecek kesesuaian implementasi dengan kebutuhan tugas.

---

## Log Prompt 1 - Memahami Kebutuhan Tugas 3

**Prompt:**

Saya ada Tugas 3 IAE. Tolong bantu pahami apa saja yang harus dikerjakan dari file tugas dosen. Fokusnya pada SSO, SOAP, RabbitMQ, analisis_tugas_3.md, dan prompt log AI.

**Hasil Bantuan AI:**

AI menjelaskan bahwa Tugas 3 membutuhkan integrasi service individu dengan tiga komponen utama, yaitu Federated SSO, SOAP XML Audit, dan RabbitMQ Publisher. AI juga menjelaskan bahwa repository individu perlu memiliki dokumen analisis_tugas_3.md dan rekap prompt AI.

**Tindak Lanjut:**

Saya memutuskan untuk fokus terlebih dahulu pada implementasi teknis SSO, SOAP, dan RabbitMQ sebelum membuat dokumen analisis.

---

## Log Prompt 2 - Menentukan Transaksi Kritis

**Prompt:**

Service saya adalah Kontrak Sewa. Dari proses bisnis kelompok, bagian saya adalah penyewa menyetujui kontrak penyewaan. Apakah transaksi approve kontrak cocok dijadikan transaksi kritis untuk Tugas 3?

**Hasil Bantuan AI:**

AI menyarankan transaksi `Penyewa Menyetujui Kontrak Penyewaan` sebagai transaksi kritis karena transaksi ini mengubah status kontrak dari draft menjadi signed. Transaksi ini juga termasuk state-changing transaction karena mengubah data pada database lokal.

**Tindak Lanjut:**

Saya memilih endpoint berikut sebagai transaksi kritis:

```http
POST /api/contracts/{id}/approve
```

---

## Log Prompt 3 - Menyusun Environment Integrasi IAE

**Prompt:**

Akun IAE saya adalah [warga31@ktp.iae.id](mailto:warga31@ktp.iae.id) dan API key saya KEY-MHS-200. Tolong bantu susun konfigurasi .env untuk koneksi ke SSO, SOAP, dan RabbitMQ dosen.

**Hasil Bantuan AI:**

AI memberikan konfigurasi `.env` untuk menyimpan URL pusat, API key, team ID, akun warga, dan nama service.

**Tindak Lanjut:**

Saya menambahkan konfigurasi berikut ke file `.env`:

```env
IAE_BASE_URL=https://iae-sso.virtualfri.id
IAE_API_KEY=KEY-MHS-200
IAE_TEAM_ID=TEAM-08
IAE_WARGA_EMAIL=warga31@ktp.iae.id
IAE_WARGA_PASSWORD=KtpDigital2026!
IAE_SERVICE_NAME=kontrak-sewa-service
```

---

## Log Prompt 4 - Membuat Service SSO Laravel

**Prompt:**

Tolong bantu buat service Laravel untuk mengambil token dari SSO dosen dan login warga menggunakan endpoint /api/v1/auth/token.

**Hasil Bantuan AI:**

AI memberikan struktur file `app/Services/IaeSsoService.php` yang berisi method untuk mengambil machine token menggunakan API key dan login warga menggunakan email serta password.

**Tindak Lanjut:**

Saya membuat folder `app/Services` secara manual karena belum tersedia di project Laravel, lalu menambahkan file `IaeSsoService.php`.

---

## Log Prompt 5 - Membuat SOAP XML Client

**Prompt:**

Tolong buatkan service SOAP client Laravel untuk mengirim audit transaksi kontrak ke endpoint /soap/v1/audit dengan format XML Envelope.

**Hasil Bantuan AI:**

AI memberikan contoh service `IaeSoapAuditService.php` yang mengubah payload JSON menjadi format SOAP XML Envelope. XML tersebut berisi `TeamID`, `ActivityName`, dan `LogContent` dalam bentuk CDATA.

**Tindak Lanjut:**

Saya menambahkan file `IaeSoapAuditService.php` dan mengatur agar response SOAP mengambil `ReceiptNumber`.

---

## Log Prompt 6 - Membuat RabbitMQ Publisher

**Prompt:**

Tolong bantu buat service Laravel untuk publish event ContractApproved ke RabbitMQ dosen melalui endpoint /api/v1/messages/publish.

**Hasil Bantuan AI:**

AI memberikan contoh service `IaeMessagePublisherService.php` yang mengirim event JSON ke exchange `iae.central.exchange` dengan routing key `rental.contract.approved`.

**Tindak Lanjut:**

Saya menambahkan file `IaeMessagePublisherService.php` dan memakai event name `ContractApproved`.

---

## Log Prompt 7 - Membuat Route dan Controller SSO

**Prompt:**

Saya butuh endpoint lokal untuk mengetes login SSO dari Postman. Tolong bantu buat SsoController dan route /api/sso/login.

**Hasil Bantuan AI:**

AI memberikan contoh `SsoController` dengan method `login()` yang menerima email dan password, lalu memanggil service SSO. AI juga menjelaskan bahwa payload JWT dapat disimpan ke tabel role lokal.

**Tindak Lanjut:**

Saya membuat controller SSO dan route:

```php
Route::post('/sso/login', [SsoController::class, 'login']);
```

---

## Log Prompt 8 - Menambahkan Endpoint Approve Kontrak

**Prompt:**

Tolong bantu tambahkan method approve pada ContractController agar ketika kontrak disetujui, sistem menjalankan SSO token, SOAP audit, dan RabbitMQ publish.

**Hasil Bantuan AI:**

AI memberikan method `approve()` yang menjalankan alur berikut:

1. Mencari kontrak berdasarkan ID.
2. Mengambil token dari SSO.
3. Mengubah status kontrak menjadi signed.
4. Membuat payload `ContractApproved`.
5. Mengirim SOAP audit.
6. Menyimpan ReceiptNumber.
7. Publish event ke RabbitMQ.
8. Menyimpan status publish.
9. Mengembalikan response sukses ke Postman.

**Tindak Lanjut:**

Saya menambahkan route:

```php
Route::post('/contracts/{id}/approve', [ContractController::class, 'approve']);
```

---

## Log Prompt 9 - Memperbaiki Error Artisan

**Prompt:**

Saat menjalankan `php artisan make:migration`, muncul error `Could not open input file: artisan`. Kenapa dan bagaimana solusinya?

**Hasil Bantuan AI:**

AI menjelaskan bahwa error terjadi karena terminal belum berada di folder root Laravel yang memiliki file `artisan`.

**Tindak Lanjut:**

Saya masuk ke folder Laravel yang benar:

```powershell
cd C:\Users\lenovo\102022400152-MUHAMMAD-AL-FARIS-Kontrak-Sewa\temp
```

Setelah itu perintah `php artisan` dapat dijalankan.

---

## Log Prompt 10 - Memperbaiki Error Duplicate Route Import

**Prompt:**

Saat menjalankan Laravel muncul error `Cannot use App\Http\Controllers\Api\ContractController as ContractController because the name is already in use`.

**Hasil Bantuan AI:**

AI menjelaskan bahwa `ContractController` di-import dua kali di file `routes/api.php`.

**Tindak Lanjut:**

Saya menghapus salah satu baris import yang duplikat sehingga hanya tersisa satu:

```php
use App\Http\Controllers\Api\ContractController;
```

---

## Log Prompt 11 - Memperbaiki Error Class ContractController Ganda

**Prompt:**

Saat test endpoint muncul error `Cannot declare class App\Http\Controllers\Api\ContractController, because the name is already in use`. Tolong perbaiki codingan ContractController saya.

**Hasil Bantuan AI:**

AI menjelaskan bahwa dalam satu file terdapat dua class dengan nama `ContractController`. AI kemudian membantu menyusun ulang controller agar hanya memiliki satu class, dan semua method seperti `index`, `store`, `show`, `update`, `destroy`, dan `approve` berada di dalam class yang sama.

**Tindak Lanjut:**

Saya mengganti isi file `ContractController.php` agar struktur class menjadi benar dan tidak duplikat.

---

## Log Prompt 12 - Pengujian Postman

**Prompt:**

Apa saja yang harus saya test di Postman untuk memastikan SSO, SOAP, dan RabbitMQ berjalan?

**Hasil Bantuan AI:**

AI memberikan urutan pengujian Postman:

1. `POST /api/sso/login`
2. `GET /api/contracts`
3. `POST /api/contracts`
4. `POST /api/contracts/{id}/approve`

**Tindak Lanjut:**

Saya melakukan pengujian endpoint approve kontrak menggunakan Postman.

---

## Log Prompt 13 - Verifikasi Hasil Integrasi

**Prompt:**

Saya mendapat response 200 OK dengan message `Kontrak berhasil disetujui, SOAP audit terkirim, dan event RabbitMQ berhasil dipublish`. Apakah sudah berhasil?

**Hasil Bantuan AI:**

AI menjelaskan bahwa integrasi sudah berhasil karena response memiliki status success, terdapat `soap_receipt_number`, dan `publish_result` menunjukkan status success pada exchange `iae.central.exchange` dengan routing key `rental.contract.approved`.

**Tindak Lanjut:**

Saya mengecek RabbitMQ board dosen dan mencari event menggunakan routing key serta nama service. Event berhasil ditemukan pada dashboard RabbitMQ.

---

## Log Prompt 14 - Membuat Analisis Tugas 3 dan Sequence Diagram

**Prompt:**

Tolong buatkan analisis_tugas_3.md dan code sequence diagram Mermaid yang bisa dimasukkan ke draw.io untuk transaksi approve kontrak sewa.

**Hasil Bantuan AI:**

AI membantu menyusun dokumen analisis yang berisi identitas service, transaksi kritis, justifikasi, role lokal, alur integrasi SSO, SOAP, RabbitMQ, hasil pengujian, dan kesimpulan. AI juga memberikan kode Mermaid Sequence Diagram untuk dimasukkan ke draw.io.

**Tindak Lanjut:**

Saya membuat file `analisis_tugas_3.md` dan memasukkan sequence diagram ke draw.io.

---

## Ringkasan Hasil Implementasi

Berdasarkan proses pengerjaan dan pengujian, integrasi yang berhasil dilakukan adalah:

| Komponen           | Implementasi                                               | Status   |
| ------------------ | ---------------------------------------------------------- | -------- |
| Federated SSO      | Login/token melalui Cloud SSO Dosen                        | Berhasil |
| SOAP XML Client    | Mengirim audit ContractApproved dan menerima ReceiptNumber | Berhasil |
| RabbitMQ Publisher | Publish event ContractApproved ke iae.central.exchange     | Berhasil |
| Transaksi Kritis   | Approve kontrak sewa                                       | Berhasil |
| Dokumentasi        | analisis_tugas_3.md dan AI_PROMPT_LOG.md                   | Dibuat   |

---