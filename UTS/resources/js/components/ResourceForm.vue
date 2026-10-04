<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ChevronDown, CircleAlert, LoaderCircle, Save } from '@lucide/vue';
import { computed } from 'vue';

const props = defineProps({
    action: { type: String, required: true },
    method: { type: String, default: 'post' }, // 'post' | 'put'
    sections: { type: Array, required: true },
    values: { type: Object, default: () => ({}) },
    submitLabel: { type: String, default: 'Simpan' },
    cancelUrl: { type: String, default: '' },
});

const form = useForm(
    Object.fromEntries(props.sections.flatMap((s) => s.fields).map((f) => [f.name, String(props.values[f.name] ?? '')])),
);

const errorCount = computed(() => Object.keys(form.errors).length);

function submit() {
    form[props.method](props.action);
}
</script>

<template>
    <form novalidate class="space-y-6" @submit.prevent="submit">
        <div
            v-if="errorCount"
            class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700"
        >
            <CircleAlert class="mt-0.5 size-5 shrink-0" />
            <div>
                <p class="font-semibold">Periksa kembali isian Anda</p>
                <p class="text-rose-600/90">Terdapat {{ errorCount }} isian yang belum sesuai.</p>
            </div>
        </div>

        <section v-for="section in sections" :key="section.title" class="card p-5 sm:p-7">
            <div class="mb-6 border-b border-slate-100 pb-4">
                <h2 class="text-base font-bold text-slate-900">{{ section.title }}</h2>
                <p v-if="section.description" class="mt-1 text-sm text-slate-500">{{ section.description }}</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div v-for="field in section.fields" :key="field.name" :class="field.span === 'full' && 'sm:col-span-2'">
                    <label :for="field.name" class="mb-1.5 block text-sm font-semibold text-slate-700">
                        {{ field.label }}
                        <span v-if="field.required" class="text-rose-500">*</span>
                    </label>

                    <div v-if="field.type === 'select'" class="relative">
                        <select
                            :id="field.name"
                            v-model="form[field.name]"
                            :name="field.name"
                            class="input appearance-none pr-10"
                            :class="form.errors[field.name] && 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/15'"
                            @change="form.clearErrors(field.name)"
                        >
                            <option value="">{{ field.placeholder ?? '— Pilih —' }}</option>
                            <option v-for="opt in field.options" :key="opt.value" :value="String(opt.value)">{{ opt.label }}</option>
                        </select>
                        <ChevronDown class="pointer-events-none absolute top-1/2 right-3.5 size-4 -translate-y-1/2 text-slate-400" />
                    </div>

                    <div v-else-if="field.type === 'textarea'">
                        <textarea
                            :id="field.name"
                            v-model="form[field.name]"
                            :name="field.name"
                            rows="4"
                            :maxlength="field.maxlength"
                            class="input resize-y"
                            :class="form.errors[field.name] && 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/15'"
                            :placeholder="field.placeholder"
                            @input="form.clearErrors(field.name)"
                        ></textarea>
                        <p v-if="field.maxlength" class="mt-1 text-right text-xs text-slate-400">
                            {{ String(form[field.name] ?? '').length }}/{{ field.maxlength }}
                        </p>
                    </div>

                    <input
                        v-else
                        :id="field.name"
                        v-model="form[field.name]"
                        :name="field.name"
                        :type="field.type ?? 'text'"
                        :min="field.min"
                        :max="field.max"
                        class="input"
                        :class="form.errors[field.name] && 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/15'"
                        :placeholder="field.placeholder"
                        @input="form.clearErrors(field.name)"
                    />

                    <p v-if="form.errors[field.name]" class="mt-1.5 flex items-center gap-1 text-xs font-medium text-rose-600">
                        <CircleAlert class="size-3.5" /> {{ form.errors[field.name] }}
                    </p>
                    <p v-else-if="field.hint" class="mt-1.5 text-xs text-slate-400">{{ field.hint }}</p>
                </div>
            </div>
        </section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <Link v-if="cancelUrl" :href="cancelUrl" class="btn btn-ghost">Batal</Link>
            <button type="submit" class="btn btn-primary min-w-40" :disabled="form.processing">
                <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                <Save v-else class="size-4" />
                {{ form.processing ? 'Menyimpan...' : submitLabel }}
            </button>
        </div>
    </form>
</template>
