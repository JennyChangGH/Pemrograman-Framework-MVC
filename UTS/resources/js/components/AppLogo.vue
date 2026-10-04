<script setup>
defineProps({
    dark: { type: Boolean, default: false },
});
</script>

<template>
    <div class="flex items-center gap-3">
        <div
            class="relative grid size-10 place-items-center rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-cyan-400 shadow-lg shadow-indigo-500/30"
        >
            <svg viewBox="0 0 24 24" class="size-5 text-white" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <path d="m8 7-5 5 5 5" />
                <path d="m16 7 5 5-5 5" />
                <path d="m13.5 4-3 16" />
            </svg>
        </div>
        <div class="leading-tight">
            <p class="text-base font-extrabold tracking-tight" :class="dark ? 'text-white' : 'text-slate-900'">TPL Admin</p>
            <p class="text-[11px] font-medium" :class="dark ? 'text-slate-400' : 'text-slate-500'">Teknik Perangkat Lunak</p>
        </div>
    </div>
</template>
