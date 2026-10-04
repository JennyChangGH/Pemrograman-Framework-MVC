<script setup>
import { computed } from 'vue';
import ResourceForm from '../../components/ResourceForm.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';

const props = defineProps({
    dosen: { type: Object, default: null }, // null = tambah, objek = ubah
    status: { type: Array, default: () => [] },
    pendidikan: { type: Object, default: () => ({}) },
});

const isEdit = computed(() => !!props.dosen);

const sections = computed(() => [
    {
        title: 'Identitas Dosen',
        description: 'Data identitas resmi dosen sesuai PDDikti.',
        fields: [
            { name: 'nama', label: 'Nama Lengkap & Gelar', required: true, span: 'full', placeholder: 'cth. Dr. Rina Kartika, M.Kom.' },
            { name: 'nidn', label: 'NIDN', required: true, placeholder: 'cth. 0412078501', hint: 'Nomor Induk Dosen Nasional, maksimal 20 karakter.' },
            {
                name: 'status_dosen',
                label: 'Status Dosen',
                type: 'select',
                required: true,
                options: props.status.map((s) => ({ value: s, label: s })),
                hint: 'Dosen Tidak Tetap (DTT) dibayar honor per pertemuan.',
            },
        ],
    },
    {
        title: 'Pendidikan & Kontak',
        description: 'Jenjang pendidikan terakhir dan informasi kontak dosen.',
        fields: [
            {
                name: 'pendidikan_terakhir',
                label: 'Pendidikan Terakhir',
                type: 'select',
                required: true,
                span: 'full',
                options: Object.entries(props.pendidikan).map(([value, label]) => ({ value, label })),
            },
            { name: 'email', label: 'Email', type: 'email', required: true, placeholder: 'nama@tpl.ac.id' },
            { name: 'no_hp', label: 'Nomor HP', type: 'tel', placeholder: '08xxxxxxxxxx' },
        ],
    },
]);
</script>

<template>
    <AdminLayout
        :title="isEdit ? 'Ubah Dosen' : 'Tambah Dosen'"
        :subtitle="isEdit ? `Perbarui data ${dosen.nama}` : 'Isi formulir berikut untuk menambahkan dosen baru'"
        :breadcrumb="{ label: 'Dosen', href: '/dosen' }"
    >
        <div class="mx-auto max-w-4xl">
            <ResourceForm
                :action="isEdit ? `/dosen/${dosen.id}` : '/dosen'"
                :method="isEdit ? 'put' : 'post'"
                :submit-label="isEdit ? 'Simpan Perubahan' : 'Simpan Dosen'"
                cancel-url="/dosen"
                :sections="sections"
                :values="dosen ?? {}"
            />
        </div>
    </AdminLayout>
</template>
