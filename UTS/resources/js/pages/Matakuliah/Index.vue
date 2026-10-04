<script setup>
import { computed } from 'vue';
import DataTable from '../../components/DataTable.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';

const props = defineProps({
    matakuliah: { type: Array, default: () => [] },
});

const columns = [
    { key: 'kode_mk', label: 'Kode', type: 'mono' },
    { key: 'nama_mk', label: 'Mata Kuliah', type: 'title', sub: 'deskripsi' },
    { key: 'sks', label: 'SKS', type: 'pill', align: 'center' },
    { key: 'semester', label: 'Semester', type: 'pill', align: 'center' },
    { key: 'jenis', label: 'Jenis', type: 'badge' },
    { key: 'pengampu', label: 'Dosen Pengampu' },
];

const rows = computed(() => props.matakuliah.map((m) => ({ ...m, label: `${m.kode_mk} - ${m.nama_mk}` })));
</script>

<template>
    <AdminLayout title="Data Mata Kuliah" subtitle="Kelola kurikulum dan mata kuliah Program Studi TPL">
        <DataTable
            entity="mata kuliah"
            base-url="/matakuliah"
            create-label="Tambah Mata Kuliah"
            search-placeholder="Cari kode, nama, pengampu…"
            :search-keys="['kode_mk', 'nama_mk', 'pengampu']"
            :filter="{ key: 'semester', prefix: 'Smt ', options: [1, 2, 3, 4, 5, 6, 7, 8] }"
            :columns="columns"
            :rows="rows"
        />
    </AdminLayout>
</template>
