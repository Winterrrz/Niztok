Niztock adalah aplikasi pengelolaan stok yang dirancang untuk memudahkan Anda dalam mengelola data stok dan penjualan di seluruh cabang perusahaan Anda. Dengan fitur lengkap dan antarmuka yang intuitif, Niztock membantu Anda mengoptimalkan pengelolaan inventaris dan memaksimalkan efisiensi bisnis Anda.

## Requirements

- [Visual Studio Code](https://code.visualstudio.com/)
- [Composer](https://getcomposer.org/)
- [XAMPP 8.2.4 or later](https://www.apachefriends.org/download.html)


## Getting Started

Anda perlu melakukan sedikit konfigurasi di bawah ini sebelum mulai menjalankan web Niztock:
1. Unduh file ZIP source code Niztock atau jalankan perintah berikut di terminal Anda

2. Ekstrak file ZIP dan letakkan folder Niztock di dalam folder htdocs (misalnya D:\xampp\htdocs).

3. Buka folder Niztock di Visual Studio Code.

4. Di Visual Studio Code, buka terminal dengan memilih `Terminal > New Terminal` di menu bagian atas, atau tekan ctrl + `
   
5. Jalankan perintah berikut untuk menginstal package yang dibutuhkan:
   ```console
   composer install
   ```
   
6. Setelah instalasi selesai, salin file `env` dan beri nama menjadi `.env`
   - Atur nama aplikasi:
     ```
     APP_NAME = "Nama Aplikasi"
     ```
     
   - Ubah environment menjadi development:
     ```
     CI_ENVIRONMENT = development
     ```

   - Atur Base URL:
     ```
     app.baseURL = 'http://localhost:8080/'
     ```
     
   - Konfirgurasikan database. Sesuaikan dengan database milik Anda:
     ```
     database.default.hostname = localhost
     database.default.database = Nistock
     database.default.username = root
     database.default.password = 
     database.default.DBDriver = MySQLi
     database.default.DBPrefix =
     database.default.port = 3306
     ```

   - Pastikan tidak ada tanda "#" pada baris yang telah dikonfigurasi.
     
7. Buka file `vendor\myth\auth\src\Filters\RoleFilter.php`. Modifikasi function `before` (baris 18 - 46) menjadi seperti berikut ini.
   ```
   public function before(RequestInterface $request, $arguments = null)
    {
        /* 
        * Jika tidak ada pengguna yang login, arahkan mereka ke formulir login.
        */
        if (!$this->authenticate->check()) {
            session()->set('redirect_url', current_url());
            return redirect($this->reservedRoutes['login']);
        }

        /* 
        * Jika tidak ada argumen yang diberikan, lanjutkan ke proses berikutnya.
        */
        if (empty($arguments)) {
            return;
        }

        /* 
        * Periksa setiap izin yang diminta
        */
        foreach ($arguments as $group) {
            /* 
            * Jika pengguna berada dalam grup yang memiliki izin, lanjutkan.
            */
            if ($this->authorize->inGroup($group, $this->authenticate->id())) {
                return;
            }
        }

        /* 
        * Jika pengguna tidak memiliki izin dan loginnya bersifat senyap (silent login)
        */
        if ($this->authenticate->silent()) {
            /* 
            * Arahkan ke URL yang tersimpan di sesi atau ke URL landing
            */
            $redirectURL = session('redirect_url') ?? route_to($this->landingRoute);
            unset($_SESSION['redirect_url']);
            return redirect()->to($redirectURL)->with('error', lang('Auth.notEnoughPrivilege'));
        }

        /* 
        * Jika pengguna tidak memiliki izin dan login tidak bersifat senyap, arahkan ke halaman utama
        */
        return redirect()->to(base_url());
    }
   ```
     
8. Buka file `vendor\myth\auth\src\Config\Auth.php`.
    - Atur defaultUserGroup (baris 19)
      ```
      public $defaultUserGroup = 'cabang';
      ```
      
    - Atur tampilan auth (baris 76 - 83)
      ```
      public $views = [
        'login'           => 'App\Views\auth\login',
        'register'        => 'Myth\Auth\Views\register',
        'forgot'          => 'Myth\Auth\Views\forgot',
        'reset'           => 'Myth\Auth\Views\reset',
        'emailForgot'     => 'Myth\Auth\Views\emails\forgot',
        'emailActivation' => 'Myth\Auth\Views\emails\activation',
      ];
      ```
    - Atur activeResetter (baris 201)
      ```
      public $activeResetter = null;
      ```
    
9. Buka XAMPP Control Panel Anda dan start server Apache dan MySQL.
    
10. Buka `localhost/phpmyadmin` di browser, lalu buat database baru dengan nama `Nistock` atau sesuaikan dengan nama database yang Anda inginkan.

11. Buka kembali terminal di Visual Studio Code, jalankan perintah migrate dan seed.
    - Migrate
      ```console
      php spark migrate -2024-07-27-125132_create_Niztock_tables
      php spark migrate -2024-07-27-134447_create_auth_tables
      ```

    - Seed
      ```console
      php spark db:seed KantorSeeder
      php spark db:seed SalesmanSeeder
      php spark db:seed KonsumenSeeder
      php spark db:seed SupplierSeeder
      php spark db:seed KategoriProdukSeeder
      php spark db:seed ProdukSeeder
      php spark db:seed UserSeeder
      php spark db:seed AuthGroupsSeeder
      php spark db:seed AuthGroupsUsersSeeder
      ```

12. Mulai server dengan menjalankan perintah berikut ini di terminal.
    ```console
    php spark serve
    ```
      
13. Selesai! Akses web melalui `http://localhost:8080`.

## First Usage

### Login
Setelah melakukan instalasi dan konfigurasi Niztock, Anda dapat melakukan login pada aplikasi dengan email dan password sebagai berikut.

#### Pusat
```
Email: pusat@example.com
Password: password
```

#### Cabang 1
```
Email: cabang1@example.com
Password: password
```

#### Cabang 2
```
Email: cabang2@example.com
Password: password
```

### Tambahkan Stok Awal
Setelah berhasil melakukan login, Anda dapat mencoba menambahkan stok awal produk, untuk selanjutnya melakukan pencatatan penjualan, alokasi, mutasi, dan retur.

## Services

Layanan di bawah ini tersedia pada aplikasi Niztock.

### Layanan Utama

#### Pengelolaan Data
- Kelola data konsumen, salesman, supplier, kantor & cabang, kategori produk, dan produk dengan mudah dalam satu platform terintegrasi.

#### Pengelolaan Stok dalam Penjualan, Alokasi, Mutasi, dan Retur
- Atur stok produk untuk penjualan, alokasi, mutasi antar cabang, dan proses retur penjualan dengan efisien.

#### Kartu Stok
- Tinjau dan pantau histori masuk dan keluarnya stok untuk memastikan transparansi dan akurasi dalam pengelolaan stok dengan riwayat yang lengkap.

#### Menambahkan User tanpa Batas
- Tambah akun pengguna tanpa batas, baik untuk pusat maupun cabang.

#### Kelola Profil
- Ubah foto profil, username, nama, dan informasi profil lainnya.

#### Lupa & Ubah Password
- Fitur untuk memulihkan password dan mengubah password melalui halaman profil sehingga dapat meningkatkan keamanan dan kemudahan akses akun pengguna.

#### Ubah Email
- Ubah alamat email dengan verifikasi melalui sistem email sehingga memastikan email pengguna selalu diperbarui dan akurat untuk komunikasi dan pemulihan akun.

## Multilevel Auth

Pengguna yang terdaftar terdiri dari 2 jenis, yaitu role Pusat dan Cabang.
- Pusat dapat melakukan:
    - Lihat, Tambah, Edit, dan Hapus Data Salesman, Konsumen, Pusat & Cabang, dan User.
    - Lihat, Tambah, Edit, dan Hapus Data Supplier, Kategori Produk, dan Produk.
    - Lihat Data Penjualan.
    - Lihat, Tambah, dan Edit Data Alokasi.
    - Lihat Data Mutasi.
    - Lihat Data Retur.
    - Lihat Kartu Stok dan Tambah & Edit Data Stok Awal Produk.
    - Kelola Profil.

- Cabang dapat melakukan:
    - Lihat dan Tambah Data Salesman.
    - Lihat, Tambah, dan Edit Data Konsumen.
    - Lihat Data Supplier, Kategori Produk, dan Produk.
    - Lihat dan Tambah Data Penjualan.
    - Lihat, Tambah, dan Update Status Data Mutasi.
    - Lihat dan Tambah Data Retur.
    - Lihat Kartu Stok.
    - Kelola Profil.
"# Niztok" 
