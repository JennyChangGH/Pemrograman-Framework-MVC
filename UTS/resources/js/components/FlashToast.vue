<script setup>
import { CircleCheck, TriangleAlert, X } from '@lucide/vue';
import { onMounted, ref } from 'vue';

const props = defineProps({
    message: { type: String, required: true },
    type: { type: String, default: 'success' },
});

const visible = ref(false);

onMounted(() => {
    requestAnimationFrame(() => (visible.value = true));
    setTimeout(() => (visible.value = false), 5000);
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-from-class="translate-y-4 opacity-0 sm:translate-x-4 sm:translate-y-0"
            enter-active-class="transition duration-300 ease-out"
            leave-to-class="opacity-0"
            leave-active-class="transition duration-200 ease-in"
        >
            <div
                v-if="visible"
                role="status"
                class="fixed right-4 bottom-4 left-4 z-[60] flex items-start gap-3 rounded-2xl border bg-white p-4 shadow-2xl shadow-slate-900/10 sm:left-auto sm:top-5 sm:bottom-auto sm:w-96"
                :class="props.type === 'success' ? 'border-emerald-100' : 'border-rose-100'"
            >
                <div
                    class="grid size-9 shrink-0 place-items-center rounded-xl"
                    :class="props.type === 'success' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'"
                >
                    <CircleCheck v-if="props.type === 'success'" class="size-5" />
                    <TriangleAlert v-else class="size-5" />
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-bold text-slate-900">{{ props.type === 'success' ? 'Berhasil' : 'Perhatian' }}</p>
                    <p class="mt-0.5 text-sm text-slate-600">{{ message }}</p>
                </div>
                <button class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" aria-label="Tutup" @click="visible = false">
                    <X class="size-4" />
                </button>
            </div>
        </Transition>
    </Teleport>
</template>
