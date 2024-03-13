
# Farah Nada Syahidah (220302034) 

## CodeIgniter4

## Welcome to CodeIgniter4
CodeIgniter4 merupakan sebuah framework PHP yang digunakan untuk membangun website maupun aplikasi. Tujuannya agar dapat mengembangkan proyek jauh lebih cepat daripada harus menulis kode dari awal. 

### Persyaratan Server 

PHP dan Ekstensi yang diperlukan :

Diperlukan PHP versi 7.4 atau lebih baru, dengan mengaktifkan ekstensi PHP berikut : 
- internasional
- mbstring
- json

Database yang didukung 
- MySQL versi 5.1 ke atas, 
- Oracle Database versi 12.1 atau lebih baru,
- microsoft SQL versi 2005 atau lebih baru, dll

## Composer Installation
CodeIgniter memiliki dua metode instalasi yang didukung yaitu download manual atau menggunakan Composer. Kelebihan instalasi menggunakan composer yaitu mudah untuk diperbarui.

Langkah - langkah instalasi Composer :
- Instal Composer (versi 2.0.14 atau lebih baru) pada website getcomposer.org.
- Tentukan folder yang akan digunakan untuk meletakkan project..
- Buka terminal/git bash here pada folder tersebut
- Ketikkan pada terminal perintah untuk membuat project baru.
    
    `composer create-project codeigniter4/appstarter (namaproject)`
- Perintah untuk update 
    
    `composer update`
## Manual Installation
Kelebihan instalasi manual yaitu hanya perlu unduh dan jalankan.
Langkah - langkah :
- Download starter project dari repository.
- Ekstrak folder yang sudah diunduh.

##  Menjalankan Aplikasi Anda
#### Konfigurasi Awal
Langkah - langkah :
- Buka file **app/Config/App.php**
- Tetapkan $baseURL atau dapat diatur dalam file .env
    
    `app.baseURL = 'http://localhost:8080/'`
- Tetapkan $indexPage (Jika tidak ingin menyertakan index.php di URL situs, setel `indexPage = ''`)
- Rename file env menjadi .env (supaya file env dapat digunakan)
- Tetapkan ke mode pengembangan development ()
    
    `CI_ENVIRONMENT = development`

#### Menjalankan Project
Langkah-langkah :
- Buka terminal pada project folder kita
- Masukkan baris perintah

    `php spark serve`
- Klik pada server yang ada

    `http://localhost:8080`
## Halaman Statis
Hal pertama yang akan dilakukan adalah menyiapkan aturan perutean untuk menangani halaman statis.
#### Menetapkan Aturan Perutean
- Buka **app/Config/Routes.php**
- Tambahkan baris perintah 
    ```
    use App\Controllers\Pages;

    $routes->get('pages', [Pages::class, 'index']);
    $routes->get('(:segment)', [Pages::class, 'view']);
    ```
#### Buat Pengontrol Halaman
- Buka file **app/Controllers/Pages.php**, lalu tambahkan kode berikut

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
#### Buat Tampilan
- Buat header di **app/Views/templates/header.php** lalu tambahkan kode berikut
    ``` 
    <!doctype html>
    <html>
    <head>
        <title>CodeIgniter Tutorial</title>
    </head>
    <body>

        <h1><?= esc($title) ?></h1>

    ```
- Buat footer di **app/Views/templates/footer.php** yang menyertakan kode berikut

    ```
        <em>&copy; 2022</em>
    </body>
    </html>
    ```
#### Menambahkan logika ke Controllers
- Buat file **home.php** dan **about.php** pada direktori **app/Views/pages**
- Isikan kedua file tersebut dengan `Hello World!`
#### Menambahkan baris perintah pada Controller Pages
Ini akan menjadi isi metode view() pada Controller Pages yang dibuat di atas:

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
Sekarang kunjungi `localhost:8080/home` . Apakah itu dirutekan dengan benar ke method view() pada Controller Pages.

## Bagian Berita
#### Buat Database untuk digunakan
- Buat database ci4tutorial
- Pada database ci4tutorial jalankan perintah SQL di bawah Ini
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
- Isikan tabel dengan beberapa data melalui perintah SQL
```
    INSERT INTO news VALUES
    (1,'Elvis sighted','elvis-sighted','Elvis was sighted at the Podunk internet cafe. It looked like he was writing a CodeIgniter app.'),
    (2,'Say it isn\'t so!','say-it-isnt-so','Scientists conclude that some programmers have a sense of humor.'),
    (3,'Caffeination, Yes!','caffeination-yes','World\'s largest coffee shop open onsite nested coffee shop for staff only.');
```

#### Hubungkan ke Database 
Pada file **.env** lakukan konfigurasi database seperti di bawah Ini
```
database.default.hostname = localhost
database.default.database = ci4tutorial
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```
#### Buat Model News
Buka direktori **app/Models** dan buat file baru bernama **NewsModel.php** lalu tambahkan kode berikut
```
<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';
}
```

#### Menambahkan baris perintah pada NewsModel method getNews()
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

#### Menambahkan Aturan Perutean
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
#### Buat Controller News
Buat Controller baru di **app/Controllers/News.php** seperti di bawah Ini
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
#### Lengkapi method index() pada Controller News
Ubah index() metodenya menjadi seperti ini:
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
#### Buat tampilan News
Buat **app/Views/news/index.php** dan tambahkan kode berikut.
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
#### Lengkapi method show() pada Controller News
Lengkapi method show() pada Controller News seperti berikut ini :
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
#### Buat Tampilan News
Satu-satunya hal yang perlu dilakukan adalah membuat tampilan terkait di **app/Views/news/view.php** . Letakkan kode berikut di file ini.
```
<h2><?= esc($news['title']) ?></h2>
<p><?= esc($news['body']) ?></p>
```
Lalu kunjungi  `localhost:8080/news` , Anda akan melihat daftar item News, yang masing-masing memiliki link untuk menampilkan satu artikel saja.

## Membuat Item Berita
#### Aktifkan Filter CSRF
Sebelum membuat formulir, aktifkan perlindungan CSRF.

Buka file **app/Config/Filters.php** dan perbarui `$methods` properti seperti berikut:
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
Hal tersebut mengkonfigurasi filter CSRF untuk diaktifkan untuk semua permintaan POST.

#### Menambahkan Aturan Perutean
Sebelum Anda dapat mulai menambahkan item berita ke dalam aplikasi CodeIgniter Anda, Anda harus menambahkan aturan tambahan ke file **app/Config/Routes.php** . Pastikan file Anda berisi yang berikut ini:
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
- Buat tampilan baru di **app/Views/news/create.php**
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
#### Menambahkan Fungsi new() pada Controller News 
Buat metode untuk menampilkan form HTML yang telah dibuat.
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
#### Menambahkan Fungsi create() untuk Membuat Item Berita
Selanjutnya, buat metode untuk membuat item berita dari data yang dikirimkan.

Anda akan melakukan tiga hal di sini:

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
#### Kembalikan Halaman Sukses
Setelah ini, file tampilan dimuat dan dikembalikan untuk menampilkan pesan sukses. Buat tampilan di **app/Views/news/success.php** dan tulis pesan sukses.

`<p>News item created successfully.</p>`
#### Update NewsModel
Edit NewsModel untuk memberikannya daftar bidang yang dapat diperbarui di `$allowedFields` properti.
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
Sekarang arahkan browser Anda ke lingkungan pengembangan lokal tempat Anda menginstal CodeIgniter dan tambahkan **/news/new** ke URL. Tambahkan beberapa berita dan periksa halaman berbeda yang Anda buat.

## Struktur Aplikasi 
Secara umum, struktur aplikasi terdiri dari :
- Direktori Default
    - app. Direktori `app` adalah tempat semua kode aplikasi berada. Strukturnya terdiri dari `Config/`, `Controllers/`, `Database/`, `Filters/`, `Helpers/`, `Language/`, `Libraries/`, `Models/`, `ThirdParty/`, `Views/`  
    - system. Direktori ini menyimpan file-file yang membentuk kerangka itu sendiri.
    - public. Folder publik menampung bagian aplikasi web Anda yang dapat diakses browser, mencegah akses langsung ke kode. berisi **.htaccess**, **index.php**, CSS, javascript dan gambar
    - writeable. Direktori ini menampung semua direktori yang mungkin perlu ditulisi selama masa pakai aplikasi. Ini termasuk direktori untuk menyimpan file cache, log, dan unggahan apa pun yang mungkin dikirim pengguna.
    - tests. Direktori ini disiapkan untuk menyimpan file pengujian.
- Memodifikasi Lokasi Direktori
    
    Jika Anda telah memindahkan salah satu direktori utama, Anda dapat mengubah pengaturan konfigurasi di dalam **app/Config/Paths.php** .

## Models, View, and Controllers
#### Apa itu MVC?
MVC adalah singkatan dari Model-View-Controller, sebuah pola desain yang digunakan dalam pengembangan perangkat lunak untuk memisahkan logika aplikasi ke dalam tiga komponen yang berbeda.
#### komponen
- Model. Mengelola data aplikasi dan membantu menegakkan aturan bisnis khusus yang mungkin diperlukan aplikasi. Model biasanya disimpan di **app/Models**
- View. Merupakan file sederhana, dengan sedikit atau tanpa logika, yang menampilkan informasi kepada pengguna. View umumnya disimpan di **app/Views**
- Controllers. Controller bertindak sebagai kode perekat, menyusun data bolak-balik antara tampilan (atau pengguna yang melihatnya) dan penyimpanan data. Pengontrol biasanya disimpan di **app/Controllers**

## Views
#### Membuat View
Dengan menggunakan editor teks Anda, buat file bernama **blog_view.php** dan letakkan ini di dalamnya:
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
Kemudian simpan file di direktori app/Views Anda .
#### Menampilkan View
- Untuk memuat dan menampilkan file tampilan tertentu, Anda akan menggunakan kode berikut di pengontrol Anda:
    ```
    return view('name');
    ```
    Di mana name adalah nama file tampilan Anda.

- Sekarang, buat file bernama **Blog.php** di direktori **app/Controllers** , dan letakkan ini di dalamnya:
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

- Buka file perutean yang terletak di **app/Config/Routes.php** , dan cari “Definisi Rute”. Tambahkan kode berikut:
    ```
    use App\Controllers\Blog;

    $routes->get('blog', [Blog::class, 'index']);
    ```
- Jika Anda mengunjungi situs Anda, Anda akan melihat tampilan baru Anda. URL-nya mirip dengan ini:
    ```
    example.com/index.php/blog/
    ```

##Helper
###  Number Helper
#### Memuat Helper
Helper ini dimuat menggunakan kode berikut:
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