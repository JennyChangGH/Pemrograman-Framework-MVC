<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tpl.ac.id'],
            ['name' => 'Admin TPL', 'password' => Hash::make('password')]
        );

        $dosen = collect([
            ['0412078501', 'Dr. Rina Kartika, M.Kom.', 'rina.kartika@tpl.ac.id', '081234567801', 'Dosen Tetap', 'S3'],
            ['0415038702', 'Andi Pratama, S.T., M.T.', 'andi.pratama@tpl.ac.id', '081234567802', 'Dosen Tetap', 'S2'],
            ['0420119003', 'Siti Nurhaliza, S.Kom., M.Cs.', 'siti.nurhaliza@tpl.ac.id', '081234567803', 'Dosen Tetap', 'S2'],
            ['0402058804', 'Budi Santoso, M.Kom.', 'budi.santoso@tpl.ac.id', '081234567804', 'Dosen Tidak Tetap', 'S2'],
            ['0409129205', 'Dewi Lestari, S.T., M.Eng.', 'dewi.lestari@tpl.ac.id', '081234567805', 'Dosen Tetap', 'S2'],
            ['0425068106', 'Prof. Hendra Wijaya, Ph.D.', 'hendra.wijaya@tpl.ac.id', '081234567806', 'Dosen Tetap', 'S3'],
            ['0418029307', 'Yoga Aditya, S.Kom., M.T.', 'yoga.aditya@tpl.ac.id', '081234567807', 'Dosen Tidak Tetap', 'S2'],
        ])->map(fn ($d) => Dosen::updateOrCreate(['nidn' => $d[0]], [
            'nama' => $d[1],
            'email' => $d[2],
            'no_hp' => $d[3],
            'status_dosen' => $d[4],
            'pendidikan_terakhir' => $d[5],
        ]));

        $matakuliah = [
            ['TPL101', 'Algoritma dan Pemrograman', 4, 1, 'Wajib', 0, 'Dasar logika, algoritma, dan pemrograman terstruktur.'],
            ['TPL102', 'Pengantar Teknologi Informasi', 2, 1, 'Wajib', 2, 'Konsep dasar teknologi informasi dan komputasi.'],
            ['TPL201', 'Struktur Data', 3, 2, 'Wajib', 0, 'Array, linked list, stack, queue, tree, dan graph.'],
            ['TPL202', 'Basis Data', 3, 2, 'Wajib', 5, 'Perancangan basis data relasional dan SQL.'],
            ['TPL301', 'Pemrograman Web', 3, 3, 'Wajib', 1, 'HTML, CSS, JavaScript, dan framework web modern.'],
            ['TPL302', 'Pemrograman Berorientasi Objek', 3, 3, 'Wajib', 3, 'Konsep class, object, inheritance, dan polymorphism.'],
            ['TPL401', 'Pemrograman MVC', 3, 4, 'Wajib', 1, 'Arsitektur Model-View-Controller dengan Laravel.'],
            ['TPL402', 'Rekayasa Kebutuhan', 2, 4, 'Wajib', 0, 'Elisitasi, analisis, dan spesifikasi kebutuhan perangkat lunak.'],
            ['TPL501', 'Pemrograman Mobile', 3, 5, 'Wajib', 3, 'Pengembangan aplikasi Android dan cross-platform.'],
            ['TPL502', 'Pengujian Perangkat Lunak', 3, 5, 'Wajib', 0, 'Unit test, integration test, dan quality assurance.'],
            ['TPL601', 'Machine Learning', 3, 6, 'Pilihan', 2, 'Supervised dan unsupervised learning untuk aplikasi.'],
            ['TPL602', 'Pengembangan Game', 3, 6, 'Pilihan', 6, 'Desain dan pemrograman game 2D/3D.'],
            ['TPL701', 'Internet of Things', 3, 7, 'Pilihan', 4, 'Sistem tertanam, sensor, dan komunikasi perangkat.'],
            ['TPL801', 'Tugas Akhir', 6, 8, 'Wajib', 5, 'Penelitian dan pengembangan perangkat lunak mandiri.'],
        ];

        foreach ($matakuliah as $mk) {
            Matakuliah::updateOrCreate(['kode_mk' => $mk[0]], [
                'nama_mk' => $mk[1],
                'sks' => $mk[2],
                'semester' => $mk[3],
                'jenis' => $mk[4],
                'dosen_id' => $dosen[$mk[5]]->id,
                'deskripsi' => $mk[6],
            ]);
        }

        $projects = [
            ['SIAKAD Mini Berbasis Laravel', 'Fajar Ramadhan', '2201001', 2022, 'Web Development', 'Selesai', 1, 'Sistem informasi akademik untuk pengelolaan KRS dan nilai mahasiswa.'],
            ['Aplikasi Kasir UMKM Offline-First', 'Nabila Putri', '2201014', 2022, 'Mobile App', 'Berjalan', 3, 'Aplikasi POS Android yang tetap berjalan tanpa koneksi internet.'],
            ['Deteksi Penyakit Daun Padi dengan CNN', 'Rizky Maulana', '2101022', 2021, 'Artificial Intelligence', 'Selesai', 2, 'Klasifikasi citra daun padi menggunakan convolutional neural network.'],
            ['Smart Garden Monitoring', 'Aulia Rahman', '2301007', 2023, 'Internet of Things', 'Berjalan', 4, 'Pemantauan kelembapan tanah dan penyiraman otomatis berbasis ESP32.'],
            ['Petualangan Nusantara 2D', 'Kevin Saputra', '2201031', 2022, 'Game Development', 'Proposal', 6, 'Game platformer edukasi budaya Indonesia.'],
            ['E-Library Kampus', 'Salsabila Zahra', '2301019', 2023, 'Sistem Informasi', 'Berjalan', 5, 'Sistem peminjaman buku digital dengan notifikasi jatuh tempo.'],
            ['Chatbot Layanan Akademik', 'Dimas Prasetyo', '2101045', 2021, 'Artificial Intelligence', 'Selesai', 2, 'Chatbot NLP untuk menjawab pertanyaan seputar layanan akademik.'],
            ['Marketplace Jasa Mahasiswa', 'Intan Permata', '2301025', 2023, 'Web Development', 'Proposal', 1, 'Platform yang mempertemukan mahasiswa penyedia jasa dengan pelanggan.'],
            ['Presensi QR Code Real-time', 'Gilang Firmansyah', '2201040', 2022, 'Mobile App', 'Selesai', 3, 'Aplikasi presensi kelas menggunakan QR code dinamis.'],
        ];

        foreach ($projects as $p) {
            Project::updateOrCreate(['judul' => $p[0]], [
                'nama_mahasiswa' => $p[1],
                'nim' => $p[2],
                'angkatan' => $p[3],
                'kategori' => $p[4],
                'status' => $p[5],
                'dosen_id' => $dosen[$p[6]]->id,
                'link_repo' => 'https://github.com/tpl/'.str($p[0])->slug(),
                'deskripsi' => $p[7],
            ]);
        }
    }
}
