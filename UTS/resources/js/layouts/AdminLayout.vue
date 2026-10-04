<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminSidebar from '../components/AdminSidebar.vue';

defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
    breadcrumb: { type: Object, default: null }, 
});
</script>

<template>
    <Head :title="title" />

    <AdminSidebar />

    <div class="flex min-h-screen flex-col lg:pl-72">
        <header class="px-4 pt-6 pb-6 sm:px-8 sm:pt-8">
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-400">
                <Link href="/dashboard" class="hover:text-indigo-600">Admin</Link>
                <template v-if="breadcrumb">
                    <span>/</span>
                    <Link :href="breadcrumb.href" class="hover:text-indigo-600">{{ breadcrumb.label }}</Link>
                </template>
                <span>/</span>
                <span class="text-slate-600">{{ title }}</span>
            </nav>
            <div class="mt-2 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ title }}</h1>
                    <p v-if="subtitle" class="mt-1 text-sm text-slate-500">{{ subtitle }}</p>
                </div>
                <slot name="actions" />
            </div>
        </header>

        <main class="flex-1 px-4 pb-10 sm:px-8">
            <slot />
        </main>

        <footer class="border-t border-slate-200/70 px-4 py-5 text-xs text-slate-400 sm:px-8">
            <div class="flex flex-col justify-between gap-1 sm:flex-row">
                <p>&copy; {{ new Date().getFullYear() }} Program Studi Teknik Perangkat Lunak. All rights reserved.</p>
                <p>Dibangun dengan Laravel, Inertia &amp; Vue 3</p>
            </div>
        </footer>
    </div>
</template>
