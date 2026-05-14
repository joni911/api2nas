# Dokumentasi API - Image Storage System

## Base URL
```
https://your-domain.com/api
```

## Endpoint

### Health Check
#### GET /health
Memeriksa status kesehatan sistem.

##### Response 200
```json
{
  "status": "healthy",
  "timestamp": "2023-01-01T00:00:00.000000Z",
  "version": "1.0.0"
}
```

---

### Upload File
#### POST /data2nas
Upload file ke sistem dalam bentuk base64.

##### Headers
- `Content-Type: application/json`

##### Request Body
```json
{
  "file": "string (base64 encoded file)",
  "nama_sistem": "string (original filename)",
  "id_tdaabel": "string (record ID)",
  "tabel_name": "string (table name)",
  "apikey": "string (valid API key)"
}
```

##### Response 201 (Created)
```json
{
  "message": "File uploaded successfully",
  "data": {
    "id": 1,
    "url": "https://your-domain.com/storage/uploads/2023/01/filename.jpg",
    "file_info": {
      "original_name": "nama_file_asli",
      "stored_name": "nama_file_asli_1672531200_random.jpg",
      "size": 12345,
      "mime_type": "image/jpeg"
    }
  }
}
```

##### Response 400 (Bad Request)
```json
{
  "error": "Invalid base64 format"
}
```

##### Response 401 (Unauthorized)
```json
{
  "error": "Invalid or inactive API key"
}
```

##### Response 422 (Unprocessable Entity)
```json
{
  "errors": {
    "file": ["The file field is required."],
    "nama_sistem": ["The nama sistem field is required."],
    "id_tabel": ["The id tabel field is required."],
    "tabel_name": ["The tabel name field is required."],
    "apikey": ["The apikey field is required."]
  }
}
```

---

### Get File
#### GET /data2nas/{id}
Mendapatkan informasi file berdasarkan ID.

##### Path Parameters
- `id` (integer, required): ID file dari database

##### Response 200
```json
{
  "id": 1,
  "url": "https://your-domain.com/storage/uploads/2023/01/filename.jpg",
  "nama_file": "nama_file_asli_1672531200_random.jpg",
  "file_path": "uploads/2023/01/filename.jpg",
  "id_tabel": "id_record",
  "tabel_name": "nama_tabel",
  "created_at": "2023-01-01T00:00:00.000000Z"
}
```

##### Response 404 (Not Found)
```json
{
  "message": "Record not found"
}
```

---

## Authentication
Beberapa endpoint memerlukan autentikasi API key. Anda bisa menyertakan API key dalam:
- Header: `X-API-Key: your_api_key_here`
- Query parameter: `?apikey=your_api_key_here`

---

## Error Codes
- `400`: Bad Request - Format data tidak valid
- `401`: Unauthorized - API key tidak valid atau tidak aktif
- `404`: Not Found - Resource tidak ditemukan
- `422`: Unprocessable Entity - Validasi input gagal
- `500`: Internal Server Error - Kesalahan server