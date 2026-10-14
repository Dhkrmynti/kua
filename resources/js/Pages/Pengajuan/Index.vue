<script setup>
import { Link, router } from '@inertiajs/vue3';
import { FilePlus2, ChevronRight } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import Pagination from '@/Components/Pagination.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { formatTanggal, useEnums } from '@/lib/helpers';

const props = defineProps({
    pengajuan: Object,
    filter: Object,
});

const { enums, label } = useEnums();

function filterStatus(status) {
    router.get('/pengajuan', status ? { status } : {}, { preserveState: true, preserveScroll: true });
}
</script>

<template>
    <AppLayout title="Pengajuan Saya">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap gap-2">
                <button
                    type="button"
                    class="rounded-full px-3 py-1.5 text-sm font-medium"
                    :class="!filter.status ? 'bg-brand-600 text-white' : 'bg-white text-stone-600 ring-1 ring-stone-200 hover:bg-stone-50'"
                    @click="filterStatus(null)"
                >Semua</button>
                <button
                    v-for="s in enums.statusPengajuan"
                    :key="s.value"
                    type="button"
                    class="rounded-full px-3 py-1.5 text-sm font-medium"
                    :class="filter.status === s.value ? 'bg-brand-600 text-white' : 'bg-white text-stone-600 ring-1 ring-stone-200 hover:bg-stone-50'"
                    @click="filterStatus(s.value)"
                >{{ s.label }}</button>
            </div>
            <Link href="/pengajuan/create" class="btn-primary"><FilePlus2 class="size-4" /> Pengajuan Baru</Link>
        </div>

        <div class="card overflow-hidden">
            <ul v-if="pengajuan.data.length" class="divide-y divide-stone-100">
                <li v-for="p in pengajuan.data" :key="p.id">
                    <Link :href="`/pengajuan/${p.id}`" class="flex items-center gap-4 px-5 py-4 hover:bg-stone-50">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-bold text-stone-900">{{ label('jenis', p.jenis) }}</span>
                                <span class="font-mono text-xs text-stone-500">{{ p.kode }}</span>
                            </div>
                            <p class="mt-0.5 truncate text-sm text-stone-600">Pasangan: {{ p.nama_pasangan }}</p>
                            <p class="text-xs text-stone-400">Diajukan {{ formatTanggal(p.created_at) }}</p>
                        </div>
                        <StatusBadge :status="p.status" />
                        <ChevronRight class="size-4 text-stone-300" />
                    </Link>
                </li>
            </ul>
            <EmptyState v-else judul="Belum ada pengajuan" teks="Pengajuan yang Anda buat akan tampil di sini.">
                <Link href="/pengajuan/create" class="btn-primary">Buat Pengajuan</Link>
            </EmptyState>
            <Pagination :meta="pengajuan" />
        </div>
    </AppLayout>
</template>
