<script setup>
import { router } from '@inertiajs/vue3';
import { Trash2 } from '@lucide/vue';
import { ref } from 'vue';
import ConfirmModal from './ConfirmModal.vue';

const props = defineProps({
    action: { type: String, required: true },
    label: { type: String, required: true },
    entity: { type: String, default: 'data' },
});

const open = ref(false);
const processing = ref(false);

function destroy() {
    router.delete(props.action, {
        onStart: () => (processing.value = true),
        onFinish: () => {
            processing.value = false;
            open.value = false;
        },
    });
}
</script>

<template>
    <button type="button" class="btn border border-rose-200 bg-white text-rose-600 hover:bg-rose-50" @click="open = true">
        <Trash2 class="size-4" /> Hapus
    </button>
    <ConfirmModal :open="open" :processing="processing" :title="`Hapus ${entity}?`" @close="open = false" @confirm="destroy">
        Data <strong class="text-slate-800">{{ label }}</strong> akan dihapus permanen dan tidak dapat dikembalikan.
    </ConfirmModal>
</template>
