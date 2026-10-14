<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { Search, SearchX, CalendarCheck } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import Timeline from '@/Components/Timeline.vue';
import { formatTanggal, useEnums } from '@/lib/helpers';

const props = defineProps({
    kode: String,
    hasil: Object,
});

const { label } = useEnums();
const input = ref(props.kode ?? '');

function cari() {
    router.get('/lacak', { kode: input.value.trim() }, { preserveState: true });
}
</script>

<template>
    <Head title="Lacak Berkas" />

    <PublicLayout>
        <section class="mx-auto max-w-2xl px-4 py-14">
            <h1 class="text-3xl font-extrabold tracking-tight text-stone-900">Lacak Berkas</h1>
            <p class="mt-2 text-stone-600">Cek perkembangan pengajuan atau pengaduan menggunakan kode yang Anda terima.</p>

            <form class="mt-6 flex gap-2" @submit.prevent="cari">
                <input v-model="input" type="text" class="input uppercase" placeholder="NKH-261010-AB12 / ADU-261010-XY34" aria-label="Kode berkas" autofocus />
                <button class="btn-primary shrink-0"><Search class="size-4" /> Cari</button>
            </form>

            <div v-if="kode && !hasil" class="card mt-8 flex flex-col items-center p-10 text-center">
                <SearchX class="size-10 text-stone-300" />
                <p class="mt-3 font-semibold text-stone-700">Kode "{{ kode }}" tidak ditemukan</p>
                <p class="mt-1 text-sm text-stone-500">Periksa kembali penulisan kode Anda.</p>
            </div>

            <div v-if="hasil" class="card mt-8 overflow-hidden">
                <div class="flex flex-wrap items-start justify-between gap-3 border-b border-stone-100 p-6">
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-stone-500 uppercase">
                            {{ hasil.tipe === 'pengajuan' ? `Pengajuan ${label('jenis', hasil.jenis)}` : `Pengaduan · ${hasil.kategori}` }}
                        </p>
                        <p class="mt-1 font-mono text-xl font-bold text-stone-900">{{ hasil.kode }}</p>
                        <p class="mt-1 text-sm text-stone-500">Atas nama {{ hasil.nama }} · {{ formatTanggal(hasil.dibuat) }}</p>
                    </div>
                    <StatusBadge :status="hasil.status" :tipe="hasil.tipe" />
                </div>

                <div v-if="hasil.tipe === 'pengajuan'" class="p-6">
                    <div v-if="hasil.status === 'dijadwalkan' && hasil.jadwal" class="mb-6 flex items-center gap-3 rounded-xl bg-violet-50 p-4 text-violet-900">
                        <CalendarCheck class="size-5 shrink-0" />
                        <p class="text-sm">Jadwal: <strong>{{ formatTanggal(hasil.jadwal, true) }} WIB</strong></p>
                    </div>
                    <h2 class="mb-4 text-sm font-bold text-stone-700">Riwayat</h2>
                    <Timeline :items="hasil.riwayat" />
                </div>

                <div v-else class="space-y-2 p-6 text-sm">
                    <p><span class="text-stone-500">Judul:</span> {{ hasil.judul }}</p>
                    <p v-if="hasil.ditanggapi_at"><span class="text-stone-500">Ditanggapi:</span> {{ formatTanggal(hasil.ditanggapi_at, true) }}</p>
                    <p class="text-stone-500">Masuk ke akun Anda untuk membaca isi tanggapan.</p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
