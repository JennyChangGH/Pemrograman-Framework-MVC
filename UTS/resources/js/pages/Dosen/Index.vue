<script setup>
import { computed } from 'vue';
import DataTable from '../../components/DataTable.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';

const props = defineProps({
    dosen: { type: Array, default: () => [] },
    status: { type: Array, default: () => [] },
});

const columns = [
    { key: 'nama', label: 'Dosen', type: 'avatar', sub: 'email' },
    { key: 'nidn', label: 'NIDN', type: 'mono' },
    { key: 'status_dosen', label: 'Status', type: 'badge' },
    { key: 'pendidikan_terakhir', label: 'Pendidikan', type: 'pill', align: 'center' },
    { key: 'matakuliah_count', label: 'MK', type: 'pill', align: 'center' },
    { key: 'project_count', label: 'Bimbingan', type: 'pill', align: 'center' },
];

const rows = computed(() => props.dosen.map((d) => ({ ...d, label: d.nama })));
</script>

<template>
    <AdminLayout title="Data Dosen" subtitle="Kelola data dosen Program Studi Teknik Perangkat Lunak">
        <DataTable
            entity="dosen"
            base-url="/dosen"
            create-label="Tambah Dosen"
            search-placeholder="Cari nama, NIDN, email…"
            :search-keys="['nama', 'nidn', 'email', 'pendidikan_terakhir']"
            :filter="{ key: 'status_dosen', options: status }"
            :columns="columns"
            :rows="rows"
        />
    </AdminLayout>
</template>
