<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { BookOpen, ExternalLink, FolderKanban, LayoutDashboard, LogOut, Menu, Users, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import AppLogo from './AppLogo.vue';
import UserAvatar from './UserAvatar.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? { name: '', email: '' });
const counts = computed(() => page.props.menuCounts ?? {});

const menu = computed(() => [
    { label: 'Dashboard', url: '/dashboard', icon: LayoutDashboard, prefix: '/dashboard' },
    { label: 'Dosen', url: '/dosen', icon: Users, prefix: '/dosen', count: counts.value.dosen },
    { label: 'Project Mahasiswa', url: '/project', icon: FolderKanban, prefix: '/project', count: counts.value.project },
    { label: 'Mata Kuliah', url: '/matakuliah', icon: BookOpen, prefix: '/matakuliah', count: counts.value.matakuliah },
]);

const isActive = (item) => page.url === item.prefix || page.url.startsWith(`${item.prefix}/`) || page.url.startsWith(`${item.prefix}?`);

const open = ref(false);
const logout = () => router.post('/logout');
</script>

<template>
    <div class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-800 bg-slate-950 px-4 py-3 lg:hidden">
        <AppLogo dark />
        <button class="rounded-xl p-2 text-slate-300 hover:bg-white/10" aria-label="Buka menu" @click="open = true">
            <Menu class="size-6" />
        </button>
    </div>

    <Transition enter-from-class="opacity-0" enter-active-class="transition" leave-to-class="opacity-0" leave-active-class="transition">
        <div v-if="open" class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden" @click="open = false"></div>
    </Transition>


    <aside
        class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col overflow-hidden bg-slate-950 transition-transform duration-300 lg:translate-x-0"
        :class="open ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="pointer-events-none absolute -top-24 -left-24 size-72 rounded-full bg-indigo-600/25 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 bottom-10 size-60 rounded-full bg-cyan-500/10 blur-3xl"></div>

        <div class="relative flex items-center justify-between px-6 pt-6 pb-8">
            <AppLogo dark />
            <button class="rounded-lg p-1.5 text-slate-400 hover:bg-white/10 lg:hidden" aria-label="Tutup menu" @click="open = false">
                <X class="size-5" />
            </button>
        </div>

        <nav class="relative flex-1 space-y-1 overflow-y-auto px-4">
            <p class="px-3 pb-2 text-[11px] font-semibold tracking-widest text-slate-500 uppercase">Menu Utama</p>
            <Link
                v-for="item in menu"
                :key="item.url"
                :href="item.url"
                class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition"
                :class="isActive(item) ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                @click="open = false"
            >
                <span v-if="isActive(item)" class="absolute top-2 bottom-2 left-0 w-1 rounded-r-full bg-gradient-to-b from-indigo-400 to-cyan-400"></span>
                <span
                    class="grid size-9 place-items-center rounded-lg transition"
                    :class="isActive(item) ? 'bg-gradient-to-br from-indigo-500 to-violet-500 text-white shadow-lg shadow-indigo-500/30' : 'bg-white/5 group-hover:bg-white/10'"
                >
                    <component :is="item.icon" class="size-[18px]" />
                </span>
                <span class="flex-1">{{ item.label }}</span>
                <span v-if="item.count !== undefined" class="rounded-full bg-white/10 px-2 py-0.5 text-[11px] font-semibold text-slate-300">
                    {{ item.count }}
                </span>
            </Link>

            <p class="px-3 pt-6 pb-2 text-[11px] font-semibold tracking-widest text-slate-500 uppercase">Lainnya</p>
            <Link href="/" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 transition hover:bg-white/5 hover:text-white">
                <span class="grid size-9 place-items-center rounded-lg bg-white/5"><ExternalLink class="size-[18px]" /></span>
                Lihat Website
            </Link>
        </nav>

        <div class="relative m-4 rounded-2xl border border-white/10 bg-white/5 p-3">
            <div class="flex items-center gap-3">
                <UserAvatar :name="user.name" />
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ user.name }}</p>
                    <p class="truncate text-xs text-slate-400">{{ user.email }}</p>
                </div>
                <button type="button" class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-500/15 hover:text-rose-400" title="Keluar" @click="logout">
                    <LogOut class="size-[18px]" />
                </button>
            </div>
        </div>
    </aside>
</template>
