<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { UploadCloud, Send } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import { cekUkuran, useEnums } from '@/lib/helpers';

const { enums } = useEnums();

const form = useForm({
    kategori: 'pelayanan',
    judul: '',
    isi: '',
    lampiran: null,
});

function pilihFile(event) {
    const file = event.target.files[0];
    const error = cekUkuran(file);
    form.clearErrors('lampiran');
    if (error) {
        form.lampiran = null;
        event.target.value = '';
        return form.setError('lampiran', error);
    }
    form.lampiran = file ?? null;
}

function kirim() {
    form.post('/pengaduan', { forceFormData: true });
}
</script>

<template>
    <AppLayout title="Buat Pengaduan">
        <form class="card mx-auto max-w-2xl space-y-5 p-6" @submit.prevent="kirim">
            <div>
                <h2 class="text-lg font-bold text-stone-900">Sampaikan pengaduan Anda</h2>
                <p class="mt-1 text-sm text-stone-500">Jelaskan secara rinci: kapan, di mana, dan apa yang terjadi. Identitas Anda dijaga kerahasiaannya.</p>
            </div>

            <div>
                <label for="kategori" class="label">Kategori</label>
                <select id="kategori" v-model="form.kategori" class="input">
                    <option v-for="k in enums.kategoriPengaduan" :key="k.value" :value="k.value">{{ k.label }}</option>
                </select>
                <p v-if="form.errors.kategori" class="error-text">{{ form.errors.kategori }}</p>
            </div>

            <div>
                <label for="judul" class="label">Judul</label>
                <input id="judul" v-model="form.judul" type="text" class="input" :class="{ 'input-error': form.errors.judul }" placeholder="Ringkasan singkat pengaduan" />
                <p v-if="form.errors.judul" class="error-text">{{ form.errors.judul }}</p>
            </div>

            <div>
                <label for="isi" class="label">Isi pengaduan</label>
                <textarea id="isi" v-model="form.isi" rows="6" class="input" :class="{ 'input-error': form.errors.isi }" />
                <div class="mt-1 flex justify-between">
                    <p v-if="form.errors.isi" class="error-text mt-0">{{ form.errors.isi }}</p>
                    <span class="ml-auto text-xs text-stone-400">{{ form.isi.length }}/5000</span>
                </div>
            </div>

            <div>
                <span class="label">Lampiran <span class="font-normal text-stone-400">(opsional, PDF/JPG/PNG maks. 2 MB)</span></span>
                <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-dashed border-stone-300 p-4 hover:border-brand-400">
                    <UploadCloud class="size-6 text-stone-400" />
                    <span class="truncate text-sm text-stone-600">{{ form.lampiran ? form.lampiran.name : 'Klik untuk memilih berkas bukti' }}</span>
                    <input type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" @change="pilihFile" />
                </label>
                <p v-if="form.errors.lampiran" class="error-text">{{ form.errors.lampiran }}</p>
            </div>

            <div class="flex justify-end gap-3 border-t border-stone-100 pt-5">
                <Link href="/pengaduan" class="btn-secondary">Batal</Link>
                <button type="submit" class="btn-primary" :disabled="form.processing"><Send class="size-4" /> Kirim Pengaduan</button>
            </div>
        </form>
    </AppLayout>
</template>
