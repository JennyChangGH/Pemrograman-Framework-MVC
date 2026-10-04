import './bootstrap';
import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h } from 'vue';
import AppLayout from './layouts/AppLayout.vue';


createInertiaApp({
    title: (title) => (title ? `${title} · Prodi TPL` : 'Prodi TPL'),
    resolve: (name) => {
        const pages = import.meta.glob('./pages/**/*.vue', { eager: true });
        const page = pages[`./pages/${name}.vue`];

        if (!page) {
            throw new Error(`Halaman Vue "${name}" tidak ditemukan.`);
        }

        page.default.layout ??= AppLayout;

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: { color: '#6366f1' },
});
