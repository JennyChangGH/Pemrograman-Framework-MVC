<script setup>
import { badgeColors } from '../lib/helpers';

defineProps({
    value: { type: [String, Number], default: '' },
});
</script>

<template>
    <span
        class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
        :class="badgeColors[value] ?? 'bg-slate-50 text-slate-600 ring-slate-500/20'"
    >
        <span class="size-1.5 rounded-full bg-current opacity-70"></span>
        {{ value }}
    </span>
</template>x
