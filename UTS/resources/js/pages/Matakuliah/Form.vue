<script setup>
import { computed } from 'vue';
import ResourceForm from '../../components/ResourceForm.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';

const props = defineProps({
    matakuliah: { type: Object, default: null },
    dosen: { type: Array, default: () => [] },
    jenis: { type: Array, default: () => [] },
});

const isEdit = computed(() => !!props.matakuliah);

const range = (from, to, format) => Array.from({ length: to - from + 1 }, (_, i) => ({ value: from + i, label: format(from + i) }));

const sections = computed(() => [
    {
        title: 'Informasi Mata Kuliah',
        description: 'Kode, nama, dan bobot mata kuliah.',
        fields: [
            { name: 'kode_mk', label: 'Kode Mata Kuliah', required: true, placeholder: 'cth. TPL401' },
            { name: 'nama_mk', label: 'Nama Mata Kuliah', required: true, placeholder: 'cth. Pemrograman MVC' },
            { name: 'sks', label: 'SKS', type: 'select', required: true, options: range(1, 6, (n) => `${n} SKS`) },
            { name: 'semester', label: 'Semester', type: 'select', required: true, options: range(1, 8, (n) => `Semester ${n}`) },
            { name: 'jenis', label: 'Jenis', type: 'select', required: true, options: props.jenis.map((j) => ({ value: j, label: j })) },
            { name: 'dosen_id', label: 'Dosen Pengampu', type: 'select', placeholder: '— Belum ditentukan —', options: props.dosen },
        ],
    },
    {
        title: 'Deskripsi',
        description: 'Ringkasan materi yang dipelajari (opsional).',
        fields: [
            {
                name: 'deskripsi',
                label: 'Deskripsi Singkat',
                type: 'textarea',
                span: 'full',
                maxlength: 1000,
                placeholder: 'Jelaskan capaian pembelajaran mata kuliah ini…',
            },
        ],
    },
]);
</script>

<template>
    <AdminLayout
        :title="isEdit ? 'Ubah Mata Kuliah' : 'Tambah Mata Kuliah'"
        :subtitle="isEdit ? `Perbarui data ${matakuliah.nama_mk}` : 'Isi formulir berikut untuk menambahkan mata kuliah baru'"
        :breadcrumb="{ label: 'Mata Kuliah', href: '/matakuliah' }"
    >
        <div class="mx-auto max-w-4xl">
            <ResourceForm
                :action="isEdit ? `/matakuliah/${matakuliah.id}` : '/matakuliah'"
                :method="isEdit ? 'put' : 'post'"
                :submit-label="isEdit ? 'Simpan Perubahan' : 'Simpan Mata Kuliah'"
                cancel-url="/matakuliah"
                :sections="sections"
                :values="matakuliah ?? {}"
            />
        </div>
    </AdminLayout>
</template>
