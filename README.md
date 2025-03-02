# Dokumentasi Sistem Perpustakaan Online

## 1. Role Pengguna
Sistem ini memiliki dua peran utama:

### a. Admin
- Dapat mengelola kategori buku, buku, anggota, dan peminjaman.
- Dapat melihat status keterlambatan peminjaman buku.
- Dapat melakukan konfirmasi pengembalian buku.

### b. User (Member)
- Dapat melihat daftar buku yang tersedia.
- Dapat meminjam satu buku dalam satu waktu.
- Harus mengembalikan buku sebelum dapat meminjam kembali.

## 2. User
### Tabel `users`
| Kolom      | Tipe Data     | Deskripsi                            |
|------------|--------------|-------------------------------------|
| id         | BIGINT       | Primary key, auto increment        |
| name       | VARCHAR(255) | Nama lengkap pengguna              |
| email      | VARCHAR(255) | Email unik (digunakan untuk login) |
| password   | VARCHAR(255) | Kata sandi terenkripsi             |
| role       | ENUM         | Peran pengguna ('admin', 'member') |
| created_at | TIMESTAMP    | Waktu pendaftaran pengguna         |
| updated_at | TIMESTAMP    | Waktu terakhir diperbarui          |

## 3. Member
### Tabel `members`
| Kolom      | Tipe Data     | Deskripsi                            |
|------------|--------------|-------------------------------------|
| id         | BIGINT       | Primary key, auto increment        |
| user_id    | BIGINT       | Relasi ke tabel `users`            |
| address    | TEXT         | Alamat lengkap member              |
| phone      | VARCHAR(20)  | Nomor telepon member               |
| created_at | TIMESTAMP    | Waktu pendaftaran member           |
| updated_at | TIMESTAMP    | Waktu terakhir diperbarui          |

## 4. Category
### Tabel `categories`
| Kolom      | Tipe Data     | Deskripsi                        |
|------------|--------------|---------------------------------|
| id         | BIGINT       | Primary key, auto increment    |
| name       | VARCHAR(255) | Nama kategori buku             |
| created_at | TIMESTAMP    | Waktu kategori dibuat          |
| updated_at | TIMESTAMP    | Waktu terakhir diperbarui      |

## 5. Book
### Tabel `books`
| Kolom      | Tipe Data     | Deskripsi                          |
|------------|--------------|-----------------------------------|
| id         | BIGINT       | Primary key, auto increment      |
| title      | VARCHAR(255) | Judul buku                        |
| author     | VARCHAR(255) | Nama penulis buku                 |
| category_id| BIGINT       | Relasi ke tabel `categories`      |
| stock      | INT          | Jumlah buku yang tersedia        |
| created_at | TIMESTAMP    | Waktu buku ditambahkan           |
| updated_at | TIMESTAMP    | Waktu terakhir diperbarui        |

## 6. Borrow
### Tabel `borrows`
| Kolom       | Tipe Data     | Deskripsi                                    |
|-------------|--------------|---------------------------------------------|
| id          | BIGINT       | Primary key, auto increment                |
| member_id   | BIGINT       | Relasi ke tabel `members`                   |
| book_id     | BIGINT       | Relasi ke tabel `books`                     |
| borrow_date | DATE         | Tanggal peminjaman buku                     |
| return_date | DATE         | Tanggal pengembalian buku                   |
| status      | ENUM         | Status peminjaman ('borrowed', 'returned')  |
| created_at  | TIMESTAMP    | Waktu peminjaman dicatat                    |
| updated_at  | TIMESTAMP    | Waktu terakhir diperbarui                   |

## Relasi Antar Tabel
1. **`users` memiliki satu `member`** (One-to-One, hanya untuk user dengan role 'member')
2. **`members` memiliki banyak `borrows`** (One-to-Many)
3. **`categories` memiliki banyak `books`** (One-to-Many)
4. **`books` dapat dipinjam oleh banyak `members`** melalui `borrows` (Many-to-Many)
5. **`borrows` memiliki relasi dengan `members` dan `books`** (Foreign Key)

Dokumentasi ini mencakup fitur utama dari sistem perpustakaan terkait role pengguna, kategori, buku, serta peminjaman buku.

