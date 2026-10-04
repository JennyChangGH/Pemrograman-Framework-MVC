<script setup>
import { Link } from '@inertiajs/vue3';
import { Link2 } from '@lucide/vue';
import { computed } from 'vue';
import DeleteButton from '../../components/DeleteButton.vue';
import StatusBadge from '../../components/StatusBadge.vue';
import UserAvatar from '../../components/UserAvatar.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';

const props = defineProps({
    project: { type: Object, required: true },
    steps: { type: Array, default: () => [] },
});

const current = computed(() => props.steps.indexOf(props.project.status));
</script>

<template>
    <AdminLayout title="Detail Project" :breadcrumb="{ label: 'Project', href: '/project' }">
        <template #actions>
            <div class="flex gap-2">
                <Link :href="`/project/${project.id}/edit`" class="btn btn-primary">Ubah Data</Link>
                <DeleteButton :action="`/project/${project.id}`" :label="project.judul" entity="project" />
            </div>
        </template>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="relative overflow-hidden rounded-3xl bg-slate-950 p-6 sm:p-8">
                    <div class="bg-grid absolute inset-0 opacity-70"></div>
                    <div class="absolute -top-20 -right-10 size-64 rounded-full bg-indigo-600/40 blur-3xl"></div>
                    <div class="relative">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold text-indigo-200">{{ project.kategori }}</span>
                            <StatusBadge :value="project.status" />
                        </div>
                        <h2 class="mt-4 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">{{ project.judul }}</h2>
                        <a
                            v-if="project.link_repo"
                            :href="project.link_repo"
                            target="_blank"
                            rel="noopener"
                            class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-cyan-300 hover:text-cyan-200"
                        >
                            <Link2 class="size-4" />
                            {{ project.link_repo }}
                        </a>
                    </div>
                </div>

                <div class="card p-6 sm:p-8">
                    <h3 class="font-bold text-slate-900">Deskripsi Project</h3>
                    <p class="mt-3 leading-relaxed whitespace-pre-line text-slate-600">{{ project.deskripsi || 'Belum ada deskripsi.' }}</p>

                    <h3 class="mt-8 font-bold text-slate-900">Progres</h3>
                    <ol class="mt-4 grid grid-cols-3 gap-2">
                        <li v-for="(step, i) in steps" :key="step">
                            <div class="h-2 rounded-full" :class="i <= current ? 'bg-gradient-to-r from-indigo-500 to-cyan-400' : 'bg-slate-100'"></div>
                            <p class="mt-2 text-xs font-semibold" :class="i <= current ? 'text-slate-800' : 'text-slate-400'">{{ i + 1 }}. {{ step }}</p>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card p-6">
                    <h3 class="font-bold text-slate-900">Mahasiswa</h3>
                    <div class="mt-4 flex items-center gap-4">
                        <UserAvatar :name="project.nama_mahasiswa" size="size-14 text-base" />
                        <div>
                            <p class="font-semibold text-slate-900">{{ project.nama_mahasiswa }}</p>
                            <p class="text-xs text-slate-500">NIM {{ project.nim }}</p>
                            <p class="text-xs text-slate-500">Angkatan {{ project.angkatan }}</p>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <h3 class="font-bold text-slate-900">Dosen Pembimbing</h3>
                    <Link
                        v-if="project.dosen"
                        :href="`/dosen/${project.dosen.id}`"
                        class="mt-4 flex items-center gap-4 rounded-2xl border border-slate-100 p-4 transition hover:border-indigo-200 hover:bg-indigo-50/40"
                    >
                        <UserAvatar :name="project.dosen.nama" size="size-12 text-sm" />
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900">{{ project.dosen.nama }}</p>
                            <p class="text-xs text-indigo-600">{{ project.dosen.status_dosen }} · {{ project.dosen.pendidikan_terakhir }}</p>
                        </div>
                    </Link>
                    <p v-else class="mt-4 rounded-2xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-500">Belum ada pembimbing.</p>

                    <dl class="mt-6 space-y-3 text-sm">
                        <div class="flex justify-between"><dt class="text-slate-500">Dibuat</dt><dd class="font-medium text-slate-800">{{ project.dibuat }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-500">Diperbarui</dt><dd class="font-medium text-slate-800">{{ project.diperbarui }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
