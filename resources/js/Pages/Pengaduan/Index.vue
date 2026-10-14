<script setup>
import { Link } from '@inertiajs/vue3';
import { Plus, ChevronRight } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import Pagination from '@/Components/Pagination.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { formatTanggal, useEnums } from '@/lib/helpers';

defineProps({
    pengaduan: Object,
});

const { label } = useEnums();
</script>

<template>
    <AppLayout title="Pengaduan Saya">
        <div class="mb-4 flex justify-end">
            <Link href="/pengaduan/create" class="btn-primary"><Plus class="size-4" /> Pengaduan Baru</Link>
        </div>

        <div class="card overflow-hidden">
            <ul v-if="pengaduan.data.length" class="divide-y divide-stone-100">
                <li v-for="p in pengaduan.data" :key="p.id">
                    <Link :href="`/pengaduan/${p.id}`" class="flex items-center gap-4 px-5 py-4 hover:bg-stone-50">
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-bold text-stone-900">{{ p.judul }}</p>
                            <p class="mt-0.5 text-xs text-stone-500">
                                <span class="font-mono">{{ p.kode }}</span> · {{ label('kategoriPengaduan', p.kategori) }} · {{ formatTanggal(p.created_at) }}
                            </p>
                        </div>
                        <StatusBadge :status="p.status" tipe="pengaduan" />
                        <ChevronRight class="size-4 text-stone-300" />
                    </Link>
                </li>
            </ul>
            <EmptyState v-else judul="Belum ada pengaduan" teks="Keluhan dan saran Anda membantu kami memperbaiki layanan.">
                <Link href="/pengaduan/create" class="btn-primary">Buat Pengaduan</Link>
            </EmptyState>
            <Pagination :meta="pengaduan" />
        </div>
    </AppLayout>
</template>
