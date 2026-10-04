<script setup>
import { computed } from 'vue';
import ResourceForm from '../../components/ResourceForm.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';

const props = defineProps({
    project: { type: Object, default: null }, 
    dosen: { type: Array, default: () => [] },
    kategori: { type: Array, default: () => [] },
    status: { type: Array, default: () => [] },
    tahunMaks: { type: Number, required: true },
    tahunIni: { type: Number, required: true },
});

const isEdit = computed(() => !!props.project);

const sections = computed(() => [
    {
        title: 'Informasi Project',
        description: 'Judul, kategori, dan progres project.',
        fields: [
            { name: 'judul', label: 'Judul Project', required: true, span: 'full', placeholder: 'cth. Sistem Informasi Akademik Berbasis Web' },
            { name: 'kategori', label: 'Kategori', type: 'select', required: true, options: props.kategori.map((k) => ({ value: k, label: k })) },
            { name: 'status', label: 'Status', type: 'select', required: true, options: props.status.map((s) => ({ value: s, label: s })) },
            {
                name: 'link_repo',
                label: 'Link Repository',
                type: 'url',
                span: 'full',
                placeholder: 'https://github.com/username/project',
                hint: 'Opsional. Tautan GitHub/GitLab project.',
            },
            {
                name: 'deskripsi',
                label: 'Deskripsi',
                type: 'textarea',
                span: 'full',
                maxlength: 2000,
                placeholder: 'Jelaskan latar belakang, fitur utama, dan teknologi yang digunakan…',
            },
        ],
    },
    {
        title: 'Mahasiswa & Pembimbing',
        description: 'Data mahasiswa pengembang dan dosen pembimbing.',
        fields: [
            { name: 'nama_mahasiswa', label: 'Nama Mahasiswa', required: true, placeholder: 'Nama lengkap' },
            { name: 'nim', label: 'NIM', required: true, placeholder: 'cth. 2201001' },
            { name: 'angkatan', label: 'Angkatan', type: 'number', required: true, min: 2000, max: props.tahunMaks, placeholder: String(props.tahunIni) },
            { name: 'dosen_id', label: 'Dosen Pembimbing', type: 'select', placeholder: '— Belum ada pembimbing —', options: props.dosen },
        ],
    },
]);
</script>

<template>
    <AdminLayout
        :title="isEdit ? 'Ubah Project' : 'Tambah Project'"
        :subtitle="isEdit ? `Perbarui data project ${project.judul}` : 'Isi formulir berikut untuk menambahkan project mahasiswa'"
        :breadcrumb="{ label: 'Project', href: '/project' }"
    >
        <div class="mx-auto max-w-4xl">
            <ResourceForm
                :action="isEdit ? `/project/${project.id}` : '/project'"
                :method="isEdit ? 'put' : 'post'"
                :submit-label="isEdit ? 'Simpan Perubahan' : 'Simpan Project'"
                cancel-url="/project"
                :sections="sections"
                :values="project ?? {}"
            />
        </div>
    </AdminLayout>
</template>
