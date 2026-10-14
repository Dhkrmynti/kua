<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, CalendarCheck, AlertTriangle, UploadCloud, Copy } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import Timeline from '@/Components/Timeline.vue';
import DetailPengajuan from '@/Components/DetailPengajuan.vue';
import { cekUkuran, formatTanggal, useEnums } from '@/lib/helpers';

const props = defineProps({
    pengajuan: Object,
    bisaUnggah: Boolean,
});

const { label } = useEnums();

const form = useForm({ nama: '', file: null });

function unggah() {
    form.post(`/pengajuan/${props.pengajuan.id}/dokumen`, {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

function pilihFile(event) {
    const file = event.target.files[0];
    const error = cekUkuran(file);
    form.clearErrors('file');
    if (error) {
        form.file = null;
        event.target.value = '';
        return form.setError('file', error);
    }
    form.file = file ?? null;
}

function salinKode() {
    navigator.clipboard?.writeText(props.pengajuan.kode);
}
</script>

<template>
    <AppLayout :title="`Pengajuan ${label('jenis', pengajuan.jenis)}`">
        <Link href="/pengajuan" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-stone-500 hover:text-stone-800">
            <ArrowLeft class="size-4" /> Kembali
        </Link>

        <div class="card mb-6 flex flex-wrap items-center justify-between gap-4 p-6">
            <div>
                <p class="text-sm text-stone-500">Kode pengajuan</p>
                <button type="button" class="group flex items-center gap-2 font-mono text-2xl font-bold text-stone-900" title="Salin kode" @click="salinKode">
                    {{ pengajuan.kode }} <Copy class="size-4 text-stone-300 group-hover:text-stone-500" />
                </button>
                <p class="mt-1 text-sm text-stone-500">Diajukan {{ formatTanggal(pengajuan.created_at, true) }}</p>
            </div>
            <StatusBadge :status="pengajuan.status" class="text-sm" />
        </div>

        <div v-if="pengajuan.status === 'dijadwalkan' && pengajuan.jadwal" class="mb-6 flex gap-4 rounded-2xl border border-violet-200 bg-violet-50 p-5">
            <CalendarCheck class="size-6 shrink-0 text-violet-600" />
            <div>
                <p class="font-bold text-violet-900">{{ formatTanggal(pengajuan.jadwal, true) }} WIB</p>
                <p class="text-sm text-violet-800">Lokasi: {{ pengajuan.lokasi_jadwal || 'Kantor KUA' }}</p>
                <p v-if="pengajuan.catatan_petugas" class="mt-2 text-sm text-violet-800">{{ pengajuan.catatan_petugas }}</p>
            </div>
        </div>

        <div v-else-if="['perlu_perbaikan', 'ditolak'].includes(pengajuan.status) && pengajuan.catatan_petugas" class="mb-6 flex gap-4 rounded-2xl border p-5"
            :class="pengajuan.status === 'ditolak' ? 'border-rose-200 bg-rose-50' : 'border-amber-200 bg-amber-50'">
            <AlertTriangle class="size-6 shrink-0" :class="pengajuan.status === 'ditolak' ? 'text-rose-600' : 'text-amber-600'" />
            <div>
                <p class="font-bold" :class="pengajuan.status === 'ditolak' ? 'text-rose-900' : 'text-amber-900'">Catatan dari petugas</p>
                <p class="mt-1 text-sm whitespace-pre-line" :class="pengajuan.status === 'ditolak' ? 'text-rose-800' : 'text-amber-800'">{{ pengajuan.catatan_petugas }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
            <DetailPengajuan :pengajuan="pengajuan">
                <template #dokumen-footer>
                    <form v-if="bisaUnggah" class="grid gap-3 border-t border-stone-100 bg-stone-50/60 px-6 py-4 sm:grid-cols-[1fr_auto_auto] sm:items-start" @submit.prevent="unggah">
                        <div>
                            <input v-model="form.nama" type="text" class="input" placeholder="Nama dokumen, mis. KTP calon istri (perbaikan)" aria-label="Nama dokumen" />
                            <p v-if="form.errors.nama" class="error-text">{{ form.errors.nama }}</p>
                        </div>
                        <div>
                            <label class="btn-secondary w-full cursor-pointer">
                                <UploadCloud class="size-4" />
                                <span class="max-w-40 truncate">{{ form.file ? form.file.name : 'Pilih berkas' }}</span>
                                <input type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" @change="pilihFile" />
                            </label>
                            <p v-if="form.errors.file" class="error-text">{{ form.errors.file }}</p>
                        </div>
                        <button class="btn-primary" :disabled="form.processing || !form.file">Unggah</button>
                    </form>
                </template>
            </DetailPengajuan>

            <aside class="card h-fit p-6">
                <h2 class="mb-5 font-bold text-stone-900">Riwayat Status</h2>
                <Timeline :items="pengajuan.riwayat" />
            </aside>
        </div>
    </AppLayout>
</template>
