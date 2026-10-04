<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({
    value: { type: Number, default: 0 },
    duration: { type: Number, default: 1400 },
});

const el = ref(null);
const display = ref(0);
let observer;

function animate() {
    const start = performance.now();
    const tick = (now) => {
        const t = Math.min((now - start) / props.duration, 1);
        const eased = 1 - Math.pow(1 - t, 3);
        display.value = Math.round(props.value * eased);
        if (t < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

onMounted(() => {
    observer = new IntersectionObserver(([entry]) => {
        if (entry.isIntersecting) {
            animate();
            observer.disconnect();
        }
    });
    observer.observe(el.value);
});

onBeforeUnmount(() => observer?.disconnect());
</script>

<template>
    <span ref="el" class="tabular-nums">{{ display.toLocaleString('id-ID') }}</span>
</template>
