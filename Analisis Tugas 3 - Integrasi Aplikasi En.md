Analisis Tugas 3 - Integrasi Aplikasi Enterprise
Identitas Service

Nama Service: Kontrak Sewa Service
Pemilik Service: Muhammad Al Faris
NIM: 102022400152
Kelompok: 8
API Key: KEY-MHS-200
Akun Warga: warga31@ktp.iae.id
Framework: Laravel
Nama Transaksi Kritis: ContractApproved / Persetujuan Kontrak Sewa

1. Latar Belakang Service

Kontrak Sewa Service merupakan layanan individu yang berperan dalam proses pengelolaan kontrak penyewaan properti. Service ini menangani proses pembuatan kontrak, menampilkan data kontrak, memperbarui status kontrak, menghapus kontrak, serta menjalankan proses persetujuan kontrak sewa oleh penyewa.

Dalam proses bisnis rental properti, kontrak sewa menjadi dokumen penting karena menjadi bukti bahwa penyewa telah menyetujui ketentuan penyewaan. Setelah kontrak disetujui, status kontrak berubah menjadi signed dan aktivitas tersebut harus tercatat pada sistem pusat.

2. Transaksi Kritis yang Dipilih

Transaksi kritis yang dipilih adalah:

Penyewa Menyetujui Kontrak Penyewaan

Endpoint lokal yang digunakan:

POST /api/contracts/{id}/approve

Contoh endpoint pengujian:

POST http://127.0.0.1:8000/api/contracts/4/approve

Contoh request body:

{
  "sso_email": "warga31@ktp.iae.id",
  "tenant_id": "TEN-031",
  "property_id": "PROP-001"
}
3. Justifikasi Transaksi Kritis

Transaksi persetujuan kontrak sewa dikategorikan sebagai transaksi kritis karena transaksi ini mengubah status utama kontrak dari draft menjadi signed. Perubahan status ini menunjukkan bahwa penyewa telah menyetujui isi kontrak dan proses penyewaan dapat dilanjutkan.

Transaksi ini termasuk state-changing transaction karena terdapat perubahan data permanen pada database lokal, yaitu:

Status kontrak berubah menjadi signed.
Email SSO penyewa disimpan pada kontrak.
Waktu persetujuan kontrak disimpan pada field approved_at.
ReceiptNumber dari SOAP audit disimpan pada kontrak.
Status publish event ke sistem pusat disimpan sebagai published.

Transaksi ini juga penting karena berkaitan langsung dengan proses legal dan administratif penyewaan properti. Jika transaksi ini tidak tercatat dengan benar, maka sistem tidak memiliki bukti bahwa kontrak telah disetujui oleh penyewa.

4. Role Lokal yang Digunakan

Role lokal yang digunakan pada service ini adalah:

Role	Deskripsi
tenant	Penyewa yang melakukan persetujuan kontrak
admin	Pihak pengelola yang dapat melihat dan mengelola data kontrak

Pada implementasi SSO, payload JWT dari Cloud Dosen dipetakan ke tabel local_roles. Email warga yang digunakan adalah warga31@ktp.iae.id. Setelah login SSO berhasil, data user disimpan atau diperbarui di database lokal.

5. Alur Integrasi Sistem Pusat

Pada transaksi approve kontrak, sistem menjalankan tiga proses integrasi utama:

5.1 Federated SSO

Service melakukan login atau mengambil token dari Cloud SSO Dosen melalui endpoint:

POST https://iae-sso.virtualfri.id/api/v1/auth/token

Token digunakan sebagai bearer token untuk mengakses layanan pusat berikutnya, yaitu SOAP Audit dan Message Publisher.

5.2 SOAP XML Audit

Setelah kontrak berhasil disetujui, sistem mengirim data transaksi ke layanan SOAP Audit Dosen melalui endpoint:

POST https://iae-sso.virtualfri.id/soap/v1/audit

Data JSON transaksi dikonversi menjadi XML Envelope dengan struktur SOAP. Isi utama yang dikirimkan adalah TeamID, ActivityName, dan LogContent.

ActivityName yang digunakan:

ContractApproved

Contoh ReceiptNumber yang diterima dari hasil pengujian:

IAE-LOG-2026-172D4A17

ReceiptNumber ini disimpan pada field soap_receipt_number di tabel contracts dan juga dicatat pada tabel central_integration_logs.

5.3 RabbitMQ / Message Publisher

Setelah SOAP audit berhasil, sistem mengirim event notification ke message broker dosen melalui endpoint:

POST https://iae-sso.virtualfri.id/api/v1/messages/publish

Routing key yang digunakan:

rental.contract.approved

Exchange yang digunakan:

iae.central.exchange

Event yang dikirim:

ContractApproved

Event ini digunakan agar aktivitas bisnis persetujuan kontrak dapat diketahui oleh sistem pusat atau departemen lain secara asinkron.

6. Data yang Dikirim ke Sistem Pusat

Payload yang dikirim ke sistem pusat berisi informasi utama dari transaksi kontrak, yaitu:

{
  "contract_id": 4,
  "contract_number": "CTR-1781273981",
  "activity": "ContractApproved",
  "tenant_id": "TEN-031",
  "property_id": "PROP-001",
  "approved_by": "warga31@ktp.iae.id",
  "status": "signed",
  "service": "kontrak-sewa-service"
}

Payload tersebut digunakan pada dua proses, yaitu SOAP Audit dan RabbitMQ Publisher.

7. Hasil Pengujian

Pengujian dilakukan menggunakan Postman pada endpoint:

POST http://127.0.0.1:8000/api/contracts/4/approve

Hasil pengujian menunjukkan bahwa transaksi berhasil dijalankan dengan status 200 OK.

Response yang diterima:

{
  "status": "success",
  "message": "Kontrak berhasil disetujui, SOAP audit terkirim, dan event RabbitMQ berhasil dipublish.",
  "data": {
    "soap_receipt_number": "IAE-LOG-2026-172D4A17",
    "publish_result": {
      "status": "success",
      "exchange": "iae.central.exchange",
      "routing_key": "rental.contract.approved"
    }
  }
}

Berdasarkan hasil tersebut, dapat disimpulkan bahwa:

Proses approve kontrak berhasil dijalankan.
Status kontrak berhasil berubah menjadi signed.
SOAP audit berhasil dikirim ke sistem pusat.
ReceiptNumber berhasil diterima dan disimpan.
Event RabbitMQ berhasil dipublish ke exchange iae.central.exchange.
Routing key rental.contract.approved berhasil digunakan.
![Sequence Diagram](images/sequence_diagram.png)