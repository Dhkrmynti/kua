<script setup>
import { reactive, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import Pagination from '@/Components/Pagination.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { formatTanggal, useEnums } from '@/lib/helpers';

const props = defineProps({
    pengaduan: Object,
    filter: Object,
});

const { enums, label } = useEnums();
const f = reactive({ q: props.filter.q ?? '', kategori: props.filter.kategori ?? '', status: props.filter.status ?? '' });

let timer;
watch(f, () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        const query = Object.fromEntries(Object.entries(f).filter(([, v]) => v));
        router.get('/admin/pengaduan', query, { preserveState: true, preserveScroll: true, replace: true });
    }, 350);
});
</script>

<template>
    <AppLayout title="Kelola Pengaduan">
        <div class="card mb-4 grid gap-3 p-4 sm:grid-cols-[1fr_200px_180px]">
            <div class="relative">
                <Search class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-stone-400" />
                <input v-model="f.q" type="search" class="input pl-9" placeholder="Cari kode atau judul…" aria-label="Cari" />
            </div>
            <select v-model="f.kategori" class="input" aria-label="Kategori">
                <option value="">Semua kategori</option>
                <option v-for="k in enums.kategoriPengaduan" :key="k.value" :value="k.value">{{ k.label }}</option>
            </select>
            <select v-model="f.status" class="input" aria-label="Status">
                <option value="">Semua status</option>
                <option v-for="s in enums.statusPengaduan" :key="s.value" :value="s.value">{{ s.label }}</option>
            </select>
        </div>

        <div class="card overflow-hidden">
            <ul v-if="pengaduan.data.length" class="divide-y divide-stone-100">
                <li v-for="p in pengaduan.data" :key="p.id">
                    <Link :href="`/admin/pengaduan/${p.id}`" class="flex items-center gap-4 px-5 py-4 hover:bg-stone-50">
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-bold text-stone-900">{{ p.judul }}</p>
                            <p class="mt-0.5 text-xs text-stone-500">
                                <span class="font-mono">{{ p.kode }}</span> · {{ p.user.name }} · {{ label('kategoriPengaduan', p.kategori) }} · {{ formatTanggal(p.created_at) }}
                            </p>
                        </div>
                        <StatusBadge :status="p.status" tipe="pengaduan" />
                    </Link>
                </li>
            </ul>
            <EmptyState v-else judul="Tidak ada pengaduan" />
            <Pagination :meta="pengaduan" />
        </div>
    </AppLayout>
</template>
