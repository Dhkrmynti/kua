<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login', { onFinish: () => form.reset('password') });
}
</script>

<template>
    <Head title="Masuk" />

    <PublicLayout>
        <section class="mx-auto max-w-md px-4 py-16">
            <div class="card p-8">
                <h1 class="text-2xl font-extrabold text-stone-900">Masuk</h1>
                <p class="mt-1 text-sm text-stone-500">Masuk untuk mengelola pengajuan dan pengaduan Anda.</p>

                <form class="mt-6 space-y-4" @submit.prevent="submit">
                    <div>
                        <label for="email" class="label">Email</label>
                        <input id="email" v-model="form.email" type="email" class="input" :class="{ 'input-error': form.errors.email }" autocomplete="username" required autofocus />
                        <p v-if="form.errors.email" class="error-text">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label for="password" class="label">Kata sandi</label>
                        <input id="password" v-model="form.password" type="password" class="input" autocomplete="current-password" required />
                        <p v-if="form.errors.password" class="error-text">{{ form.errors.password }}</p>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-stone-600">
                        <input v-model="form.remember" type="checkbox" class="size-4 rounded border-stone-300 accent-brand-600" />
                        Ingat saya
                    </label>
                    <button type="submit" class="btn-primary w-full" :disabled="form.processing">Masuk</button>
                </form>

                <p class="mt-6 text-center text-sm text-stone-500">
                    Belum punya akun?
                    <Link href="/register" class="font-semibold text-brand-700 hover:underline">Daftar sekarang</Link>
                </p>
            </div>
        </section>
    </PublicLayout>
</template>
