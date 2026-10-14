<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { FilePlus2, FileText, Loader, CheckCircle2, MessageSquareWarning, CalendarCheck, ArrowRight } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { formatTanggal, useEnums } from '@/lib/helpers';

defineProps({
    ringkasan: Object,
    pengajuan: Array,
    pengaduan: Array,
    jadwal: Object,
});

const page = usePage();
const { label } = useEnums();
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="mb-6 flex flex-col justify-between gap-4 rounded-2xl bg-brand-800 pattern-islami p-6 text-white sm:flex-row sm:items-center">
            <div>
                <p class="text-sm text-brand-100">Assalamu'alaikum,</p>
                <p class="text-2xl font-extrabold">{{ page.props.auth.user.name }}</p>
            </div>
            <Link href="/pengajuan/create" class="btn bg-gold-500 text-brand-900 hover:bg-gold-400">
                <FilePlus2 class="size-4" /> Buat Pengajuan Baru
            </Link>
        </div>

        <div v-if="jadwal" class="mb-6 flex items-start gap-4 rounded-2xl border border-violet-200 bg-violet-50 p-5">
            <CalendarCheck class="mt-0.5 size-6 shrink-0 text-violet-600" />
            <div class="flex-1">
                <p class="font-bold text-violet-900">Jadwal {{ label('jenis', jadwal.jenis) }}: {{ formatTanggal(jadwal.jadwal, true) }} WIB</p>
                <p class="mt-0.5 text-sm text-violet-800">{{ jadwal.lokasi_jadwal || 'Kantor KUA' }} · {{ jadwal.kode }}</p>
            </div>
            <Link :href="`/pengajuan/${jadwal.id}`" class="text-sm font-semibold text-violet-700 hover:underline">Detail</Link>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div v-for="s in [
                { t: 'Total Pengajuan', v: ringkasan.total, i: FileText, c: 'text-stone-600 bg-stone-100' },
                { t: 'Sedang Diproses', v: ringkasan.proses, i: Loader, c: 'text-amber-700 bg-amber-100' },
                { t: 'Selesai', v: ringkasan.selesai, i: CheckCircle2, c: 'text-emerald-700 bg-emerald-100' },
                { t: 'Pengaduan', v: ringkasan.pengaduan, i: MessageSquareWarning, c: 'text-sky-700 bg-sky-100' },
            ]" :key="s.t" class="card p-5">
                <span class="grid size-10 place-items-center rounded-lg" :class="s.c"><component :is="s.i" class="size-5" /></span>
                <p class="mt-3 text-2xl font-extrabold text-stone-900">{{ s.v }}</p>
                <p class="text-sm text-stone-500">{{ s.t }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <section class="card">
                <div class="flex items-center justify-between border-b border-stone-100 px-5 py-4">
                    <h2 class="font-bold text-stone-900">Pengajuan Terakhir</h2>
                    <Link href="/pengajuan" class="flex items-center gap-1 text-sm font-semibold text-brand-700 hover:underline">Semua <ArrowRight class="size-3.5" /></Link>
                </div>
                <ul v-if="pengajuan.length" class="divide-y divide-stone-100">
                    <li v-for="p in pengajuan" :key="p.id">
                        <Link :href="`/pengajuan/${p.id}`" class="flex items-center justify-between gap-3 px-5 py-3.5 hover:bg-stone-50">
                            <div class="min-w-0">
                                <p class="font-semibold text-stone-800">{{ label('jenis', p.jenis) }} · <span class="font-mono text-sm">{{ p.kode }}</span></p>
                                <p class="truncate text-xs text-stone-500">dengan {{ p.nama_pasangan }} · {{ formatTanggal(p.created_at) }}</p>
                            </div>
                            <StatusBadge :status="p.status" />
                        </Link>
                    </li>
                </ul>
                <EmptyState v-else judul="Belum ada pengajuan" teks="Ajukan layanan nikah, talak, cerai, atau rujuk secara online.">
                    <Link href="/pengajuan/create" class="btn-primary">Buat Pengajuan</Link>
                </EmptyState>
            </section>

            <section class="card">
                <div class="flex items-center justify-between border-b border-stone-100 px-5 py-4">
                    <h2 class="font-bold text-stone-900">Pengaduan Terakhir</h2>
                    <Link href="/pengaduan" class="flex items-center gap-1 text-sm font-semibold text-brand-700 hover:underline">Semua <ArrowRight class="size-3.5" /></Link>
                </div>
                <ul v-if="pengaduan.length" class="divide-y divide-stone-100">
                    <li v-for="p in pengaduan" :key="p.id">
                        <Link :href="`/pengaduan/${p.id}`" class="flex items-center justify-between gap-3 px-5 py-3.5 hover:bg-stone-50">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-stone-800">{{ p.judul }}</p>
                                <p class="text-xs text-stone-500">{{ p.kode }} · {{ formatTanggal(p.created_at) }}</p>
                            </div>
                            <StatusBadge :status="p.status" tipe="pengaduan" />
                        </Link>
                    </li>
                </ul>
                <EmptyState v-else judul="Belum ada pengaduan" teks="Sampaikan keluhan atau saran terkait pelayanan KUA.">
                    <Link href="/pengaduan/create" class="btn-secondary">Buat Pengaduan</Link>
                </EmptyState>
            </section>
        </div>
    </AppLayout>
</template>
