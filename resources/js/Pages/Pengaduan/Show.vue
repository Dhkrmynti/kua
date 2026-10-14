<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, Paperclip, MessageCircleReply, Clock } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { formatTanggal, useEnums } from '@/lib/helpers';

defineProps({
    pengaduan: Object,
});

const { label } = useEnums();
</script>

<template>
    <AppLayout title="Detail Pengaduan">
        <Link href="/pengaduan" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-stone-500 hover:text-stone-800">
            <ArrowLeft class="size-4" /> Kembali
        </Link>

        <div class="mx-auto max-w-3xl space-y-6">
            <article class="card p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-stone-500 uppercase">{{ label('kategoriPengaduan', pengaduan.kategori) }}</p>
                        <h2 class="mt-1 text-xl font-bold text-stone-900">{{ pengaduan.judul }}</h2>
                        <p class="mt-1 text-sm text-stone-500"><span class="font-mono">{{ pengaduan.kode }}</span> · {{ formatTanggal(pengaduan.created_at, true) }}</p>
                    </div>
                    <StatusBadge :status="pengaduan.status" tipe="pengaduan" />
                </div>
                <p class="mt-5 leading-relaxed whitespace-pre-line text-stone-700">{{ pengaduan.isi }}</p>
                <a v-if="pengaduan.lampiran_url" :href="pengaduan.lampiran_url" target="_blank" rel="noopener" class="btn-secondary mt-5 py-2">
                    <Paperclip class="size-4" /> {{ pengaduan.lampiran_nama }}
                </a>
            </article>

            <section v-if="pengaduan.tanggapan" class="card border-brand-200 bg-brand-50/50 p-6">
                <div class="flex items-center gap-2 text-brand-800">
                    <MessageCircleReply class="size-5" />
                    <h3 class="font-bold">Tanggapan KUA</h3>
                </div>
                <p class="mt-3 leading-relaxed whitespace-pre-line text-stone-700">{{ pengaduan.tanggapan }}</p>
                <p class="mt-4 text-xs text-stone-500">{{ pengaduan.penanggap?.name }} · {{ formatTanggal(pengaduan.ditanggapi_at, true) }}</p>
            </section>
            <div v-else class="card flex items-center gap-3 p-6 text-sm text-stone-500">
                <Clock class="size-5 text-stone-400" /> Pengaduan Anda sedang menunggu tanggapan petugas.
            </div>
        </div>
    </AppLayout>
</template>
