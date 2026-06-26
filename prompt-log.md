# Prompt Log

## Identitas Project

* Nama Service: Kontrak Sewa Service
* Nama: Muhammad Al Faris
* NIM: 102022400152
* Tanggal Pembaruan: 26 Juni 2026

## Penggunaan AI Assistant

AI Assistant digunakan sebagai pendamping untuk membantu memahami implementasi Laravel, REST API, middleware API key, Swagger, GraphQL, Docker, serta dokumentasi project. Seluruh kode dan hasil pengujian tetap dicek dan dijalankan secara manual pada project.

| No | Prompt / Bantuan yang Digunakan                                 | Hasil yang Digunakan                                                                   |
| -- | --------------------------------------------------------------- | -------------------------------------------------------------------------------------- |
| 1  | Membuat endpoint REST API untuk data kontrak sewa.              | Endpoint list contract, detail contract, dan create contract pada `/api/v1/contracts`. |
| 2  | Membuat middleware API key menggunakan header `X-IAE-KEY`.      | Dibuat `IaeKeyMiddleware` untuk memvalidasi API key `102022400152`.                    |
| 3  | Membuat format response JSON yang konsisten.                    | Response menggunakan `status`, `message`, `data`, dan `errors`.                        |
| 4  | Memperbaiki endpoint detail contract ketika ID tidak ditemukan. | Endpoint detail mengembalikan status `404` dengan pesan `Contract not found`.          |
| 5  | Menambahkan Swagger/OpenAPI ke Laravel.                         | Swagger tersedia pada `/api/documentation` dan dapat menguji endpoint API.             |
| 6  | Membuat query GraphQL untuk mengambil daftar contract.          | Dibuat query GraphQL `contracts` pada file `graphql/schema.graphql`.                   |
| 7  | Mengamankan endpoint GraphQL dengan API key.                    | Middleware `iae.key` ditambahkan pada konfigurasi Lighthouse.                          |
| 8  | Menjalankan Laravel menggunakan Docker Compose.                 | Aplikasi berjalan melalui container pada port `8000`.                                  |
| 9  | Melakukan pengujian REST API, Swagger, GraphQL, dan Docker.     | Semua fitur diuji manual sebelum project dipush ke GitHub.                             |

## Hasil Pengujian Manual

Pengujian yang sudah dilakukan:

* REST API tanpa header `X-IAE-KEY` mengembalikan status `401 Unauthorized`.
* Endpoint `GET /api/v1/contracts` mengembalikan status `200`.
* Endpoint `POST /api/v1/contracts` mengembalikan status `201 Created`.
* Endpoint `GET /api/v1/contracts/1` mengembalikan status `200`.
* Endpoint detail dengan ID yang tidak tersedia mengembalikan status `404`.
* Swagger dapat diakses melalui `/api/documentation`.
* Query GraphQL `contracts` berhasil menampilkan data contract.
* GraphQL tanpa API key mengembalikan status `401`.
* GraphQL dengan API key berhasil menampilkan data contract.
* Docker container berhasil berjalan pada port `8000`.
* Docker fresh build berhasil dijalankan dari Dockerfile dan aplikasi tetap dapat mengakses Swagger serta GraphQL Playground.

## Catatan

AI Assistant digunakan sebagai pendamping untuk memahami proses pengembangan dan pengecekan project. Implementasi kode, konfigurasi, pengujian endpoint, serta proses commit dan push dilakukan secara manual pada repository project.
