## Template Sistem (Web-Based) - Juruteknik Komputer Negeri Perak (JTKPK)

![Versi Template](http://img.shields.io/badge/Versi-v1.0-green.svg)

Template Sistem (Web-Based) & Platform utama sistem bersepadu bagi Kumpulan DEV-TEAM JTKPK. :+1:

[![Antaramuka Dashboard](https://s13.postimg.org/9gbnu98rr/Screen_Shot_2016_11_01_at_4_06_55_PM.png)](https://postimg.org/image/caet7paxv/)

### **Nota Ringkas:**

#### Repository ini mengandungi projek sumber terbuka Framework Laravel (PHP) yang digunapakai dalam pembangunan sistem web-based bagi kumpulan DEV-TEAM JTKPK. Sistem ini telah diubahsuai mengikut keperluan dan kesesuaian bagi sistem yang akan dibangunkan.

**Template sistem ini mengandungi :**

* Modul Pendaftaran Pengguna bagi sistem (Full authentication).
* Maklumat JPN, PPD, dan Sekolah di dalam Negeri PERAK sahaja (buat masa ini).
* Dimurnikan lagi dengan template Themes (Kredit [@putera](https://github.com/putera)) yang sangat cantik dan fluid untuk mobile.
* Dibangunkan khusus untuk **SMART DEVELOPMENT TEAM** bagi JTKPK.
* Modul Development Team iaitu pengurusan projek dan kumpulan Development Team bagi JTKPK.

**Plug-ins yang telah dimasukkan antaranya ialah :**

* Facebook Graph API
* Twitter API
* DOMPDF (Untuk generate fail PDF)
* Google reCAPTCHA (Untuk mengelakkan SPAM dan ABUSE sistem)
* dan akan ditambah dari masa ke semasa mengikut kepada keperluan.

> Sila install perisian Git (https://git-scm.com) & Composer (https://getcomposer.org) untuk mendapatkan template ini dan ikuti langkah dibawah:

**Langkah 1 :** Muat turun soskod ini
```
git clone https://github.com/putera/jtkpk.git
```

**Langkah 2 :** Kemaskini Composer
```
composer update
```

**Langkah 3 :** Setting fail .ENV (Sambungan ke Pangkalan Data anda)
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=<NAMA_DATABASE_ANDA>
DB_USERNAME=<DATABASE_USER>
DB_PASSWORD=<DATABASE_PASSWORD>
```

**Langkah 4 :** Migrate Pangkalan Data
```
php artisan migrate
```

**Langkah 5 :** Seed Pangkalan Data
```
php artisan db:seed
```

**Untuk pengguna Mac OS
```
sudo chmod -R 777 storage/
```

**Langkah 6 :** Run Sistem
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
