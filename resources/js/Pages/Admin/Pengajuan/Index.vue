<script setup>
import { reactive, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import Pagination from '@/Components/Pagination.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { formatTanggal, useEnums, warnaJenis } from '@/lib/helpers';

const props = defineProps({
    pengajuan: Object,
    filter: Object,
});

const { enums, label } = useEnums();
const f = reactive({ q: props.filter.q ?? '', jenis: props.filter.jenis ?? '', status: props.filter.status ?? '' });

let timer;
watch(f, () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        const query = Object.fromEntries(Object.entries(f).filter(([, v]) => v));
        router.get('/admin/pengajuan', query, { preserveState: true, preserveScroll: true, replace: true });
    }, 350);
});
</script>

<template>
    <AppLayout title="Kelola Pengajuan">
        <div class="card mb-4 grid gap-3 p-4 sm:grid-cols-[1fr_180px_200px]">
            <div class="relative">
                <Search class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-stone-400" />
                <input v-model="f.q" type="search" class="input pl-9" placeholder="Cari kode, nama, atau NIK…" aria-label="Cari" />
            </div>
            <select v-model="f.jenis" class="input" aria-label="Jenis layanan">
                <option value="">Semua layanan</option>
                <option v-for="j in enums.jenis" :key="j.value" :value="j.value">{{ j.label }}</option>
            </select>
            <select v-model="f.status" class="input" aria-label="Status">
                <option value="">Semua status</option>
                <option v-for="s in enums.statusPengajuan" :key="s.value" :value="s.value">{{ s.label }}</option>
            </select>
        </div>

        <div class="card overflow-hidden">
            <div v-if="pengajuan.data.length" class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Layanan</th>
                            <th>Pemohon & Pasangan</th>
                            <th>Rencana</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <tr v-for="p in pengajuan.data" :key="p.id" class="hover:bg-stone-50">
                            <td>
                                <p class="font-mono text-xs font-semibold text-stone-900">{{ p.kode }}</p>
                                <p class="text-xs text-stone-400">{{ formatTanggal(p.created_at) }}</p>
                            </td>
                            <td>
                                <span class="rounded-md px-2 py-0.5 text-xs font-semibold" :class="[warnaJenis[p.jenis].bg, warnaJenis[p.jenis].text]">
                                    {{ label('jenis', p.jenis) }}
                                </span>
                            </td>
                            <td>
                                <p class="font-medium text-stone-900">{{ p.user.name }}</p>
                                <p class="text-xs text-stone-500">& {{ p.nama_pasangan }}</p>
                            </td>
                            <td class="whitespace-nowrap">{{ formatTanggal(p.tanggal_rencana) }}</td>
                            <td><StatusBadge :status="p.status" /></td>
                            <td class="text-right">
                                <Link :href="`/admin/pengajuan/${p.id}`" class="btn-secondary px-3 py-1.5 text-xs">Proses</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <EmptyState v-else judul="Tidak ada pengajuan" teks="Coba ubah kata kunci atau filter." />
            <Pagination :meta="pengajuan" />
        </div>
    </AppLayout>
</template>
