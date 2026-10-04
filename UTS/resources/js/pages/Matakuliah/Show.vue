<script setup>
import { Link } from '@inertiajs/vue3';
import DeleteButton from '../../components/DeleteButton.vue';
import StatusBadge from '../../components/StatusBadge.vue';
import UserAvatar from '../../components/UserAvatar.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';

defineProps({
    matakuliah: { type: Object, required: true },
});
</script>

<template>
    <AdminLayout title="Detail Mata Kuliah" :breadcrumb="{ label: 'Mata Kuliah', href: '/matakuliah' }">
        <template #actions>
            <div class="flex gap-2">
                <Link :href="`/matakuliah/${matakuliah.id}/edit`" class="btn btn-primary">Ubah Data</Link>
                <DeleteButton :action="`/matakuliah/${matakuliah.id}`" :label="matakuliah.nama_mk" entity="mata kuliah" />
            </div>
        </template>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="card relative overflow-hidden p-6 sm:p-8 lg:col-span-2">
                <div class="absolute -top-16 -right-16 size-48 rounded-full bg-emerald-400/10"></div>
                <div class="relative flex flex-wrap items-center gap-2">
                    <span class="rounded-md bg-slate-900 px-2.5 py-1 font-mono text-xs font-bold text-white">{{ matakuliah.kode_mk }}</span>
                    <StatusBadge :value="matakuliah.jenis" />
                </div>
                <h2 class="relative mt-4 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ matakuliah.nama_mk }}</h2>
                <p class="relative mt-4 leading-relaxed text-slate-600">{{ matakuliah.deskripsi || 'Belum ada deskripsi untuk mata kuliah ini.' }}</p>

                <div class="relative mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl bg-indigo-50 p-4">
                        <p class="text-xs font-semibold text-indigo-600">Bobot</p>
                        <p class="mt-1 text-2xl font-extrabold text-indigo-900">{{ matakuliah.sks }} <span class="text-sm font-semibold">SKS</span></p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-4">
                        <p class="text-xs font-semibold text-emerald-600">Semester</p>
                        <p class="mt-1 text-2xl font-extrabold text-emerald-900">{{ matakuliah.semester }}</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 p-4">
                        <p class="text-xs font-semibold text-amber-600">Jenis</p>
                        <p class="mt-1 text-2xl font-extrabold text-amber-900">{{ matakuliah.jenis }}</p>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-bold text-slate-900">Dosen Pengampu</h3>
                <Link
                    v-if="matakuliah.dosen"
                    :href="`/dosen/${matakuliah.dosen.id}`"
                    class="mt-4 flex items-center gap-4 rounded-2xl border border-slate-100 p-4 transition hover:border-indigo-200 hover:bg-indigo-50/40"
                >
                    <UserAvatar :name="matakuliah.dosen.nama" size="size-14 text-base" />
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900">{{ matakuliah.dosen.nama }}</p>
                        <p class="text-xs text-slate-500">{{ matakuliah.dosen.status_dosen }}</p>
                        <p class="text-xs text-indigo-600">{{ matakuliah.dosen.pendidikan_label }}</p>
                    </div>
                </Link>
                <p v-else class="mt-4 rounded-2xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-500">Pengampu belum ditentukan.</p>

                <dl class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Dibuat</dt><dd class="font-medium text-slate-800">{{ matakuliah.dibuat }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Diperbarui</dt><dd class="font-medium text-slate-800">{{ matakuliah.diperbarui }}</dd></div>
                </dl>
            </div>
        </div>
    </AdminLayout>
</template>
