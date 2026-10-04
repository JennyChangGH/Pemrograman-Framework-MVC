# Admin Prodi Teknik Perangkat Lunak (TPL)

Aplikasi admin berbasis **Laravel 12 (MVC)** dengan tampilan **Vue 3 + Tailwind CSS** untuk mengelola data Dosen, Project Mahasiswa, dan Mata Kuliah.

## Cara Menjalankan

1. Jalankan **Apache & MySQL** di XAMPP, lalu buat database `db_mytpl` (atau biarkan `migrate` yang membuatnya).
2. Pastikan `.env`:
   ```
   DB_CONNECTION=mysql
   DB_DATABASE=db_mytpl
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Instal & siapkan:
   ```bash
   composer install
   npm install
   php artisan migrate:fresh --seed
   npm run build        # atau: npm run dev (saat development)
   php artisan serve
   ```
4. Buka http://127.0.0.1:8000 → landing dashboard → klik **Sign In**.
   Akun demo: `admin@tpl.ac.id` / `password`

## Struktur MVC

| Lapisan | Lokasi | Isi |
|---|---|---|
| **Model** | `app/Models/` | `Dosen.php` (tbl_dosen), `Project.php` (tbl_project), `Matakuliah.php` (tbl_matakuliah) |
| **Migration** | `database/migrations/` | `create_tbl_dosen`, `create_tbl_matakuliah`, `create_tbl_project` |
| **Controller** | `app/Http/Controllers/` | `DosenController`, `ProjectController`, `MatakuliahController` (CRUD), `HomeController`, `DashboardController`, `AuthController` |
| **View (Blade)** | `resources/views/` | `layouts/base`, `layouts/admin` (header/sidebar/footer yang sama), `dosen/*`, `project/*`, `matakuliah/*` (index, create, edit, show, _form) |
| **View (Vue)** | `resources/js/` | `pages/` (LandingPage, LoginPage, AdminDashboard), `components/` (DataTable, ResourceForm, ConfirmModal, AdminSidebar, …) |
| **Routing** | `routes/web.php` | `Route::resource('dosen' / 'project' / 'matakuliah', ...)` |

### Alur Data

```
Browser → routes/web.php → Controller → Model (Eloquent/MySQL)
                               ↓
                     Blade View (layout + <x-vue>)
                               ↓
               Komponen Vue (tabel, form, tombol aksi CRUD)
```

Controller mengirim data ke Blade; Blade meneruskannya ke komponen Vue melalui `<x-vue component="..." :data="...">` (`resources/views/components/vue.blade.php`), lalu `resources/js/app.js` me-mount komponen tersebut. Form Vue tetap mengirim request biasa (POST/PUT/DELETE + CSRF) ke resource route milik Controller.
