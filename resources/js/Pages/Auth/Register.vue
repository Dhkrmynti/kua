<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const form = useForm({
    name: '',
    nik: '',
    email: '',
    telepon: '',
    alamat: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/register', { onFinish: () => form.reset('password', 'password_confirmation') });
}
</script>

<template>
    <Head title="Daftar" />

    <PublicLayout>
        <section class="mx-auto max-w-xl px-4 py-16">
            <div class="card p-8">
                <h1 class="text-2xl font-extrabold text-stone-900">Daftar Akun</h1>
                <p class="mt-1 text-sm text-stone-500">Gunakan data sesuai KTP. Data ini dipakai sebagai identitas pemohon.</p>

                <form class="mt-6 grid gap-4 sm:grid-cols-2" @submit.prevent="submit">
                    <div class="sm:col-span-2">
                        <label for="name" class="label">Nama lengkap</label>
                        <input id="name" v-model="form.name" type="text" class="input" :class="{ 'input-error': form.errors.name }" required />
                        <p v-if="form.errors.name" class="error-text">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label for="nik" class="label">NIK</label>
                        <input id="nik" v-model="form.nik" type="text" inputmode="numeric" maxlength="16" class="input" :class="{ 'input-error': form.errors.nik }" required />
                        <p v-if="form.errors.nik" class="error-text">{{ form.errors.nik }}</p>
                    </div>
                    <div>
                        <label for="telepon" class="label">No. HP / WhatsApp</label>
                        <input id="telepon" v-model="form.telepon" type="tel" class="input" :class="{ 'input-error': form.errors.telepon }" required />
                        <p v-if="form.errors.telepon" class="error-text">{{ form.errors.telepon }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="label">Email</label>
                        <input id="email" v-model="form.email" type="email" class="input" :class="{ 'input-error': form.errors.email }" autocomplete="email" required />
                        <p v-if="form.errors.email" class="error-text">{{ form.errors.email }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="alamat" class="label">Alamat</label>
                        <textarea id="alamat" v-model="form.alamat" rows="2" class="input" :class="{ 'input-error': form.errors.alamat }" required />
                        <p v-if="form.errors.alamat" class="error-text">{{ form.errors.alamat }}</p>
                    </div>
                    <div>
                        <label for="password" class="label">Kata sandi</label>
                        <input id="password" v-model="form.password" type="password" class="input" :class="{ 'input-error': form.errors.password }" autocomplete="new-password" required />
                        <p v-if="form.errors.password" class="error-text">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <label for="password_confirmation" class="label">Ulangi kata sandi</label>
                        <input id="password_confirmation" v-model="form.password_confirmation" type="password" class="input" autocomplete="new-password" required />
                    </div>
                    <button type="submit" class="btn-primary sm:col-span-2" :disabled="form.processing">Daftar</button>
                </form>

                <p class="mt-6 text-center text-sm text-stone-500">
                    Sudah punya akun?
                    <Link href="/login" class="font-semibold text-brand-700 hover:underline">Masuk</Link>
                </p>
            </div>
        </section>
    </PublicLayout>
</template>
