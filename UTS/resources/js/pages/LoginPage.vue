<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, BookOpen, CircleAlert, Eye, EyeOff, FolderKanban, Info, LoaderCircle, Lock, LogIn, Mail, Users } from '@lucide/vue';
import { ref } from 'vue';
import AppLogo from '../components/AppLogo.vue';

const form = useForm({ email: '', password: '', remember: false });
const showPassword = ref(false);

const submit = () => form.post('/login', { onFinish: () => form.reset('password') });

const features = [
    { icon: Users, title: 'Data Dosen', text: 'Kelola profil, status, dan pendidikan terakhir dosen.' },
    { icon: FolderKanban, title: 'Project Mahasiswa', text: 'Pantau progres project dari proposal hingga selesai.' },
    { icon: BookOpen, title: 'Mata Kuliah', text: 'Atur kurikulum, SKS, semester, dan dosen pengampu.' },
];
</script>

<template>
    <Head title="Sign In" />
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="relative hidden overflow-hidden bg-slate-950 p-12 lg:flex lg:flex-col">
            <div class="bg-grid absolute inset-0 [mask-image:radial-gradient(ellipse_at_center,black_30%,transparent_80%)]"></div>
            <div class="absolute -top-20 -left-20 size-96 animate-float rounded-full bg-indigo-600/30 blur-3xl"></div>
            <div class="absolute -right-10 bottom-10 size-80 animate-float rounded-full bg-cyan-500/20 blur-3xl [animation-delay:-3s]"></div>

            <Link href="/" class="relative"><AppLogo dark /></Link>

            <div class="relative my-auto max-w-lg">
                <p class="text-lg text-slate-400">Masuk untuk mulai mengelola data Program Studi Teknik Perangkat Lunak.</p>

                <ul class="mt-8 space-y-4">
                    <li
                        v-for="(f, i) in features"
                        :key="f.title"
                        class="flex animate-fade-up items-start gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur"
                        :style="{ animationDelay: `${i * 120}ms` }"
                    >
                        <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white shadow-lg shadow-indigo-500/30">
                            <component :is="f.icon" class="size-5" />
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ f.title }}</p>
                            <p class="text-sm text-slate-400">{{ f.text }}</p>
                        </div>
                    </li>
                </ul>
            </div>

            <p class="relative text-xs text-slate-500">© {{ new Date().getFullYear() }} Program Studi Teknik Perangkat Lunak</p>
        </div>

        <div class="flex flex-col bg-white px-4 py-8 sm:px-12">
            <Link href="/" class="inline-flex w-fit items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-500 transition hover:bg-slate-100 hover:text-slate-800">
                <ArrowLeft class="size-4" /> Kembali ke beranda
            </Link>

            <div class="mx-auto my-auto w-full max-w-md animate-fade-up py-10">
                <div class="lg:hidden"><AppLogo /></div>
                <h2 class="mt-8 text-3xl font-extrabold tracking-tight text-slate-900 lg:mt-0">Selamat datang</h2>
                <p class="mt-2 text-slate-500">Silakan sign in dengan akun admin Anda.</p>

                <div v-if="form.errors.email || form.errors.password" class="mt-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    <CircleAlert class="mt-0.5 size-5 shrink-0" />
                    <p>{{ form.errors.email ?? form.errors.password }}</p>
                </div>

                <form class="mt-8 space-y-5" @submit.prevent="submit">

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                        <div class="relative">
                            <Mail class="pointer-events-none absolute top-1/2 left-4 size-[18px] -translate-y-1/2 text-slate-400" />
                            <input id="email" v-model="form.email" name="email" type="email" autocomplete="username" required autofocus class="input py-3 pl-11" placeholder="admin@tpl.ac.id" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                        <div class="relative">
                            <Lock class="pointer-events-none absolute top-1/2 left-4 size-[18px] -translate-y-1/2 text-slate-400" />
                            <input
                                id="password"
                                v-model="form.password"
                                name="password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password"
                                required
                                class="input py-3 pr-12 pl-11"
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                class="absolute top-1/2 right-2 -translate-y-1/2 rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600"
                                :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                                @click="showPassword = !showPassword"
                            >
                                <EyeOff v-if="showPassword" class="size-[18px]" />
                                <Eye v-else class="size-[18px]" />
                            </button>
                        </div>
                    </div>

                    <label class="flex cursor-pointer items-center gap-2.5 text-sm text-slate-600">
                        <input v-model="form.remember" type="checkbox" name="remember" class="size-4 rounded border-slate-300 accent-indigo-600" />
                        Ingat saya
                    </label>

                    <button type="submit" class="btn btn-primary w-full py-3.5 text-base" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="size-5 animate-spin" />
                        <LogIn v-else class="size-5" />
                        {{ form.processing ? 'Memproses...' : 'Sign In' }}
                    </button>
                </form>

                <div class="mt-8 flex gap-3 rounded-2xl border border-indigo-100 bg-indigo-50/60 p-4 text-sm">
                    <Info class="mt-0.5 size-5 shrink-0 text-indigo-600" />
                    <div class="text-slate-600">
                        <p class="font-semibold text-slate-800">Akun demo</p>
                        <p>Email: <code class="font-semibold text-indigo-700">admin@tpl.ac.id</code></p>
                        <p>Password: <code class="font-semibold text-indigo-700">password</code></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
