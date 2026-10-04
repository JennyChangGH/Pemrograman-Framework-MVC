<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import DeleteButton from '../../components/DeleteButton.vue';
import StatusBadge from '../../components/StatusBadge.vue';
import UserAvatar from '../../components/UserAvatar.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';

const props = defineProps({
    dosen: { type: Object, required: true },
});

const detail = computed(() => [
    ['NIDN', props.dosen.nidn],
    ['Email', props.dosen.email],
    ['No. HP', props.dosen.no_hp ?? '—'],
    ['Terdaftar', props.dosen.terdaftar],
]);
</script>

<template>
    <AdminLayout title="Detail Dosen" :breadcrumb="{ label: 'Dosen', href: '/dosen' }">
        <template #actions>
            <div class="flex gap-2">
                <Link :href="`/dosen/${dosen.id}/edit`" class="btn btn-primary">Ubah Data</Link>
                <DeleteButton :action="`/dosen/${dosen.id}`" :label="dosen.nama" entity="dosen" />
            </div>
        </template>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="card overflow-hidden">
                <div class="h-24 bg-gradient-to-br from-indigo-600 via-violet-600 to-cyan-500"></div>
                <div class="-mt-12 px-6 pb-6 text-center">
                    <UserAvatar :name="dosen.nama" size="mx-auto size-24 text-2xl ring-4! shadow-xl" />
                    <h2 class="mt-4 text-xl font-extrabold text-slate-900">{{ dosen.nama }}</h2>
                    <p class="text-sm text-indigo-600">{{ dosen.pendidikan_label }}</p>
                    <div class="mt-3"><StatusBadge :value="dosen.status_dosen" /></div>
                </div>
                <dl class="divide-y divide-slate-100 border-t border-slate-100 text-sm">
                    <div v-for="[label, value] in detail" :key="label" class="flex justify-between gap-4 px-6 py-3">
                        <dt class="text-slate-500">{{ label }}</dt>
                        <dd class="text-right font-semibold break-all text-slate-800">{{ value }}</dd>
                    </div>
                </dl>
            </div>

            <div class="space-y-6 lg:col-span-2">
                <div class="card">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <h3 class="font-bold text-slate-900">Mata Kuliah yang Diampu</h3>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">{{ dosen.matakuliah.length }} MK</span>
                    </div>
                    <ul class="divide-y divide-slate-100">
                        <li v-for="mk in dosen.matakuliah" :key="mk.id">
                            <Link :href="`/matakuliah/${mk.id}`" class="flex items-center gap-4 px-6 py-3.5 transition hover:bg-slate-50">
                                <span class="rounded-md bg-slate-100 px-2 py-1 font-mono text-xs font-semibold text-slate-700">{{ mk.kode_mk }}</span>
                                <span class="flex-1 font-medium text-slate-800">{{ mk.nama_mk }}</span>
                                <span class="text-xs text-slate-500">Smt {{ mk.semester }} · {{ mk.sks }} SKS</span>
                            </Link>
                        </li>
                        <li v-if="!dosen.matakuliah.length" class="px-6 py-8 text-center text-sm text-slate-500">Belum mengampu mata kuliah.</li>
                    </ul>
                </div>

                <div class="card">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <h3 class="font-bold text-slate-900">Project Bimbingan</h3>
                        <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-bold text-sky-700">{{ dosen.project.length }} project</span>
                    </div>
                    <ul class="divide-y divide-slate-100">
                        <li v-for="p in dosen.project" :key="p.id">
                            <Link :href="`/project/${p.id}`" class="flex items-center gap-4 px-6 py-3.5 transition hover:bg-slate-50">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-medium text-slate-800">{{ p.judul }}</p>
                                    <p class="text-xs text-slate-500">{{ p.nama_mahasiswa }} · {{ p.kategori }}</p>
                                </div>
                                <StatusBadge :value="p.status" />
                            </Link>
                        </li>
                        <li v-if="!dosen.project.length" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada project bimbingan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
