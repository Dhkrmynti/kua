<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import { UserPlus, Trash2, Users } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatTanggal } from '@/lib/helpers';

defineProps({
    staff: Array,
    jumlahMasyarakat: Number,
});

const page = usePage();

const form = useForm({
    name: '',
    email: '',
    role: 'petugas',
    password: '',
});

function simpan() {
    form.post('/admin/users', { preserveScroll: true, onSuccess: () => form.reset() });
}

function hapus(user) {
    if (confirm(`Hapus akun ${user.name}?`)) {
        router.delete(`/admin/users/${user.id}`, { preserveScroll: true });
    }
}
</script>

<template>
    <AppLayout title="Pengguna">
        <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
            <section class="card overflow-hidden">
                <div class="flex items-center justify-between border-b border-stone-100 px-5 py-4">
                    <h2 class="font-bold text-stone-900">Admin & Petugas</h2>
                    <span class="flex items-center gap-1.5 text-sm text-stone-500"><Users class="size-4" /> {{ jumlahMasyarakat }} akun masyarakat</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-base">
                        <thead>
                            <tr><th>Nama</th><th>Peran</th><th>Terdaftar</th><th class="text-right">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            <tr v-for="u in staff" :key="u.id">
                                <td>
                                    <p class="font-medium text-stone-900">{{ u.name }}</p>
                                    <p class="text-xs text-stone-500">{{ u.email }}</p>
                                </td>
                                <td>
                                    <span class="rounded-md px-2 py-0.5 text-xs font-semibold" :class="u.role === 'admin' ? 'bg-gold-400/20 text-amber-800' : 'bg-brand-50 text-brand-700'">
                                        {{ u.role === 'admin' ? 'Admin' : 'Petugas' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap">{{ formatTanggal(u.created_at) }}</td>
                                <td class="text-right">
                                    <button v-if="u.id !== page.props.auth.user.id" type="button" class="rounded-lg p-2 text-stone-400 hover:bg-rose-50 hover:text-rose-600" aria-label="Hapus" @click="hapus(u)">
                                        <Trash2 class="size-4" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <form class="card h-fit space-y-4 p-6" @submit.prevent="simpan">
                <h2 class="flex items-center gap-2 font-bold text-stone-900"><UserPlus class="size-5 text-brand-600" /> Tambah Akun</h2>
                <div>
                    <label for="name" class="label">Nama</label>
                    <input id="name" v-model="form.name" type="text" class="input" />
                    <p v-if="form.errors.name" class="error-text">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label for="email" class="label">Email</label>
                    <input id="email" v-model="form.email" type="email" class="input" />
                    <p v-if="form.errors.email" class="error-text">{{ form.errors.email }}</p>
                </div>
                <div>
                    <label for="role" class="label">Peran</label>
                    <select id="role" v-model="form.role" class="input">
                        <option value="petugas">Petugas KUA</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label for="password" class="label">Kata sandi awal</label>
                    <input id="password" v-model="form.password" type="password" class="input" autocomplete="new-password" />
                    <p v-if="form.errors.password" class="error-text">{{ form.errors.password }}</p>
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">Simpan</button>
            </form>
        </div>
    </AppLayout>
</template>
