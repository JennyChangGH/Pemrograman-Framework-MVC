<script setup>
import { computed } from 'vue';
import { gradientFor, initials } from '../lib/helpers';

const props = defineProps({
    name: { type: String, required: true },
    size: { type: String, default: 'size-10 text-sm' },
});

const letters = computed(() => initials(props.name));
const gradient = computed(() => gradientFor(props.name));
</script>

<template>
    <div
        class="grid shrink-0 place-items-center rounded-full bg-gradient-to-br font-bold text-white ring-2 ring-white"
        :class="[size, gradient]"
        :title="name"
    >
        {{ letters }}
    </div>
</template>
