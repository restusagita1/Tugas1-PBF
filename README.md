
# RESTU SAGITA NAWANGSARI (220102042)
PRAKTIKUM PBF CODEIGNITER4

## WELCOME TO CODEIGNITER4
CodeIgniter adalah Kerangka Pengembangan Aplikasi (sebuah toolkit) untuk orang-orang yang membangun situs web menggunakan PHP. Tujuannya adalah untuk memungkinkan Anda mengembangkan proyek jauh lebih cepat daripada menulis kode dari awal.

### Persyaratan Server:

- PHP dan Ekstensi yang Diperlukan (PHP versi 7.4 atau lebih baru dengan ekstensi intl, mbstring, json)
- Ekstensi PHP Opsional
- Basis Data yang Didukung
    1. MySQL (5.1+)
    2. Oracle
    3. PostgreSQL
    4. MSSQL
    5. SQLite
    6. CUBRID
    7. Interbase/Firebird
    8. ODBC

## INSTALASI
CodeIgniter memiliki dua metode instalasi yang didukung: download manual, atau menggunakan Composer.
### Instalasi Komposer
Pertama, pastikan anda sudah melakukan instalasi composer versi 2.0.14 atau yang lebih baru.

*  Di Command prompt atau terminal, arahkan ke directory folder yang akan dibuat project
    
    ```cd C:...\...\... ```
* Jika sudah berada pada folder yang dipilih, buat project dengan syntax:
    
    ```composer create-project codeigniter4/appstarter project-root ```
* Tunggu hingga proses download folder project selesai. Kemudian arahkan kembali Command prompt/terminal ke directory project yang telah dibuat
    
    ```cd C:...\...\...```
* Untuk meng-update composer, gunakan syntax:
    
    ```composer update```
### Instalasi manual
* Download starter project dari repository kemudian ekstrak folder tersebut
* Lakukan konfigurasi awal dengan langkah sebagai berikut:
### Konfigurasi awal
* Buka file `app/Config/App.php` dengan editor teks.
* Tetapkan URL dasar `$baseURL` menjadi URL localhost yang nanti akan diakses. Contoh: `$baseURL = 'http://localhost:8080/';`
* Jika Anda tidak ingin menyertakan `index.php` di URI situs Anda, setel `$indexPage` ke `''`
* Rename file `env` menjadi `.env` dan atur `CI_ENVIRONMENT = production` menjadi `CI_ENVIRONMENT = development`

### Menjalankan Aplikasi Anda
Untuk mengecek apakah instalasi berhasil dilakukan, masukkan command `php spark serve` pada command prompt/terminal. Kemudian, masuk ke `http://localhost:8080` yang muncul dari hasil command tadi.

Jika berhasil, maka akan diarahkan ke browser dengan tampilan **Welcome to CodeIgniter 4.4.1**

## BANGUN APLIKASI PERTAMA ANDA
### Halaman Statis
Hal pertama yang perlu dilakukan adalah menyiapkan aturan perutean untuk menangani halaman statis.
#### Menetapkan aturan perutean
- Buka file Routes yang terletak di `app/Config/Routes.php`
- Tambahkan baris berikut
    ```
    use App\Controllers\Pages;
    
    $routes->get('pages', [Pages::class, 'index']);
    $routes->get('(:segment)', [Pages::class, 'view']);
    ```
#### Membuat Controller Pertama
- Buat file di `app/Controllers/Pages.php` dengan kode berikut.
```
    <?php
    namespace App\Controllers;

    class Pages extends BaseController
    {
        public function index()
        {
            return view('welcome_message');
        }

        public function view($page = 'home')
        {
            // ...
        }
    }
```
#### Buat tampilan
- Buat header di `app/Views/templates/header.php` dan tambahkan kode berikut:
```
    <!doctype html>
    <html>
    <head>
        <title>CodeIgniter Tutorial</title>
    </head>
    <body>

        <h1><?= esc($title) ?></h1>
```
- Buat footer di app/Views/templates/footer.php yang menyertakan kode berikut:
```
        <em>&copy; 2022</em>
    </body>
    </html>
```

### Menambahkan Logika ke Controller
#### Buat home.php dan about.php
- Masukkan `Hello world!` ke dalam kedua file tersebut
#### Complete Pages::view() Method
Ini akan menjadi isi metode view()pada Pages pengontrol yang dibuat di atas:
```
    <?php

    namespace App\Controllers;

    use CodeIgniter\Exceptions\PageNotFoundException; // Add this line

    class Pages extends BaseController
    {
        // ...

        public function view($page = 'home')
        {
            if (! is_file(APPPATH . 'Views/pages/' . $page . '.php')) {
                // Whoops, we don't have a page for that!
                throw new PageNotFoundException($page);
            }

            $data['title'] = ucfirst($page); // Capitalize the first letter

            return view('templates/header', $data)
                . view('pages/' . $page)
                . view('templates/footer');
        }
    }
```
Sekarang kunjungi `localhost:8080/home` apakah itu dirutekan dengan benar ke method view() di controller Pages?

### Bagian News
#### Buat Database untuk Digunakan
- Anda perlu membuat database `ci4tutorial` yang dapat digunakan untuk tutorial ini, dan kemudian mengkonfigurasi CodeIgniter untuk menggunakannya. Menggunakan klien database Anda, sambungkan ke database Anda dan jalankan perintah SQL di bawah ini (MySQL):
```
    CREATE TABLE news (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        title VARCHAR(128) NOT NULL,
        slug VARCHAR(128) NOT NULL,
        body TEXT NOT NULL,
        PRIMARY KEY (id),
        UNIQUE slug (slug)
    );
```
- Inputkan data ke dalam tabel news yang tadi dibuat dengan perintah:
```
    INSERT INTO news VALUES
    (1,'Elvis sighted','elvis-sighted','Elvis was sighted at the Podunk internet cafe. It looked like he was writing a CodeIgniter app.'),
    (2,'Say it isn\'t so!','say-it-isnt-so','Scientists conclude that some programmers have a sense of humor.'),
    (3,'Caffeination, Yes!','caffeination-yes','World\'s largest coffee shop open onsite nested coffee shop for staff only.');
```
#### Hubungkan ke Database Anda
- Buka file konfigurasi lokal .env yang tadi dibuat saat menginstal CI, kemudian masukkan konfigurasi database sesuai dengan database yang anda buat tadi
```
    database.default.hostname = localhost
    database.default.database = ci4tutorial
    database.default.username = root
    database.default.password = 
    database.default.DBDriver = MySQLi
```
#### Buat News Model
Buka direktori **app/Models** dan buat file baru bernama `NewsModel.php` dan tambahkan kode berikut.
```
    <?php

    namespace App\Models;

    use CodeIgniter\Model;

    class NewsModel extends Model
    {
        protected $table = 'news';
    }
```
#### Tambahkan Method NewsModel::getNews()
 Tambahkan kode berikut ke model Anda.
 ```
    public function getNews($slug = false)
        {
            if ($slug === false) {
                return $this->findAll();
            }

            return $this->where(['slug' => $slug])->first();
        }
```
#### Menambahkan Aturan perutean
Ubah file **app/Config/Routes.php** Anda , sehingga terlihat seperti berikut:
```
    <?php

    // ...

    use App\Controllers\News; // Add this line
    use App\Controllers\Pages;

    $routes->get('news', [News::class, 'index']);           // Add this line
    $routes->get('news/(:segment)', [News::class, 'show']); // Add this line

    $routes->get('pages', [Pages::class, 'index']);
    $routes->get('(:segment)', [Pages::class, 'view']);
```
#### Buat News Controller
Buat pengontrol baru di **app/Controllers/News.php**
```
    <?php

    namespace App\Controllers;

    use App\Models\NewsModel;

    class News extends BaseController
    {
        public function index()
        {
            $model = model(NewsModel::class);

            $data['news'] = $model->getNews();
        }

        public function show($slug = null)
        {
            $model = model(NewsModel::class);

            $data['news'] = $model->getNews($slug);
        }
    }
```
#### NewsComplete::index() Method
Ubah method index() menjadi seperti ini:
```
    <?php

    namespace App\Controllers;

    use App\Models\NewsModel;

    class News extends BaseController
    {
        public function index()
        {
            $model = model(NewsModel::class);

            $data = [
                'news'  => $model->getNews(),
                'title' => 'News archive',
            ];

            return view('templates/header', $data)
                . view('news/index')
                . view('templates/footer');
        }

        // ...
    }
```
#### Buat Tampilan News/index
Buat **app/Views/news/index.php** dan tambahkan potongan kode berikut.
```
    <h2><?= esc($title) ?></h2>

    <?php if (! empty($news) && is_array($news)): ?>

        <?php foreach ($news as $news_item): ?>

            <h3><?= esc($news_item['title']) ?></h3>

            <div class="main">
                <?= esc($news_item['body']) ?>
            </div>
            <p><a href="/news/<?= esc($news_item['slug'], 'url') ?>">View article</a></p>

        <?php endforeach ?>

    <?php else: ?>

        <h3>No News</h3>

        <p>Unable to find any news for you.</p>

    <?php endif ?>
```
#### Complete News::show() Method
Kembali ke News controller dan perbarui method show() dengan kode berikut:
```
    <?php

    namespace App\Controllers;

    use App\Models\NewsModel;
    use CodeIgniter\Exceptions\PageNotFoundException;

    class News extends BaseController
    {
        // ...

        public function show($slug = null)
        {
            $model = model(NewsModel::class);

            $data['news'] = $model->getNews($slug);

            if (empty($data['news'])) {
                throw new PageNotFoundException('Cannot find the news item: ' . $slug);
            }

            $data['title'] = $data['news']['title'];

            return view('templates/header', $data)
                . view('news/view')
                . view('templates/footer');
        }
    }
```
#### Buat tampilan news/view
Letakkan kode berikut di **file app/Views/news/view.php**
```
    <h2><?= esc($news['title']) ?></h2>
    <p><?= esc($news['body']) ?></p>
```
Arahkan browser Anda ke halaman “berita”, yaitu `localhost:8080/news`, Anda akan melihat daftar item berita, yang masing-masing memiliki link untuk menampilkan satu artikel saja.

### Buat Item berita
#### Aktifkan Filter CSRF
Buka file **app/Config/Filters.php** dan perbarui $methodsproperti seperti berikut:
```
    <?php

    namespace Config;

    use CodeIgniter\Config\BaseConfig;

    class Filters extends BaseConfig
    {
        // ...

        public $methods = [
            'post' => ['csrf'],
        ];

        // ...
    }
```
#### Menambahkan Aturan perutean
Tambahkan aturan tambahan ke file **app/Config/Routes.php**. Pastikan file Anda berisi kode berikut ini:
```
    <?php

    // ...

    use App\Controllers\News;
    use App\Controllers\Pages;

    $routes->get('news', [News::class, 'index']);
    $routes->get('news/new', [News::class, 'new']); // Add this line
    $routes->post('news', [News::class, 'create']); // Add this line
    $routes->get('news/(:segment)', [News::class, 'show']);

    $routes->get('pages', [Pages::class, 'index']);
    $routes->get('(:segment)', [Pages::class, 'view']);
```
#### Buat Formulir
- **Buat File news/create**
Untuk memasukkan data ke dalam database, Anda perlu membuat formulir dimana Anda dapat memasukkan informasi yang akan disimpan. Ini berarti diperlukan formulir dengan dua bidang, satu untuk judul dan satu lagi untuk teks. Buat tampilan baru di **app/Views/news/create.php**:
```
    <h2><?= esc($title) ?></h2>

    <?= session()->getFlashdata('error') ?>
    <?= validation_list_errors() ?>

    <form action="/news" method="post">
        <?= csrf_field() ?>

        <label for="title">Title</label>
        <input type="input" name="title" value="<?= set_value('title') ?>">
        <br>

        <label for="body">Text</label>
        <textarea name="body" cols="45" rows="4"><?= set_value('body') ?></textarea>
        <br>

        <input type="submit" name="submit" value="Create news item">
    </form>
```
#### News Controller
Kembali ke `News` controller
#### Tambahkan News::new() untuk Menampilkan Formulir
Pertama, buatlah method untuk menampilkan form HTML yang telah Anda buat.
```
<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class News extends BaseController
{
    // ...

    public function new()
    {
        helper('form');

        return view('templates/header', ['title' => 'Create a news item'])
            . view('news/create')
            . view('templates/footer');
    }
}
```
#### Tambahkan News::create() untuk Membuat Item berita
Selanjutnya, buat method untuk membuat item news dari data yang dikirimkan.

Tiga hal yang perlu dilakukan di sini:

- memeriksa apakah data yang dikirimkan lolos aturan validasi.

- menyimpan item berita ke database.

- mengembalikan halaman sukses.
```
<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class News extends BaseController
{
    // ...

    public function create()
    {
        helper('form');

        $data = $this->request->getPost(['title', 'body']);

        // Checks whether the submitted data passed the validation rules.
        if (! $this->validateData($data, [
            'title' => 'required|max_length[255]|min_length[3]',
            'body'  => 'required|max_length[5000]|min_length[10]',
        ])) {
            // The validation fails, so returns the form.
            return $this->new();
        }

        // Gets the validated data.
        $post = $this->validator->getValidated();

        $model = model(NewsModel::class);

        $model->save([
            'title' => $post['title'],
            'slug'  => url_title($post['title'], '-', true),
            'body'  => $post['body'],
        ]);

        return view('templates/header', ['title' => 'Create a news item'])
            . view('news/success')
            . view('templates/footer');
    }
}
```
#### Kembalikan Halaman sukses
Buat tampilan di app/Views/news/success.php dan tulis pesan sukses.
```<p>News item created successfully.</p>```
#### Pembaruan News Model
Edit `NewsModel` untuk memberikannya daftar bidang yang dapat diperbarui di `$allowedFields` properti.
```
<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';

    protected $allowedFields = ['title', 'slug', 'body'];
}
```
#### Buat Item Berita
Sekarang arahkan browser ke local development environment tempat Anda menginstal CodeIgniter dan tambahkan /news/new ke URL. Tambahkan beberapa berita dan periksa halaman berbeda yang Anda buat.

## Ikhtisar codeigniter4
### Struktur Aplikasi
Secara umum, struktur aplikasi terdiri dari:
- Direktori default
    - app
    - system
    - public
    - writable
    - tests
- Memodifikasi Lokasi Direktori
#### - Direktori Default
Instalasi baru memiliki lima direktori: `app/`, `public/`, `writable/`, `tests/` dan `vendor/` atau `system/`. Masing-masing direktori ini memiliki peran yang sangat spesifik untuk dimainkan.
#### - app
Direktori `app` adalah tempat semua kode aplikasi Anda berada. Strukturnya terdiri dari `Config/`, `Controllers/`, `Database/`, `Filters/`, `Helpers/`, `Language/`, `Libraries/`, `Models/`, `ThirdParty/`, `Views/`. Semua file dalam direktori ini berada di bawah `App` namespace, meskipun Anda bebas mengubahnya di `app/Config/Constants.php`.

#### - public
Folder public menampung bagian aplikasi web Anda yang dapat diakses browser, mencegah akses langsung ke kode sumber Anda. Ini berisi file .htaccess utama , index.php , dan aset aplikasi apa pun yang Anda tambahkan, seperti CSS, javascript, atau gambar.

#### - writable
Direktori ini menampung semua direktori yang mungkin perlu ditulisi selama masa pakai aplikasi.
#### - tests
Direktori ini disiapkan untuk menyimpan file pengujian.
#### - Memodifikasi Lokasi Directori
Jika Anda telah memindahkan salah satu direktori utama, Anda dapat mengubah pengaturan konfigurasi di dalam **app/Config/Paths.php**.

### Model, View, dan Controller
#### Apa itu MVC?
MVC adalah pola desain arsitektur website yang terbagi menjadi tiga bagian, yaitu model, view, dan controller yang digunakan untuk mengatur file. Hal ini menjaga data, presentasi, dan aliran melalui aplikasi sebagai bagian yang terpisah.
#### Komponen
#### - Views
Tampilan adalah file sederhana, dengan sedikit atau tanpa logika, yang menampilkan informasi kepada pengguna.
#### - Models
Tugas model adalah memelihara satu tipe data untuk aplikasi. Dalam hal ini, tugas model memiliki dua bagian: menerapkan aturan pada data saat diambil, atau dimasukkan ke dalam database; dan menangani penyimpanan dan pengambilan data sebenarnya dari database.
#### - Controllers
Controller berperan menerima masukan dari pengguna dan kemudian menentukan apa yang harus dilakukan dengan masukan tersebut.
## Membangun Respons
### Tampilan/Views
Tampilan tidak pernah dipanggil secara langsung, tampilan harus dimuat oleh pengontrol atau rute tampilan .
#### Membuat Tampilan
Buat file bernama **blog_view.php** dan letakkan ini di dalamnya:
```
<html>
    <head>
        <title>My Blog</title>
    </head>
    <body>
        <h1>Welcome to my Blog!</h1>
    </body>
</html>
```
Kemudian simpan file di direktori **app/Views** Anda.
#### Menampilkan Tampilan
- Untuk memuat dan menampilkan file tampilan tertentu, gunakan kode berikut di controller Anda:
``` return view('name'); ```
- Buat file bernama **Blog.php** di direktori **app/Controllers**, dan letakkan kode ini di dalamnya:
```
<?php

namespace App\Controllers;

class Blog extends BaseController
{
    public function index()
    {
        return view('blog_view');
    }
}
```
- Buka file Routes yang terletak di **app/Config/Routes.php**,dan cari “Definisi Rute”. Tambahkan kode berikut:
```
use App\Controllers\Blog;

$routes->get('blog', [Blog::class, 'index']);
```
- Jika Anda mengunjungi situs Anda, Anda akan melihat tampilan baru. URL-nya mirip dengan ini:
```example.com/index.php/blog/```

## Helper
### Number Helper
#### Memuat Helper
Helper ini dapat dimuat dengan kode berikut:
```
<?php

helper('number');
```
#### Fungsi yang Tersedia
Memformat angka sebagai byte, berdasarkan ukuran, dan menambahkan akhiran yang sesuai. Contoh:
```
<?php

echo number_to_size(456); // Returns 456 Bytes
echo number_to_size(4567); // Returns 4.5 KB
echo number_to_size(45678); // Returns 44.6 KB
echo number_to_size(456789); // Returns 447.8 KB
echo number_to_size(3456789); // Returns 3.3 MB
echo number_to_size(12345678912345); // Returns 1.8 GB
echo number_to_size(123456789123456789); // Returns 11,228.3 TB
```
