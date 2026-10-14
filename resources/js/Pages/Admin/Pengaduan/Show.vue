<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Paperclip, Send } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { formatTanggal, useEnums } from '@/lib/helpers';

const props = defineProps({
    pengaduan: Object,
});

const { enums, label } = useEnums();

const form = useForm({
    status: props.pengaduan.status === 'baru' ? 'diproses' : props.pengaduan.status,
    tanggapan: props.pengaduan.tanggapan ?? '',
});

function simpan() {
    form.patch(`/admin/pengaduan/${props.pengaduan.id}`, { preserveScroll: true });
}
</script>

<template>
    <AppLayout title="Tanggapi Pengaduan">
        <Link href="/admin/pengaduan" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-stone-500 hover:text-stone-800">
            <ArrowLeft class="size-4" /> Kembali ke daftar
        </Link>

        <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
            <article class="card p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-stone-500 uppercase">{{ label('kategoriPengaduan', pengaduan.kategori) }}</p>
                        <h2 class="mt-1 text-xl font-bold text-stone-900">{{ pengaduan.judul }}</h2>
                        <p class="mt-1 text-sm text-stone-500"><span class="font-mono">{{ pengaduan.kode }}</span> · {{ formatTanggal(pengaduan.created_at, true) }}</p>
                    </div>
                    <StatusBadge :status="pengaduan.status" tipe="pengaduan" />
                </div>

                <div class="mt-4 rounded-lg bg-stone-50 p-3 text-sm">
                    <p class="font-semibold text-stone-800">{{ pengaduan.user.name }}</p>
                    <p class="text-stone-500">{{ pengaduan.user.email }} · {{ pengaduan.user.telepon || '-' }}</p>
                </div>

                <p class="mt-5 leading-relaxed whitespace-pre-line text-stone-700">{{ pengaduan.isi }}</p>
                <a v-if="pengaduan.lampiran_url" :href="pengaduan.lampiran_url" target="_blank" rel="noopener" class="btn-secondary mt-5 py-2">
                    <Paperclip class="size-4" /> {{ pengaduan.lampiran_nama }}
                </a>
            </article>

            <form class="card h-fit space-y-4 p-6" @submit.prevent="simpan">
                <h2 class="font-bold text-stone-900">Tanggapan</h2>
                <p v-if="pengaduan.ditanggapi_at" class="text-xs text-stone-500">
                    Terakhir ditanggapi {{ pengaduan.penanggap?.name }} · {{ formatTanggal(pengaduan.ditanggapi_at, true) }}
                </p>
                <div>
                    <label for="status" class="label">Status</label>
                    <select id="status" v-model="form.status" class="input">
                        <option v-for="s in enums.statusPengaduan" :key="s.value" :value="s.value">{{ s.label }}</option>
                    </select>
                </div>
                <div>
                    <label for="tanggapan" class="label">Isi tanggapan</label>
                    <textarea id="tanggapan" v-model="form.tanggapan" rows="7" class="input" :class="{ 'input-error': form.errors.tanggapan }" />
                    <p v-if="form.errors.tanggapan" class="error-text">{{ form.errors.tanggapan }}</p>
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing"><Send class="size-4" /> Simpan Tanggapan</button>
            </form>
        </div>
    </AppLayout>
</template>
