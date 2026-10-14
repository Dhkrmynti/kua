<script setup>
import KuaLayout from '@/Layouts/KuaLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    submissions: {
        type: Array,
        required: true,
    },
});

const getStatusColor = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'verified': return 'bg-blue-100 text-blue-800';
        case 'scheduled': return 'bg-purple-100 text-purple-800';
        case 'completed': return 'bg-green-100 text-green-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 'pending': return 'Menunggu Verifikasi';
        case 'verified': return 'Terverifikasi';
        case 'scheduled': return 'Dijadwalkan';
        case 'completed': return 'Selesai';
        case 'rejected': return 'Ditolak';
        default: return status;
    }
};
</script>

<template>
    <Head title="Pengajuan Saya" />

    <KuaLayout>
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Layanan</h2>
                <p class="text-slate-500 mt-1 font-medium">Pantau status pengajuan administrasi Anda di sini.</p>
            </div>
            <Link :href="route('submissions.create')" class="inline-flex items-center justify-center bg-emerald-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-500/30 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Buat Pengajuan Baru
            </Link>
        </div>

        <div v-if="submissions.length === 0" class="flex flex-col items-center justify-center bg-white rounded-3xl border border-slate-100 p-12 shadow-sm mt-6">
            <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Pengajuan</h3>
            <p class="text-slate-500 text-center max-w-md">Anda belum pernah membuat pengajuan layanan apapun. Silakan klik tombol di atas untuk memulai proses pendaftaran.</p>
        </div>

        <div v-else class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden mt-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-500 text-xs uppercase tracking-wider font-bold">
                            <th class="px-8 py-5">Tanggal Pengajuan</th>
                            <th class="px-8 py-5">Jenis Layanan</th>
                            <th class="px-8 py-5">Status</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="sub in submissions" :key="sub.id" class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">{{ new Date(sub.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ new Date(sub.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }} WIB</div>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-sm capitalize">
                                    {{ sub.service_type }}
                                </div>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <span :class="['px-3 py-1.5 inline-flex text-xs font-bold rounded-lg', getStatusColor(sub.status)]">
                                    {{ getStatusText(sub.status) }}
                                </span>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap text-right">
                                <Link :href="route('submissions.show', sub.id)" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-800 transition-colors">
                                    Lihat Detail
                                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </KuaLayout>
</template>
