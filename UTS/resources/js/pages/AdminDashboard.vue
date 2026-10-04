<script setup>
import { ArrowRight, ArrowUpRight, BookOpen, CalendarDays, FolderKanban, GraduationCap, Plus, Users } from '@lucide/vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import CountUp from '../components/CountUp.vue';
import StatusBadge from '../components/StatusBadge.vue';
import UserAvatar from '../components/UserAvatar.vue';
import AdminLayout from '../layouts/AdminLayout.vue';
import { kategoriIcon, statusHex } from '../lib/helpers';

const props = defineProps({
    stats: { type: Object, required: true },
    projectPerStatus: { type: Object, default: () => ({}) },
    projectPerKategori: { type: Object, default: () => ({}) },
    latestProjects: { type: Array, default: () => [] },
    topDosen: { type: Array, default: () => [] },
});

const userName = computed(() => usePage().props.auth?.user?.name ?? '');

const greeting = computed(() => {
    const h = new Date().getHours();
    if (h < 11) return 'Selamat pagi';
    if (h < 15) return 'Selamat siang';
    if (h < 18) return 'Selamat sore';
    return 'Selamat malam';
});

const today = new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

const menuCards = computed(() => [
    { label: 'Dosen', desc: 'Data dosen & status', value: props.stats.dosen, icon: Users, url: '/dosen', color: 'from-indigo-500 to-violet-500', glow: 'shadow-indigo-500/30' },
    { label: 'Project Mahasiswa', desc: 'Karya & tugas akhir', value: props.stats.project, icon: FolderKanban, url: '/project', color: 'from-sky-500 to-cyan-400', glow: 'shadow-sky-500/30' },
    { label: 'Mata Kuliah', desc: 'Kurikulum & SKS', value: props.stats.matakuliah, icon: BookOpen, url: '/matakuliah', color: 'from-emerald-500 to-teal-400', glow: 'shadow-emerald-500/30' },
]);

/* Donut chart status project */
const totalProject = computed(() => Object.values(props.projectPerStatus).reduce((a, b) => a + b, 0));
const donutSegments = computed(() => {
    let offset = 25;
    return Object.entries(props.projectPerStatus).map(([status, n]) => {
        const pct = totalProject.value ? (n / totalProject.value) * 100 : 0;
        const seg = { status, n, pct, dash: `${pct} ${100 - pct}`, offset, color: statusHex[status] };
        offset -= pct;
        return seg;
    });
});

/* Bar chart kategori */
const kategoriBars = computed(() => {
    const max = Math.max(1, ...Object.values(props.projectPerKategori));
    return Object.entries(props.projectPerKategori).map(([k, n]) => ({ k, n, pct: (n / max) * 100 }));
});
</script>

<template>
    <AdminLayout title="Dashboard" subtitle="Ringkasan data Program Studi Teknik Perangkat Lunak">
    <div class="space-y-6">
        <!-- Welcome banner -->
        <section class="relative animate-fade-up overflow-hidden rounded-3xl bg-slate-950 p-6 sm:p-8">
            <div class="bg-grid absolute inset-0 opacity-70"></div>
            <div class="absolute -top-20 right-10 size-72 rounded-full bg-indigo-600/40 blur-3xl"></div>
            <div class="absolute -bottom-24 left-1/3 size-60 rounded-full bg-cyan-500/20 blur-3xl"></div>
            <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
                <div>
                    <p class="inline-flex items-center gap-2 text-sm text-slate-400"><CalendarDays class="size-4" /> {{ today }}</p>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">
                        {{ greeting }}, <span class="text-gradient">{{ userName }}</span>!
                    </h2>
                    <p class="mt-2 max-w-xl text-slate-400">Berikut ringkasan data Program Studi Teknik Perangkat Lunak hari ini.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <Link href="/dosen/create" class="btn border border-white/15 bg-white/10 text-white backdrop-blur hover:bg-white/20"><Plus class="size-4" /> Dosen</Link>
                    <Link href="/project/create" class="btn border border-white/15 bg-white/10 text-white backdrop-blur hover:bg-white/20"><Plus class="size-4" /> Project</Link>
                    <Link href="/matakuliah/create" class="btn bg-white text-slate-900 hover:bg-slate-100"><Plus class="size-4" /> Mata Kuliah</Link>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <Link
                v-for="(m, i) in menuCards"
                :key="m.label"
                :href="m.url"
                class="card group relative animate-fade-up overflow-hidden p-5 transition hover:-translate-y-1 hover:shadow-xl"
                :style="{ animationDelay: `${(i + 1) * 80}ms` }"
            >
                <div class="absolute -top-10 -right-10 size-32 rounded-full bg-gradient-to-br opacity-10 transition group-hover:scale-150" :class="m.color"></div>
                <div class="flex items-start justify-between">
                    <div class="grid size-12 place-items-center rounded-2xl bg-gradient-to-br text-white shadow-lg" :class="[m.color, m.glow]">
                        <component :is="m.icon" class="size-6" />
                    </div>
                    <ArrowUpRight class="size-5 text-slate-300 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-slate-600" />
                </div>
                <p class="mt-5 text-3xl font-extrabold tracking-tight text-slate-900"><CountUp :value="m.value" /></p>
                <p class="mt-0.5 font-semibold text-slate-700">{{ m.label }}</p>
                <p class="text-xs text-slate-400">{{ m.desc }}</p>
            </Link>

            <div class="card relative animate-fade-up overflow-hidden p-5 [animation-delay:320ms]">
                <div class="grid size-12 place-items-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-400 text-white shadow-lg shadow-amber-500/30">
                    <GraduationCap class="size-6" />
                </div>
                <p class="mt-5 text-3xl font-extrabold tracking-tight text-slate-900"><CountUp :value="stats.sks" /></p>
                <p class="mt-0.5 font-semibold text-slate-700">Total SKS</p>
                <p class="text-xs text-slate-400">Dari seluruh mata kuliah</p>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-5">
            <div class="card p-6 lg:col-span-2">
                <h3 class="font-bold text-slate-900">Status Project</h3>
                <p class="text-sm text-slate-500">Distribusi progres project mahasiswa</p>
                <div class="mt-6 flex flex-col items-center gap-8 sm:flex-row lg:flex-col xl:flex-row">
                    <div class="relative size-44 shrink-0">
                        <svg viewBox="0 0 42 42" class="size-full -rotate-0">
                            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#f1f5f9" stroke-width="5" />
                            <circle
                                v-for="seg in donutSegments"
                                :key="seg.status"
                                cx="21"
                                cy="21"
                                r="15.915"
                                fill="none"
                                :stroke="seg.color"
                                stroke-width="5"
                                stroke-linecap="butt"
                                :stroke-dasharray="seg.dash"
                                :stroke-dashoffset="seg.offset"
                                class="transition-all duration-700"
                            />
                        </svg>
                        <div class="absolute inset-0 grid place-items-center text-center">
                            <div>
                                <p class="text-3xl font-extrabold text-slate-900">{{ totalProject }}</p>
                                <p class="text-xs font-medium text-slate-500">Project</p>
                            </div>
                        </div>
                    </div>
                    <ul class="w-full min-w-0 flex-1 space-y-3">
                        <li v-for="seg in donutSegments" :key="seg.status" class="flex items-center gap-3 rounded-xl bg-slate-50 px-4 py-3">
                            <span class="size-3 shrink-0 rounded-full" :style="{ background: seg.color }"></span>
                            <span class="flex-1 text-sm font-medium text-slate-700">{{ seg.status }}</span>
                            <span class="text-sm font-bold text-slate-900">{{ seg.n }}</span>
                            <span class="w-10 text-right text-xs text-slate-400">{{ Math.round(seg.pct) }}%</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card p-6 lg:col-span-3">
                <h3 class="font-bold text-slate-900">Project per Kategori</h3>
                <p class="text-sm text-slate-500">Bidang peminatan project mahasiswa</p>
                <ul class="mt-6 space-y-4">
                    <li v-for="bar in kategoriBars" :key="bar.k" class="flex items-center gap-4">
                        <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-indigo-50 text-indigo-600">
                            <component :is="kategoriIcon(bar.k)" class="size-5" />
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-slate-700">{{ bar.k }}</span>
                                <span class="font-bold text-slate-900">{{ bar.n }}</span>
                            </div>
                            <div class="mt-1.5 h-2.5 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-cyan-400 transition-all duration-1000" :style="{ width: `${bar.pct}%` }"></div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <!-- Aktivitas -->
        <section class="grid gap-6 lg:grid-cols-5">
            <div class="card lg:col-span-3">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <div>
                        <h3 class="font-bold text-slate-900">Project Terbaru</h3>
                        <p class="text-sm text-slate-500">5 project yang terakhir ditambahkan</p>
                    </div>
                    <Link href="/project" class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-700">Semua <ArrowRight class="size-4" /></Link>
                </div>
                <ul class="divide-y divide-slate-100">
                    <li v-for="p in latestProjects" :key="p.url">
                        <Link :href="p.url" class="flex items-center gap-4 px-6 py-4 transition hover:bg-slate-50">
                            <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-slate-100 text-slate-600">
                                <component :is="kategoriIcon(p.kategori)" class="size-5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold text-slate-900">{{ p.judul }}</p>
                                <p class="truncate text-xs text-slate-500">{{ p.nama_mahasiswa }} Â· {{ p.kategori }}</p>
                            </div>
                            <StatusBadge :value="p.status" />
                        </Link>
                    </li>
                    <li v-if="!latestProjects.length" class="px-6 py-10 text-center text-sm text-slate-500">Belum ada project.</li>
                </ul>
            </div>

            <div class="card lg:col-span-2">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <div>
                        <h3 class="font-bold text-slate-900">Dosen Pembimbing Teraktif</h3>
                        <p class="text-sm text-slate-500">Berdasarkan jumlah bimbingan</p>
                    </div>
                </div>
                <ul class="space-y-1 p-3">
                    <li v-for="(d, i) in topDosen" :key="d.url">
                        <Link :href="d.url" class="flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-slate-50">
                            <span class="w-4 text-center text-xs font-bold text-slate-400">{{ i + 1 }}</span>
                            <UserAvatar :name="d.nama" size="size-10 text-xs" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ d.nama }}</p>
                                <p class="truncate text-xs text-slate-500">{{ d.keterangan }}</p>
                            </div>
                            <span class="rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700">{{ d.project_count }}</span>
                        </Link>
                    </li>
                    <li v-if="!topDosen.length" class="px-3 py-10 text-center text-sm text-slate-500">Belum ada dosen.</li>
                </ul>
            </div>
        </section>
    </div>
    </AdminLayout>
</template>
