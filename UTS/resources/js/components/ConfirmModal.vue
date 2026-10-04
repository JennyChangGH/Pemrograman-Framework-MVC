<script setup>
import { LoaderCircle, Trash2 } from '@lucide/vue';

defineProps({
    open: { type: Boolean, default: false },
    title: { type: String, default: 'Hapus data?' },
    message: { type: String, default: '' },
    processing: { type: Boolean, default: false },
});

const emit = defineEmits(['close', 'confirm']);
</script>

<template>
    <Teleport to="body">
        <Transition enter-from-class="opacity-0" enter-active-class="transition duration-200" leave-to-class="opacity-0" leave-active-class="transition duration-150">
            <div v-if="open" class="fixed inset-0 z-50 grid place-items-center bg-slate-950/50 p-4 backdrop-blur-sm" @click.self="emit('close')" @keydown.esc="emit('close')">
                <div class="w-full max-w-md animate-fade-up rounded-3xl bg-white p-6 shadow-2xl" role="dialog" aria-modal="true">
                    <div class="mx-auto grid size-14 place-items-center rounded-2xl bg-rose-50 text-rose-600 ring-8 ring-rose-50/50">
                        <Trash2 class="size-6" />
                    </div>
                    <h3 class="mt-5 text-center text-lg font-bold text-slate-900">{{ title }}</h3>
                    <p class="mt-2 text-center text-sm text-slate-500">
                        <slot>{{ message }}</slot>
                    </p>
                    <div class="mt-6 flex gap-3">
                        <button type="button" class="btn btn-ghost flex-1" @click="emit('close')">Batal</button>
                        <button type="button" class="btn btn-danger flex-1" :disabled="processing" @click="emit('confirm')">
                            <LoaderCircle v-if="processing" class="size-4 animate-spin" />
                            Ya, hapus
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
