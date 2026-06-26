# Kontrak Sewa Service

Service API untuk mengelola data kontrak sewa properti pada proses bisnis penyewaan.

## Identitas Service

* Nama service: Kontrak Sewa Service
* Nama: Muhammad Al Faris
* NIM: 102022400152
* Base URL: `http://localhost:8000`

## Teknologi

* Laravel
* PHP 8.2
* SQLite
* Docker
* Swagger / OpenAPI
* GraphQL Lighthouse

## Menjalankan Project

### 1. Clone repository

```bash
git clone https://github.com/IAE-2026/102022400152_Muhammad-Al-Faris-Kontrak-Sewa.git
cd 102022400152_Muhammad-Al-Faris-Kontrak-Sewa
```

### 2. Siapkan Laravel

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Buat file database SQLite:

```powershell
New-Item -ItemType File -Path database\database.sqlite -Force
```

Lalu jalankan migrasi:

```bash
php artisan migrate
```

### 3. Jalankan menggunakan Docker

```bash
docker compose up -d --build
```

Jika database di container masih kosong, jalankan:

```bash
docker compose exec app sh -lc "touch database/database.sqlite && php artisan migrate"
```

Cek container:

```bash
docker compose ps
```

## API Key

Semua endpoint REST dan GraphQL wajib memakai header berikut:

```text
X-IAE-KEY: 102022400152
```

Jika header tidak dikirim atau nilainya salah, sistem akan mengembalikan response:

```json
{
  "status": "error",
  "message": "Unauthorized. X-IAE-KEY is missing or invalid.",
  "errors": null
}
```

## REST API Endpoint

| Method | Endpoint                         | Keterangan                                |
| ------ | -------------------------------- | ----------------------------------------- |
| GET    | `/api/v1/contracts`              | Menampilkan seluruh data kontrak          |
| GET    | `/api/v1/contracts/{id}`         | Menampilkan detail kontrak berdasarkan ID |
| POST   | `/api/v1/contracts`              | Membuat data kontrak contoh               |
| PUT    | `/api/v1/contracts/{id}`         | Mengubah data kontrak                     |
| DELETE | `/api/v1/contracts/{id}`         | Menghapus data kontrak                    |
| POST   | `/api/v1/contracts/{id}/approve` | Menyetujui kontrak                        |
| POST   | `/api/v1/auth/login`             | Login service                             |

## Contoh Request REST API

### Mengambil seluruh kontrak

```bash
curl -X GET "http://localhost:8000/api/v1/contracts" ^
  -H "X-IAE-KEY: 102022400152"
```

### Membuat kontrak

```bash
curl -X POST "http://localhost:8000/api/v1/contracts" ^
  -H "X-IAE-KEY: 102022400152" ^
  -H "Content-Type: application/json"
```

### Mengambil detail kontrak

```bash
curl -X GET "http://localhost:8000/api/v1/contracts/1" ^
  -H "X-IAE-KEY: 102022400152"
```

## Format Response

### Response berhasil

```json
{
  "status": "success",
  "message": "Contract retrieved successfully",
  "data": {}
}
```

### Response data tidak ditemukan

```json
{
  "status": "error",
  "message": "Contract not found",
  "errors": null
}
```

## Swagger Documentation

Swagger dapat dibuka melalui:

```text
http://localhost:8000/api/documentation
```

Pada Swagger, klik tombol **Authorize**, lalu masukkan:

```text
102022400152
```

Swagger menyediakan pengujian endpoint GET, POST, dan GET detail contract.

## GraphQL

Endpoint GraphQL:

```text
http://localhost:8000/graphql
```

GraphQL juga wajib menggunakan header:

```text
X-IAE-KEY: 102022400152
```

Contoh query GraphQL:

```graphql
{
  contracts {
    id
    contract_number
    property_id
    tenant_id
    status
    monthly_rent
  }
}
```

Contoh test GraphQL melalui PowerShell:

```powershell
$body = @{ query = '{ contracts { id contract_number status } }' } | ConvertTo-Json -Compress

Invoke-RestMethod -Uri "http://localhost:8000/graphql" -Method Post `
  -ContentType "application/json" `
  -Headers @{ "X-IAE-KEY" = "102022400152" } `
  -Body $body
```

## Docker

Untuk menghentikan container:

```bash
docker compose down
```

Untuk menjalankan kembali:

```bash

## GraphQL Playground

GraphQL Playground dapat diakses melalui:

```text
http://localhost:8000/graphql-playground
```

Halaman ini digunakan untuk menjalankan query GraphQL melalui browser. Masukkan API key berikut pada field `X-IAE-KEY`:

```text
102022400152
```

Contoh query:

```graphql
{
  contracts {
    id
    contract_number
    property_id
    tenant_id
    status
    monthly_rent
  }
}
```

## Docker Quick Start

Untuk menjalankan project dari hasil clone repository menggunakan Docker:

```bash
docker compose up -d --build
```

Docker akan menyiapkan dependency Composer, file `.env`, database SQLite, migration, dan Swagger secara otomatis.

Akses aplikasi melalui:

```text
Swagger: http://localhost:8000/api/documentation
GraphQL Playground: http://localhost:8000/graphql-playground
```

docker compose up -d
```
