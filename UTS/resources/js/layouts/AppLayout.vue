<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import FlashToast from '../components/FlashToast.vue';

const page = usePage();
const toast = ref(null);
let counter = 0;

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.error) toast.value = { id: ++counter, type: 'error', message: flash.error };
        else if (flash?.success) toast.value = { id: ++counter, type: 'success', message: flash.success };
    },
    { immediate: true },
);
</script>

<template>
    <slot />
    <FlashToast v-if="toast" :key="toast.id" :message="toast.message" :type="toast.type" />
</template>
