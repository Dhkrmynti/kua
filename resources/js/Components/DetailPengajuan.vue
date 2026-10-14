<script setup>
import { computed } from 'vue';
import { FileText, ExternalLink } from 'lucide-vue-next';
import { formatTanggal, formatUkuran, useEnums } from '@/lib/helpers';

const props = defineProps({
    pengajuan: { type: Object, required: true },
    pemohon: { type: Object, default: null },
});

const { find } = useEnums();
const layanan = computed(() => find('jenis', props.pengajuan.jenis));
</script>

<template>
    <div class="space-y-6">
        <section class="card p-6">
            <h2 class="font-bold text-stone-900">Data Pengajuan</h2>
            <dl class="mt-4 grid gap-x-6 gap-y-4 text-sm sm:grid-cols-2">
                <template v-if="pemohon">
                    <div><dt class="text-stone-500">Pemohon</dt><dd class="font-semibold text-stone-900">{{ pemohon.name }}</dd></div>
                    <div><dt class="text-stone-500">NIK pemohon</dt><dd class="font-mono text-stone-900">{{ pemohon.nik || '-' }}</dd></div>
                    <div><dt class="text-stone-500">Kontak pemohon</dt><dd class="text-stone-900">{{ pemohon.telepon || '-' }} · {{ pemohon.email }}</dd></div>
                    <div><dt class="text-stone-500">Alamat pemohon</dt><dd class="text-stone-900">{{ pemohon.alamat || '-' }}</dd></div>
                </template>
                <div><dt class="text-stone-500">{{ layanan?.labelPasangan }}</dt><dd class="font-semibold text-stone-900">{{ pengajuan.nama_pasangan }}</dd></div>
                <div><dt class="text-stone-500">NIK pasangan</dt><dd class="font-mono text-stone-900">{{ pengajuan.nik_pasangan }}</dd></div>
                <div><dt class="text-stone-500">Telepon pasangan</dt><dd class="text-stone-900">{{ pengajuan.telepon_pasangan || '-' }}</dd></div>
                <div><dt class="text-stone-500">{{ layanan?.labelTanggal }}</dt><dd class="text-stone-900">{{ formatTanggal(pengajuan.tanggal_rencana) }}</dd></div>
                <div v-if="pengajuan.lokasi_akad"><dt class="text-stone-500">Lokasi akad</dt><dd class="text-stone-900">{{ pengajuan.lokasi_akad === 'kua' ? 'Balai Nikah KUA' : 'Di luar KUA' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-stone-500">Alamat pasangan</dt><dd class="text-stone-900">{{ pengajuan.alamat_pasangan }}</dd></div>
                <div v-if="pengajuan.keterangan" class="sm:col-span-2"><dt class="text-stone-500">Keterangan</dt><dd class="whitespace-pre-line text-stone-900">{{ pengajuan.keterangan }}</dd></div>
            </dl>
        </section>

        <section class="card">
            <div class="border-b border-stone-100 px-6 py-4">
                <h2 class="font-bold text-stone-900">Dokumen ({{ pengajuan.dokumen.length }})</h2>
            </div>
            <ul v-if="pengajuan.dokumen.length" class="divide-y divide-stone-100">
                <li v-for="d in pengajuan.dokumen" :key="d.id" class="flex items-center gap-3 px-6 py-3">
                    <FileText class="size-5 shrink-0 text-stone-400" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-stone-800">{{ d.nama }}</p>
                        <p class="truncate text-xs text-stone-500">{{ d.original_name }} · {{ formatUkuran(d.size) }} · {{ formatTanggal(d.created_at) }}</p>
                    </div>
                    <a :href="d.url" target="_blank" rel="noopener" class="btn-secondary px-3 py-1.5 text-xs">
                        Lihat <ExternalLink class="size-3.5" />
                    </a>
                </li>
            </ul>
            <p v-else class="px-6 py-6 text-sm text-stone-500">Belum ada dokumen.</p>
            <slot name="dokumen-footer" />
        </section>
    </div>
</template>
