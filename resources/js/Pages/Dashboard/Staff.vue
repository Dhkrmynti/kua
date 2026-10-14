<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { FileText, Hourglass, CalendarCheck, CheckCircle2, MessageSquareWarning, ArrowRight } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { formatTanggal, useEnums, warnaJenis } from '@/lib/helpers';

const props = defineProps({
    ringkasan: Object,
    perStatus: Array,
    perJenis: Array,
    terbaru: Array,
    jadwal: Array,
});

const { label } = useEnums();
const maksJenis = computed(() => Math.max(1, ...props.perJenis.map((j) => j.total)));
const totalStatus = computed(() => Math.max(1, props.perStatus.reduce((a, s) => a + s.total, 0)));
</script>

<template>
    <AppLayout title="Dashboard Petugas">
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
            <div v-for="s in [
                { t: 'Total Pengajuan', v: ringkasan.total, i: FileText, c: 'text-stone-600 bg-stone-100', href: '/admin/pengajuan' },
                { t: 'Menunggu Proses', v: ringkasan.menunggu, i: Hourglass, c: 'text-amber-700 bg-amber-100', href: '/admin/pengajuan?status=diajukan' },
                { t: 'Dijadwalkan', v: ringkasan.dijadwalkan, i: CalendarCheck, c: 'text-violet-700 bg-violet-100', href: '/admin/pengajuan?status=dijadwalkan' },
                { t: 'Selesai', v: ringkasan.selesai, i: CheckCircle2, c: 'text-emerald-700 bg-emerald-100', href: '/admin/pengajuan?status=selesai' },
                { t: 'Pengaduan Baru', v: ringkasan.pengaduanBaru, i: MessageSquareWarning, c: 'text-sky-700 bg-sky-100', href: '/admin/pengaduan?status=baru' },
            ]" :key="s.t">
                <Link :href="s.href" class="card block h-full p-5 transition hover:border-brand-200 hover:shadow-md">
                    <span class="grid size-10 place-items-center rounded-lg" :class="s.c"><component :is="s.i" class="size-5" /></span>
                    <p class="mt-3 text-2xl font-extrabold text-stone-900">{{ s.v }}</p>
                    <p class="text-sm text-stone-500">{{ s.t }}</p>
                </Link>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <section class="card p-5">
                <h2 class="font-bold text-stone-900">Pengajuan per Layanan</h2>
                <ul class="mt-5 space-y-4">
                    <li v-for="j in perJenis" :key="j.value">
                        <div class="mb-1.5 flex justify-between text-sm">
                            <span class="font-medium text-stone-700">{{ label('jenis', j.value) }}</span>
                            <span class="font-bold text-stone-900">{{ j.total }}</span>
                        </div>
                        <div class="h-2.5 overflow-hidden rounded-full bg-stone-100">
                            <div class="h-full rounded-full transition-all" :class="warnaJenis[j.value].bar" :style="{ width: `${(j.total / maksJenis) * 100}%` }" />
                        </div>
                    </li>
                </ul>
            </section>

            <section class="card p-5">
                <h2 class="font-bold text-stone-900">Komposisi Status</h2>
                <div class="mt-5 flex h-3 overflow-hidden rounded-full bg-stone-100">
                    <div
                        v-for="s in perStatus"
                        :key="s.value"
                        :title="`${label('statusPengajuan', s.value)}: ${s.total}`"
                        :style="{ width: `${(s.total / totalStatus) * 100}%` }"
                        :class="{
                            'bg-sky-500': s.value === 'diajukan', 'bg-indigo-500': s.value === 'diverifikasi',
                            'bg-amber-500': s.value === 'perlu_perbaikan', 'bg-violet-500': s.value === 'dijadwalkan',
                            'bg-emerald-500': s.value === 'selesai', 'bg-rose-500': s.value === 'ditolak',
                        }"
                    />
                </div>
                <ul class="mt-5 grid grid-cols-2 gap-3">
                    <li v-for="s in perStatus" :key="s.value" class="flex items-center justify-between gap-2 rounded-lg bg-stone-50 px-3 py-2">
                        <StatusBadge :status="s.value" />
                        <span class="text-sm font-bold text-stone-800">{{ s.total }}</span>
                    </li>
                </ul>
            </section>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <section class="card">
                <div class="flex items-center justify-between border-b border-stone-100 px-5 py-4">
                    <h2 class="font-bold text-stone-900">Perlu Ditindaklanjuti</h2>
                    <Link href="/admin/pengajuan" class="flex items-center gap-1 text-sm font-semibold text-brand-700 hover:underline">Semua <ArrowRight class="size-3.5" /></Link>
                </div>
                <ul v-if="terbaru.length" class="divide-y divide-stone-100">
                    <li v-for="p in terbaru" :key="p.id">
                        <Link :href="`/admin/pengajuan/${p.id}`" class="flex items-center justify-between gap-3 px-5 py-3.5 hover:bg-stone-50">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-stone-800">{{ p.user.name }} & {{ p.nama_pasangan }}</p>
                                <p class="text-xs text-stone-500">{{ label('jenis', p.jenis) }} · {{ p.kode }} · {{ formatTanggal(p.created_at) }}</p>
                            </div>
                            <StatusBadge :status="p.status" />
                        </Link>
                    </li>
                </ul>
                <EmptyState v-else judul="Semua beres" teks="Tidak ada pengajuan yang menunggu." />
            </section>

            <section class="card">
                <div class="border-b border-stone-100 px-5 py-4">
                    <h2 class="font-bold text-stone-900">Jadwal Mendatang</h2>
                </div>
                <ul v-if="jadwal.length" class="divide-y divide-stone-100">
                    <li v-for="p in jadwal" :key="p.id">
                        <Link :href="`/admin/pengajuan/${p.id}`" class="flex items-center gap-4 px-5 py-3.5 hover:bg-stone-50">
                            <div class="w-14 shrink-0 rounded-lg bg-violet-50 py-1.5 text-center text-violet-800">
                                <p class="text-lg leading-none font-extrabold">{{ new Date(p.jadwal).toLocaleDateString('id-ID', { day: '2-digit', timeZone: 'Asia/Jakarta' }) }}</p>
                                <p class="text-[11px] font-semibold uppercase">{{ new Date(p.jadwal).toLocaleDateString('id-ID', { month: 'short', timeZone: 'Asia/Jakarta' }) }}</p>
                            </div>
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-stone-800">{{ label('jenis', p.jenis) }}: {{ p.user.name }} & {{ p.nama_pasangan }}</p>
                                <p class="text-xs text-stone-500">{{ formatTanggal(p.jadwal, true) }} · {{ p.lokasi_jadwal || 'KUA' }}</p>
                            </div>
                        </Link>
                    </li>
                </ul>
                <EmptyState v-else judul="Belum ada jadwal" />
            </section>
        </div>
    </AppLayout>
</template>
