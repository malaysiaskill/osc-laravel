## Template Sistem (Web-Based) - Juruteknik Komputer Negeri Perak (JTKPK)

![Versi Template](http://img.shields.io/badge/Versi-v1.0.0-green.svg)

Template Sistem (Web-Based) & Platform utama sistem bersepadu bagi Kumpulan DEV-TEAM JTKPK. :+1:

[![Antaramuka Log Masuk Sistem](https://s12.postimg.org/6v3x9myfh/Screen_Shot_2016_11_01_at_4_05_29_PM.png)](https://postimg.org/image/6icj3gg5l/)

**Antaramuka Log Masuk Sistem**

[![Antaramuka Dashboard](https://s13.postimg.org/9gbnu98rr/Screen_Shot_2016_11_01_at_4_06_55_PM.png)](https://postimg.org/image/caet7paxv/)

**Antaramuka Dashboard**

Ini adalah antaramuka utama apabila log masuk ke sistem. Semua pakej/modul yang telah didevelop oleh kumpulan DevTeam akan disenaraikan di muka hadapan Dashboard ini.

[![Senarai Pakej/Module dalam Sistem](https://s18.postimg.org/k1qkd9t61/Screen_Shot_2016_11_01_at_4_09_14_PM.png)](https://postimg.org/image/tz1l6c0rp/)

**Antaramuka Pakej (Bahagian Pentadbir)**

Ini merupakan antaramuka yang menyenaraikan semua pakej/modul telah dipasang (embed) ke dalam sistem. Bahagian ini hanya dapat diakses oleh sistem pentadbir sahaja.

### **Nota Ringkas:**

#### Repository ini mengandungi projek sumber terbuka Framework Laravel (PHP) yang digunapakai dalam pembangunan sistem web-based bagi kumpulan DEV-TEAM JTKPK. Sistem ini telah diubahsuai mengikut keperluan dan kesesuaian bagi sistem yang akan dibangunkan.

**Template sistem ini mengandungi :**

* Modul Pendaftaran Pengguna bagi sistem (Full authentication).
* Maklumat JPN, PPD, dan Sekolah di dalam Negeri PERAK sahaja (buat masa ini).
* Dimurnikan lagi dengan template Themes (Kredit [@putera](https://github.com/putera)) yang sangat cantik dan fluid untuk mobile.
* Dibangunkan khusus untuk **SMART DEVELOPMENT TEAM** bagi JTKPK.

**Plug-ins yang telah dimasukkan antaranya ialah :**

* facebook/php-graph-sdk (Facebook Graph API)
* abraham/twitteroauth (Twitter API)
* dompdf/dompdf (Untuk generate fail PDF)
* google/recaptcha (reCAPTCHA untuk mengelakkan SPAM dan ABUSE)
* dan akan ditambah dari masa ke semasa mengikut kepada keperluan.

> Untuk mendapatkan template sistem ini, anda perlulah memasang Git (https://git-scm.com) & Composer (https://getcomposer.org) dan ikuti arahan berikut:

**Langkah 1 :** Clone Repositori ini
```
git clone https://github.com/putera/jtkpk.git
```

**Langkah 2 :** Setting fail .ENV (Sambungan ke Pangkalan Data)
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=<NAMA_DATABASE_ANDA>
DB_USERNAME=<DATABASE_USER>
DB_PASSWORD=<DATABASE_PASSWORD>
```

**Langkah 3 :** Migrate Pangkalan Data
```
php artisan migrate
```

**Langkah 4 :** Seed Pangkalan Data
```
php artisan db:seed
```

**Langkah 5 :** Run Sistem
```
php artisan serve
```

```
Layari : http://localhost:8000
```

Itu sahaja ! Mudah kan ?

**Pengguna & Kata Laluan Administrator default adalah :**
```
ID Pengguna/Email : admin@domain.com
Kata Laluan : password
```

Sebarang masalah boleh berhubung terus dengan saudara Zulkifli Mohamed [(@putera)](https://github.com/putera). Terima kasih.
