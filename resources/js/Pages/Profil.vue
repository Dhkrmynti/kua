<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    profil: Object,
});

const form = useForm({
    name: props.profil.name,
    email: props.profil.email,
    telepon: props.profil.telepon ?? '',
    alamat: props.profil.alamat ?? '',
    password_lama: '',
    password: '',
    password_confirmation: '',
});

function simpan() {
    form.put('/profil', {
        preserveScroll: true,
        onSuccess: () => form.reset('password_lama', 'password', 'password_confirmation'),
    });
}
</script>

<template>
    <AppLayout title="Profil">
        <form class="mx-auto max-w-2xl space-y-6" @submit.prevent="simpan">
            <section class="card grid gap-4 p-6 sm:grid-cols-2">
                <h2 class="font-bold text-stone-900 sm:col-span-2">Data Diri</h2>
                <div class="sm:col-span-2">
                    <label for="name" class="label">Nama lengkap</label>
                    <input id="name" v-model="form.name" type="text" class="input" />
                    <p v-if="form.errors.name" class="error-text">{{ form.errors.name }}</p>
                </div>
                <div v-if="profil.nik">
                    <label class="label">NIK</label>
                    <input :value="profil.nik" type="text" class="input" disabled />
                </div>
                <div>
                    <label for="telepon" class="label">No. HP</label>
                    <input id="telepon" v-model="form.telepon" type="tel" class="input" />
                </div>
                <div class="sm:col-span-2">
                    <label for="email" class="label">Email</label>
                    <input id="email" v-model="form.email" type="email" class="input" />
                    <p v-if="form.errors.email" class="error-text">{{ form.errors.email }}</p>
                </div>
                <div class="sm:col-span-2">
                    <label for="alamat" class="label">Alamat</label>
                    <textarea id="alamat" v-model="form.alamat" rows="2" class="input" />
                </div>
            </section>

            <section class="card grid gap-4 p-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <h2 class="font-bold text-stone-900">Ganti Kata Sandi</h2>
                    <p class="text-sm text-stone-500">Kosongkan bila tidak ingin mengganti.</p>
                </div>
                <div class="sm:col-span-2">
                    <label for="password_lama" class="label">Kata sandi lama</label>
                    <input id="password_lama" v-model="form.password_lama" type="password" class="input" autocomplete="current-password" />
                    <p v-if="form.errors.password_lama" class="error-text">{{ form.errors.password_lama }}</p>
                </div>
                <div>
                    <label for="password" class="label">Kata sandi baru</label>
                    <input id="password" v-model="form.password" type="password" class="input" autocomplete="new-password" />
                    <p v-if="form.errors.password" class="error-text">{{ form.errors.password }}</p>
                </div>
                <div>
                    <label for="password_confirmation" class="label">Ulangi kata sandi baru</label>
                    <input id="password_confirmation" v-model="form.password_confirmation" type="password" class="input" autocomplete="new-password" />
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary" :disabled="form.processing">Simpan Perubahan</button>
            </div>
        </form>
    </AppLayout>
</template>
