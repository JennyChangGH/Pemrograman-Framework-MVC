<script setup>
import { computed } from 'vue';
import DataTable from '../../components/DataTable.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';

const props = defineProps({
    project: { type: Array, default: () => [] },
    status: { type: Array, default: () => [] },
});

const columns = [
    { key: 'judul', label: 'Judul Project', type: 'title', sub: 'kategori' },
    { key: 'nama_mahasiswa', label: 'Mahasiswa', type: 'avatar', sub: 'nim_angkatan' },
    { key: 'pembimbing', label: 'Pembimbing' },
    { key: 'status', label: 'Status', type: 'badge' },
];

const rows = computed(() => props.project.map((p) => ({ ...p, label: p.judul })));
</script>

<template>
    <AdminLayout title="Project Mahasiswa" subtitle="Kelola data project dan karya mahasiswa TPL">
        <DataTable
            entity="project"
            base-url="/project"
            create-label="Tambah Project"
            search-placeholder="Cari judul, mahasiswa, NIM…"
            :search-keys="['judul', 'nama_mahasiswa', 'nim', 'kategori', 'pembimbing']"
            :filter="{ key: 'status', options: status }"
            :columns="columns"
            :rows="rows"
        />
    </AdminLayout>
</template>
