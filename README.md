# Kontrak Sewa Service

Service Kontrak Sewa untuk Tugas Integrasi Aplikasi Enterprise.

## Identitas

* Nama: Muhammad Al Faris
* NIM: 102022400152
* Kelompok: 8
* Framework: Laravel

## Fitur Utama

* Manajemen data kontrak sewa
* Federated SSO untuk autentikasi
* SOAP Audit setelah kontrak disetujui
* RabbitMQ Publisher untuk event `ContractApproved`
* Swagger/OpenAPI documentation
* GraphQL schema
* Docker containerization

## REST API Endpoint

* POST `/api/v1/auth/login`
* GET `/api/v1/contracts`
* POST `/api/v1/contracts`
* GET `/api/v1/contracts/{id}`
* PUT `/api/v1/contracts/{id}`
* DELETE `/api/v1/contracts/{id}`
* POST `/api/v1/contracts/{id}/approve`

## Cara Menjalankan

```powershell
composer install
copy .env.example .env
php artisan key:generate
New-Item -ItemType File -Path database\database.sqlite -Force
php artisan migrate
docker compose up -d --build
```

Aplikasi dapat diakses melalui:

```text
http://localhost:8000
```

Contoh endpoint:

```text
http://localhost:8000/api/v1/contracts
```

## Transaksi Kritis

Transaksi kritis pada service ini adalah persetujuan kontrak sewa melalui endpoint:

```text
POST /api/v1/contracts/{id}/approve
```

Saat kontrak disetujui, status kontrak berubah menjadi `signed`, kemudian sistem menjalankan proses SOAP Audit dan publish event RabbitMQ dengan routing key `rental.contract.approved`.
