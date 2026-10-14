<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import Timeline from '@/Components/Timeline.vue';
import DetailPengajuan from '@/Components/DetailPengajuan.vue';
import { formatTanggal, toDatetimeLocal, useEnums } from '@/lib/helpers';

const props = defineProps({
    pengajuan: Object,
});

const { enums, label } = useEnums();

const form = useForm({
    status: props.pengajuan.status,
    catatan: '',
    jadwal: toDatetimeLocal(props.pengajuan.jadwal) || (props.pengajuan.tanggal_rencana ? `${props.pengajuan.tanggal_rencana}T09:00` : ''),
    lokasi_jadwal: props.pengajuan.lokasi_jadwal ?? (props.pengajuan.lokasi_akad === 'luar_kua' ? '' : 'Balai Nikah KUA'),
});

const catatanWajib = computed(() => ['perlu_perbaikan', 'ditolak'].includes(form.status));

const templateCatatan = {
    diverifikasi: 'Berkas telah diperiksa dan dinyatakan lengkap.',
    perlu_perbaikan: 'Mohon unggah ulang dokumen berikut karena belum terbaca jelas / belum sesuai: ',
    dijadwalkan: 'Harap hadir 30 menit sebelum jadwal dengan membawa dokumen asli.',
    selesai: 'Layanan telah selesai dilaksanakan.',
    ditolak: 'Pengajuan tidak dapat diproses karena ',
};

function simpan() {
    form.patch(`/admin/pengajuan/${props.pengajuan.id}/status`, {
        preserveScroll: true,
        onSuccess: () => form.reset('catatan'),
    });
}
</script>

<template>
    <AppLayout :title="`Proses Pengajuan ${label('jenis', pengajuan.jenis)}`">
        <Link href="/admin/pengajuan" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-stone-500 hover:text-stone-800">
            <ArrowLeft class="size-4" /> Kembali ke daftar
        </Link>

        <div class="card mb-6 flex flex-wrap items-center justify-between gap-4 p-6">
            <div>
                <p class="text-sm text-stone-500">{{ label('jenis', pengajuan.jenis) }}</p>
                <p class="font-mono text-2xl font-bold text-stone-900">{{ pengajuan.kode }}</p>
                <p class="mt-1 text-sm text-stone-500">
                    Diajukan {{ formatTanggal(pengajuan.created_at, true) }}
                    <template v-if="pengajuan.petugas"> · ditangani {{ pengajuan.petugas.name }}</template>
                </p>
            </div>
            <StatusBadge :status="pengajuan.status" />
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
            <DetailPengajuan :pengajuan="pengajuan" :pemohon="pengajuan.user" />

            <div class="space-y-6">
                <form class="card space-y-4 p-6" @submit.prevent="simpan">
                    <h2 class="font-bold text-stone-900">Perbarui Status</h2>

                    <div>
                        <label for="status" class="label">Status baru</label>
                        <select id="status" v-model="form.status" class="input">
                            <option v-for="s in enums.statusPengajuan" :key="s.value" :value="s.value">{{ s.label }}</option>
                        </select>
                    </div>

                    <template v-if="form.status === 'dijadwalkan'">
                        <div>
                            <label for="jadwal" class="label">Tanggal & jam</label>
                            <input id="jadwal" v-model="form.jadwal" type="datetime-local" class="input" :class="{ 'input-error': form.errors.jadwal }" />
                            <p v-if="form.errors.jadwal" class="error-text">{{ form.errors.jadwal }}</p>
                        </div>
                        <div>
                            <label for="lokasi_jadwal" class="label">Lokasi</label>
                            <input id="lokasi_jadwal" v-model="form.lokasi_jadwal" type="text" class="input" placeholder="Balai Nikah KUA / alamat rumah" />
                        </div>
                    </template>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="catatan" class="label">
                                Catatan untuk pemohon <span v-if="!catatanWajib" class="font-normal text-stone-400">(opsional)</span>
                            </label>
                            <button v-if="templateCatatan[form.status]" type="button" class="mb-1.5 text-xs font-semibold text-brand-700 hover:underline" @click="form.catatan = templateCatatan[form.status]">
                                Pakai template
                            </button>
                        </div>
                        <textarea id="catatan" v-model="form.catatan" rows="4" class="input" :class="{ 'input-error': form.errors.catatan }" />
                        <p v-if="form.errors.catatan" class="error-text">{{ form.errors.catatan }}</p>
                    </div>

                    <button type="submit" class="btn-primary w-full" :disabled="form.processing"><Save class="size-4" /> Simpan</button>
                </form>

                <aside class="card p-6">
                    <h2 class="mb-5 font-bold text-stone-900">Riwayat</h2>
                    <Timeline :items="pengajuan.riwayat" />
                </aside>
            </div>
        </div>
    </AppLayout>
</template>
