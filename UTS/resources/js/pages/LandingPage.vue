<script setup>
import {
    ArrowRight,
    BookOpen,
    FolderKanban,
    GraduationCap,
    Layers,
    LayoutDashboard,
    LogIn,
    Menu,
    ShieldCheck,
    Sparkles,
    UserRound,
    Users,
    X,
} from '@lucide/vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import AppLogo from '../components/AppLogo.vue';
import CountUp from '../components/CountUp.vue';
import StatusBadge from '../components/StatusBadge.vue';
import UserAvatar from '../components/UserAvatar.vue';
import { kategoriIcon, statusHex } from '../lib/helpers';

const props = defineProps({
    stats: { type: Object, required: true },
    projectStatus: { type: Object, default: () => ({}) },
    projects: { type: Array, default: () => [] },
    dosen: { type: Array, default: () => [] },
    matakuliah: { type: Array, default: () => [] },
});

const isAuth = computed(() => !!usePage().props.auth);

/* Navbar */
const scrolled = ref(false);
const mobileOpen = ref(false);
const onScroll = () => (scrolled.value = window.scrollY > 24);
onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
});
onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));

const navLinks = [
    { label: 'Beranda', href: '#beranda' },
    { label: 'Project', href: '#project' },
    { label: 'Dosen', href: '#dosen' },
    { label: 'Kurikulum', href: '#kurikulum' },
];

const ctaUrl = computed(() => (isAuth.value ? '/dashboard' : '/login'));
const ctaLabel = computed(() => (isAuth.value ? 'Buka Dashboard' : 'Sign In'));

/* Statistik */
const statCards = computed(() => [
    { label: 'Dosen Aktif', value: props.stats.dosen, icon: Users, color: 'from-indigo-500 to-violet-500' },
    { label: 'Project Mahasiswa', value: props.stats.project, icon: FolderKanban, color: 'from-sky-500 to-cyan-400' },
    { label: 'Mata Kuliah', value: props.stats.matakuliah, icon: BookOpen, color: 'from-emerald-500 to-teal-400' },
    { label: 'Total SKS', value: props.stats.sks, icon: GraduationCap, color: 'from-amber-500 to-orange-400' },
]);

/* Preview mini dashboard di hero */
const statusCount = computed(() => Object.entries(props.projectStatus).map(([s, n]) => ({ s, n })));
const maxStatus = computed(() => Math.max(1, ...statusCount.value.map((x) => x.n)));

/* Project showcase */
const kategoriList = computed(() => ['Semua', ...new Set(props.projects.map((p) => p.kategori))]);
const activeKategori = ref('Semua');
const shownProjects = computed(() =>
    activeKategori.value === 'Semua' ? props.projects : props.projects.filter((p) => p.kategori === activeKategori.value),
);

/* Kurikulum */
const semesters = computed(() => [...new Set(props.matakuliah.map((m) => m.semester))].sort((a, b) => a - b));
const activeSemester = ref(null);
const currentSemester = computed(() => activeSemester.value ?? semesters.value[0]);
const semesterMk = computed(() => props.matakuliah.filter((m) => m.semester === currentSemester.value));
const semesterSks = computed(() => semesterMk.value.reduce((t, m) => t + m.sks, 0));
</script>

<template>
    <Head title="Beranda" />

    <!-- ============ NAVBAR ============ -->
    <header
        class="fixed inset-x-0 top-0 z-40 transition-all duration-300"
        :class="scrolled ? 'border-b border-white/10 bg-slate-950/80 py-3 backdrop-blur-xl' : 'py-5'"
    >
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-6">
            <a href="#beranda"><AppLogo dark /></a>

            <nav class="hidden items-center gap-1 md:flex">
                <a
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-slate-300 transition hover:bg-white/5 hover:text-white"
                >
                    {{ link.label }}
                </a>
            </nav>

            <div class="flex items-center gap-2">
                <Link :href="ctaUrl" class="btn btn-primary hidden sm:inline-flex">
                    <LayoutDashboard v-if="isAuth" class="size-4" />
                    <LogIn v-else class="size-4" />
                    {{ ctaLabel }}
                </Link>
                <button class="rounded-xl p-2 text-slate-300 hover:bg-white/10 md:hidden" aria-label="Menu" @click="mobileOpen = !mobileOpen">
                    <X v-if="mobileOpen" class="size-6" />
                    <Menu v-else class="size-6" />
                </button>
            </div>
        </div>

        <Transition enter-from-class="-translate-y-2 opacity-0" enter-active-class="transition" leave-to-class="opacity-0" leave-active-class="transition">
            <div v-if="mobileOpen" class="mx-4 mt-3 rounded-2xl border border-white/10 bg-slate-900/95 p-3 backdrop-blur-xl md:hidden">
                <a
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    class="block rounded-lg px-4 py-2.5 text-sm font-medium text-slate-300 hover:bg-white/5"
                    @click="mobileOpen = false"
                >
                    {{ link.label }}
                </a>
                <Link :href="ctaUrl" class="btn btn-primary mt-2 w-full"><LogIn class="size-4" /> {{ ctaLabel }}</Link>
            </div>
        </Transition>
    </header>

    <!-- ============ HERO ============ -->
    <section id="beranda" class="relative overflow-hidden bg-slate-950 pt-32 pb-40 sm:pt-40">
        <div class="bg-grid absolute inset-0 [mask-image:radial-gradient(ellipse_at_top,black_30%,transparent_75%)]"></div>
        <div class="absolute top-20 -left-32 size-96 animate-float rounded-full bg-indigo-600/30 blur-3xl"></div>
        <div class="absolute top-40 -right-20 size-80 animate-float rounded-full bg-cyan-500/20 blur-3xl [animation-delay:-4s]"></div>
        <div class="absolute bottom-0 left-1/3 size-72 animate-float rounded-full bg-violet-600/20 blur-3xl [animation-delay:-2s]"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-16 px-4 sm:px-6 lg:grid-cols-2">
            <div class="animate-fade-up">
                <span class="inline-flex items-center gap-2 rounded-full border border-indigo-400/30 bg-indigo-500/10 px-4 py-1.5 text-xs font-semibold text-indigo-200">
                    <Sparkles class="size-3.5" /> Program Studi Teknik Perangkat Lunak
                </span>
                <h1 class="mt-6 text-3xl leading-[1.15] font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Web Program Studi <span class="text-gradient">Teknik Perangkat Lunak</span> Universitas Universal
                </h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-slate-400 sm:text-lg">
                    Portal administrasi Prodi TPL untuk mengelola data dosen, project mahasiswa, dan mata kuliah dalam satu dashboard yang rapi dan mudah digunakan.
                </p>
                <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                    <Link :href="ctaUrl" class="btn btn-primary px-6 py-3.5 text-base">
                        {{ isAuth ? 'Buka Dashboard Admin' : 'Masuk ke Admin Panel' }} <ArrowRight class="size-5" />
                    </Link>
                    <a href="#project" class="btn border border-white/15 bg-white/5 px-6 py-3.5 text-base text-white backdrop-blur hover:bg-white/10">
                        Jelajahi Project
                    </a>
                </div>
                <div class="mt-10 flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <UserAvatar v-for="d in dosen.slice(0, 4)" :key="d.nama" :name="d.nama" size="size-10 text-xs ring-slate-950!" />
                    </div>
                    <p class="text-sm text-slate-400">
                        <span class="font-semibold text-white">{{ stats.dosen }} dosen</span> membimbing
                        <span class="font-semibold text-white">{{ stats.project }} project</span> mahasiswa
                    </p>
                </div>
            </div>

            <!-- Preview dashboard -->
            <div class="relative hidden animate-fade-up [animation-delay:150ms] lg:block">
                <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-indigo-500/30 via-violet-500/10 to-cyan-400/30 blur-2xl"></div>
                <div class="relative rounded-3xl border border-white/10 bg-white/[0.04] p-6 shadow-2xl backdrop-blur-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex gap-1.5">
                            <span class="size-3 rounded-full bg-rose-400/80"></span>
                            <span class="size-3 rounded-full bg-amber-400/80"></span>
                            <span class="size-3 rounded-full bg-emerald-400/80"></span>
                        </div>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-medium text-slate-400">tpl-admin / dashboard</span>
                    </div>

                    <div class="mt-6 grid grid-cols-3 gap-3">
                        <div v-for="c in statCards.slice(0, 3)" :key="c.label" class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="grid size-8 place-items-center rounded-lg bg-gradient-to-br text-white" :class="c.color">
                                <component :is="c.icon" class="size-4" />
                            </div>
                            <p class="mt-3 text-2xl font-bold text-white"><CountUp :value="c.value" /></p>
                            <p class="text-[11px] text-slate-400">{{ c.label }}</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-sm font-semibold text-white">Status Project</p>
                        <div class="mt-4 space-y-3">
                            <div v-for="x in statusCount" :key="x.s">
                                <div class="flex justify-between text-xs text-slate-400">
                                    <span>{{ x.s }}</span><span class="font-semibold text-white">{{ x.n }}</span>
                                </div>
                                <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-white/10">
                                    <div
                                        class="h-full rounded-full transition-all duration-1000"
                                        :style="{ width: `${(x.n / maxStatus) * 100}%`, background: statusHex[x.s] }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -bottom-6 -left-8 flex items-center gap-3 rounded-2xl border border-white/10 bg-slate-900/90 p-3 pr-5 shadow-xl backdrop-blur">
                        <div class="grid size-10 place-items-center rounded-xl bg-emerald-500/15 text-emerald-400"><ShieldCheck class="size-5" /></div>
                        <div>
                            <p class="text-xs font-semibold text-white">Akses Aman</p>
                            <p class="text-[11px] text-slate-400">Khusus admin terverifikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ STAT CARDS ============ -->
    <section class="relative z-10 mx-auto -mt-24 max-w-7xl px-4 sm:px-6">
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div
                v-for="c in statCards"
                :key="c.label"
                class="group rounded-3xl border border-slate-200/70 bg-white p-5 shadow-xl shadow-slate-900/5 transition hover:-translate-y-1 sm:p-6"
            >
                <div class="grid size-12 place-items-center rounded-2xl bg-gradient-to-br text-white shadow-lg transition group-hover:scale-110" :class="c.color">
                    <component :is="c.icon" class="size-6" />
                </div>
                <p class="mt-5 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl"><CountUp :value="c.value" /></p>
                <p class="mt-1 text-sm font-medium text-slate-500">{{ c.label }}</p>
            </div>
        </div>
    </section>

    <!-- ============ PROJECT ============ -->
    <section id="project" class="mx-auto max-w-7xl scroll-mt-20 px-4 py-24 sm:px-6">
        <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div>
                <p class="text-sm font-bold tracking-widest text-indigo-600 uppercase">Karya Mahasiswa</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Project Mahasiswa Terbaru</h2>
                <p class="mt-3 max-w-2xl text-slate-500">Inovasi perangkat lunak yang dikembangkan mahasiswa TPL bersama dosen pembimbing.</p>
            </div>
            <div class="flex gap-2 overflow-x-auto pb-1">
                <button
                    v-for="k in kategoriList"
                    :key="k"
                    class="rounded-full px-4 py-2 text-sm font-semibold whitespace-nowrap transition"
                    :class="activeKategori === k ? 'bg-slate-900 text-white shadow-lg' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:ring-slate-300'"
                    @click="activeKategori = k"
                >
                    {{ k }}
                </button>
            </div>
        </div>

        <TransitionGroup tag="div" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" move-class="transition duration-500" enter-from-class="opacity-0 scale-95" enter-active-class="transition duration-300">
            <article
                v-for="p in shownProjects"
                :key="p.judul"
                class="group flex flex-col rounded-3xl border border-slate-200/70 bg-white p-6 transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-2xl hover:shadow-indigo-500/10"
            >
                <div class="flex items-start justify-between">
                    <div class="grid size-12 place-items-center rounded-2xl bg-indigo-50 text-indigo-600 transition group-hover:bg-gradient-to-br group-hover:from-indigo-500 group-hover:to-violet-500 group-hover:text-white">
                        <component :is="kategoriIcon(p.kategori)" class="size-6" />
                    </div>
                    <StatusBadge :value="p.status" />
                </div>
                <p class="mt-5 text-xs font-semibold text-indigo-600">{{ p.kategori }}</p>
                <h3 class="mt-1 text-lg leading-snug font-bold text-slate-900">{{ p.judul }}</h3>
                <p class="mt-2 line-clamp-2 flex-1 text-sm text-slate-500">{{ p.deskripsi }}</p>
                <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-4">
                    <UserAvatar :name="p.nama_mahasiswa" size="size-9 text-xs" />
                    <div class="min-w-0 text-sm">
                        <p class="truncate font-semibold text-slate-800">{{ p.nama_mahasiswa }}</p>
                        <p class="truncate text-xs text-slate-500">Angkatan {{ p.angkatan }} · {{ p.pembimbing ?? 'Belum ada pembimbing' }}</p>
                    </div>
                </div>
            </article>
        </TransitionGroup>
    </section>

    <!-- ============ DOSEN ============ -->
    <section id="dosen" class="scroll-mt-20 bg-white py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-bold tracking-widest text-indigo-600 uppercase">Tim Pengajar</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Dosen Program Studi</h2>
                <p class="mt-3 text-slate-500">Para ahli di bidangnya yang siap membimbing mahasiswa menjadi software engineer profesional.</p>
            </div>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="d in dosen"
                    :key="d.nama"
                    class="group relative overflow-hidden rounded-3xl border border-slate-200/70 bg-slate-50/50 p-6 text-center transition hover:-translate-y-1 hover:bg-white hover:shadow-2xl hover:shadow-slate-900/5"
                >
                    <div class="absolute inset-x-0 top-0 h-20 bg-gradient-to-br from-indigo-500/10 via-violet-500/10 to-cyan-400/10"></div>
                    <UserAvatar :name="d.nama" size="relative mx-auto size-20 text-xl ring-4! shadow-lg" />
                    <h3 class="mt-4 font-bold text-slate-900">{{ d.nama }}</h3>
                    <p class="mt-1 text-sm text-indigo-600">{{ d.pendidikan }}</p>
                    <div class="mt-4 flex items-center justify-center gap-2 text-xs">
                        <span class="rounded-full bg-white px-3 py-1 font-semibold text-slate-600 ring-1 ring-slate-200">{{ d.status_dosen }}</span>
                        <span class="rounded-full bg-white px-3 py-1 font-semibold text-slate-600 ring-1 ring-slate-200">{{ d.project_count }} project</span>
                    </div>
                </div>
            </div>
            <p v-if="!dosen.length" class="mt-10 text-center text-slate-500"><UserRound class="mx-auto mb-2 size-8" /> Belum ada data dosen.</p>
        </div>
    </section>

    <!-- ============ KURIKULUM ============ -->
    <section id="kurikulum" class="mx-auto max-w-7xl scroll-mt-20 px-4 py-24 sm:px-6">
        <div class="grid gap-12 lg:grid-cols-[1fr_2fr]">
            <div>
                <p class="text-sm font-bold tracking-widest text-indigo-600 uppercase">Kurikulum</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Mata Kuliah per Semester</h2>
                <p class="mt-3 text-slate-500">Kurikulum berbasis industri yang mencakup rekayasa perangkat lunak, web, mobile, hingga kecerdasan buatan.</p>
                <div class="mt-8 grid grid-cols-4 gap-2">
                    <button
                        v-for="s in semesters"
                        :key="s"
                        class="rounded-2xl py-3 text-center transition"
                        :class="currentSemester === s ? 'bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:ring-indigo-300'"
                        @click="activeSemester = s"
                    >
                        <span class="block text-[10px] font-semibold uppercase opacity-70">Smt</span>
                        <span class="text-lg font-extrabold">{{ s }}</span>
                    </button>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/70 bg-white p-2 shadow-xl shadow-slate-900/5">
                <div class="flex items-center justify-between px-5 py-4">
                    <p class="font-bold text-slate-900">Semester {{ currentSemester }}</p>
                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700">{{ semesterMk.length }} MK · {{ semesterSks }} SKS</span>
                </div>
                <TransitionGroup tag="ul" class="space-y-1" enter-from-class="opacity-0 translate-x-2" enter-active-class="transition duration-300">
                    <li v-for="mk in semesterMk" :key="mk.kode_mk" class="flex items-center gap-4 rounded-2xl px-5 py-4 transition hover:bg-slate-50">
                        <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-slate-100 font-mono text-[11px] font-bold text-slate-600">
                            {{ mk.sks }}<span class="-mt-1 text-[8px]">SKS</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-slate-900">{{ mk.nama_mk }}</p>
                            <p class="truncate text-xs text-slate-500">{{ mk.kode_mk }} · {{ mk.pengampu ?? 'Pengampu belum ditentukan' }}</p>
                        </div>
                        <StatusBadge :value="mk.jenis" />
                    </li>
                </TransitionGroup>
                <p v-if="!semesterMk.length" class="px-5 py-10 text-center text-sm text-slate-500"><Layers class="mx-auto mb-2 size-6" /> Belum ada mata kuliah.</p>
            </div>
        </div>
    </section>

    <!-- ============ CTA ============ -->
    <section class="mx-auto max-w-7xl px-4 pb-24 sm:px-6">
        <div class="relative overflow-hidden rounded-[2rem] bg-slate-950 px-6 py-16 text-center sm:px-16">
            <div class="bg-grid absolute inset-0 opacity-60"></div>
            <div class="absolute -top-24 left-1/2 size-96 -translate-x-1/2 rounded-full bg-indigo-600/40 blur-3xl"></div>
            <div class="relative">
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Siap mengelola data Prodi TPL?</h2>
                <p class="mx-auto mt-4 max-w-xl text-slate-400">Masuk sebagai admin untuk menambah, mengubah, dan menghapus data dosen, project mahasiswa, dan mata kuliah.</p>
                <Link :href="ctaUrl" class="btn mt-8 bg-white px-7 py-3.5 text-base text-slate-900 shadow-xl hover:bg-slate-100">
                    <LogIn class="size-5" /> {{ isAuth ? 'Buka Dashboard' : 'Sign In sebagai Admin' }}
                </Link>
            </div>
        </div>
    </section>

    <!-- ============ FOOTER ============ -->
    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 py-8 text-sm text-slate-500 sm:flex-row sm:px-6">
            <p>&copy; {{ new Date().getFullYear() }} Program Studi <span class="font-semibold text-slate-700">Teknik Perangkat Lunak</span></p>
            <div class="flex gap-6">
                <a href="#project" class="hover:text-indigo-600">Project</a>
                <a href="#dosen" class="hover:text-indigo-600">Dosen</a>
                <a href="#kurikulum" class="hover:text-indigo-600">Kurikulum</a>
                <Link :href="ctaUrl" class="font-semibold text-indigo-600">{{ ctaLabel }}</Link>
            </div>
        </div>
    </footer>
</template>
