<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ArrowDown, ArrowUp, ChevronLeft, ChevronRight, ChevronsUpDown, Eye, Inbox, Pencil, Plus, Search, Trash2 } from '@lucide/vue';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import ConfirmModal from './ConfirmModal.vue';
import StatusBadge from './StatusBadge.vue';
import UserAvatar from './UserAvatar.vue';

const props = defineProps({
    columns: { type: Array, required: true },
    rows: { type: Array, default: () => [] },
    searchKeys: { type: Array, default: () => [] },
    searchPlaceholder: { type: String, default: 'Cari data...' },
    filter: { type: Object, default: null },
    baseUrl: { type: String, required: true }, 
    createLabel: { type: String, default: 'Tambah Data' },
    entity: { type: String, default: 'data' },
    perPage: { type: Number, default: 8 },
});

const query = ref('');
const activeFilter = ref('');
const sortKey = ref('');
const sortDir = ref('asc');
const page = ref(1);
const toDelete = ref(null);
const deleting = ref(false);

function destroy() {
    router.delete(`${props.baseUrl}/${toDelete.value.id}`, {
        preserveScroll: true,
        onStart: () => (deleting.value = true),
        onFinish: () => {
            deleting.value = false;
            toDelete.value = null;
        },
    });
}

const get = (row, key) => key.split('.').reduce((v, k) => v?.[k], row);

const filtered = computed(() => {
    const q = query.value.trim().toLowerCase();
    let result = props.rows.filter((row) => {
        if (activeFilter.value && String(get(row, props.filter.key)) !== activeFilter.value) return false;
        if (!q) return true;
        return props.searchKeys.some((k) => String(get(row, k) ?? '').toLowerCase().includes(q));
    });

    if (sortKey.value) {
        const dir = sortDir.value === 'asc' ? 1 : -1;
        result = [...result].sort((a, b) => {
            const x = get(a, sortKey.value) ?? '';
            const y = get(b, sortKey.value) ?? '';
            return (typeof x === 'number' && typeof y === 'number' ? x - y : String(x).localeCompare(String(y), 'id')) * dir;
        });
    }
    return result;
});

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / props.perPage)));
const paged = computed(() => filtered.value.slice((page.value - 1) * props.perPage, page.value * props.perPage));
const rangeText = computed(() => {
    if (!filtered.value.length) return '0 data';
    const from = (page.value - 1) * props.perPage + 1;
    const to = Math.min(page.value * props.perPage, filtered.value.length);
    return `${from}–${to} dari ${filtered.value.length} data`;
});

watch([query, activeFilter], () => (page.value = 1));

function toggleSort(col) {
    if (col.sortable === false) return;
    if (sortKey.value === col.key) {
        sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortKey.value = col.key;
        sortDir.value = 'asc';
    }
}

const onKey = (e) => e.key === 'Escape' && (toDelete.value = null);
onMounted(() => window.addEventListener('keydown', onKey));
onBeforeUnmount(() => window.removeEventListener('keydown', onKey));
</script>

<template>
    <div class="card overflow-hidden">
        <!-- Toolbar -->
        <div class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:p-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                <label class="relative block w-full sm:max-w-xs">
                    <Search class="pointer-events-none absolute top-1/2 left-3.5 size-4 -translate-y-1/2 text-slate-400" />
                    <input v-model="query" type="search" class="input pl-10" :placeholder="searchPlaceholder" />
                </label>
                <div v-if="filter" class="flex gap-1.5 overflow-x-auto rounded-xl bg-slate-100 p-1">
                    <button
                        v-for="opt in ['', ...filter.options]"
                        :key="opt"
                        type="button"
                        class="whitespace-nowrap rounded-lg px-3 py-1.5 text-xs font-semibold transition"
                        :class="activeFilter === String(opt) ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                        @click="activeFilter = String(opt)"
                    >
                        {{ opt === '' ? 'Semua' : (filter.prefix ?? '') + opt }}
                    </button>
                </div>
            </div>
            <Link :href="`${baseUrl}/create`" class="btn btn-primary shrink-0">
                <Plus class="size-4" /> {{ createLabel }}
            </Link>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        <th class="w-12 px-5 py-3.5">#</th>
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            class="px-5 py-3.5 whitespace-nowrap select-none"
                            :class="[col.sortable !== false && 'cursor-pointer hover:text-slate-800', col.align === 'center' && 'text-center']"
                            @click="toggleSort(col)"
                        >
                            <span class="inline-flex items-center gap-1.5">
                                {{ col.label }}
                                <template v-if="col.sortable !== false">
                                    <ArrowUp v-if="sortKey === col.key && sortDir === 'asc'" class="size-3.5 text-indigo-600" />
                                    <ArrowDown v-else-if="sortKey === col.key" class="size-3.5 text-indigo-600" />
                                    <ChevronsUpDown v-else class="size-3.5 opacity-40" />
                                </template>
                            </span>
                        </th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <TransitionGroup tag="tbody" class="divide-y divide-slate-100" enter-from-class="opacity-0" enter-active-class="transition duration-300">
                    <tr v-for="(row, i) in paged" :key="row.id" class="group transition hover:bg-indigo-50/40">
                        <td class="px-5 py-4 text-slate-400 tabular-nums">{{ (page - 1) * perPage + i + 1 }}</td>
                        <td v-for="col in columns" :key="col.key" class="px-5 py-4" :class="col.align === 'center' && 'text-center'">
                            <div v-if="col.type === 'avatar'" class="flex items-center gap-3">
                                <UserAvatar :name="String(get(row, col.key))" />
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-slate-900">{{ get(row, col.key) }}</p>
                                    <p v-if="col.sub" class="truncate text-xs text-slate-500">{{ get(row, col.sub) }}</p>
                                </div>
                            </div>
                            <div v-else-if="col.type === 'title'" class="max-w-xs">
                                <p class="line-clamp-1 font-semibold text-slate-900">{{ get(row, col.key) }}</p>
                                <p v-if="col.sub" class="line-clamp-1 text-xs text-slate-500">{{ get(row, col.sub) }}</p>
                            </div>
                            <StatusBadge v-else-if="col.type === 'badge'" :value="get(row, col.key)" />
                            <span v-else-if="col.type === 'mono'" class="rounded-md bg-slate-100 px-2 py-1 font-mono text-xs font-semibold text-slate-700">
                                {{ get(row, col.key) }}
                            </span>
                            <span v-else-if="col.type === 'pill'" class="inline-grid min-w-8 place-items-center rounded-lg bg-indigo-50 px-2 py-1 text-xs font-bold text-indigo-700">
                                {{ get(row, col.key) }}
                            </span>
                            <span v-else :class="get(row, col.key) == null ? 'text-slate-400 italic' : 'text-slate-600'">
                                {{ get(row, col.key) ?? '—' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-1">
                                <Link :href="`${baseUrl}/${row.id}`" class="rounded-lg p-2 text-slate-400 transition hover:bg-sky-50 hover:text-sky-600" title="Detail">
                                    <Eye class="size-4" />
                                </Link>
                                <Link :href="`${baseUrl}/${row.id}/edit`" class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600" title="Ubah">
                                    <Pencil class="size-4" />
                                </Link>
                                <button type="button" class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600" title="Hapus" @click="toDelete = row">
                                    <Trash2 class="size-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </TransitionGroup>
            </table>

            <div v-if="!paged.length" class="flex flex-col items-center px-6 py-16 text-center">
                <div class="grid size-16 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                    <Inbox class="size-7" />
                </div>
                <p class="mt-4 font-semibold text-slate-800">{{ rows.length ? 'Tidak ada data yang cocok' : `Belum ada data ${entity}` }}</p>
                <p class="mt-1 text-sm text-slate-500">
                    {{ rows.length ? 'Coba ubah kata kunci pencarian atau filter.' : 'Klik tombol tambah untuk membuat data pertama.' }}
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3 border-t border-slate-100 px-5 py-3.5 text-sm">
            <p class="text-slate-500">Menampilkan {{ rangeText }}</p>
            <div class="flex items-center gap-1">
                <button class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 disabled:opacity-40" :disabled="page === 1" aria-label="Sebelumnya" @click="page--">
                    <ChevronLeft class="size-4" />
                </button>
                <button
                    v-for="n in totalPages"
                    :key="n"
                    class="size-8 rounded-lg text-xs font-semibold transition"
                    :class="n === page ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'text-slate-600 hover:bg-slate-100'"
                    @click="page = n"
                >
                    {{ n }}
                </button>
                <button class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 disabled:opacity-40" :disabled="page === totalPages" aria-label="Berikutnya" @click="page++">
                    <ChevronRight class="size-4" />
                </button>
            </div>
        </div>

        <ConfirmModal :open="!!toDelete" :processing="deleting" :title="`Hapus ${entity}?`" @close="toDelete = null" @confirm="destroy">
            Data <strong class="text-slate-800">{{ toDelete?.label }}</strong> akan dihapus permanen dan tidak dapat dikembalikan.
        </ConfirmModal>
    </div>
</template>
