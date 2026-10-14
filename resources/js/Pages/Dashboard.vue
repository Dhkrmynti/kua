<script setup>
import KuaLayout from '@/Layouts/KuaLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: {
        type: Object,
        default: () => ({ total: 0, pending: 0, completed: 0 })
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <KuaLayout>
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard</h2>
                <p class="text-gray-500 mt-1 font-medium">Ringkasan aktivitas dan pengajuan Anda.</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-[#022C22] to-emerald-800 rounded-3xl shadow-xl shadow-emerald-900/10 overflow-hidden relative border border-emerald-700/50">
            <!-- Decorative circle -->
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl"></div>
            
            <div class="p-10 relative z-10">
                <h3 class="text-2xl font-bold text-white mb-3">Selamat Datang di Kantor Urusan Agama Kecamatan Kemang!</h3>
                <p class="text-emerald-100/90 mb-8 max-w-xl leading-relaxed text-lg font-medium">
                    Sistem informasi layanan pengajuan dan pengaduan nikah, talak, cerai, dan rujuk secara online terpadu. Lebih cepat, lebih transparan.
                </p>
                <div class="flex flex-wrap gap-4">
                    <Link :href="route('submissions.create')" class="bg-white hover:bg-emerald-50 text-emerald-800 font-bold py-3.5 px-7 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Mulai Pengajuan Baru
                    </Link>
                    <Link :href="route('submissions.index')" class="bg-emerald-900/40 border border-emerald-500/30 text-white hover:bg-emerald-900/60 font-bold py-3.5 px-7 rounded-xl transition-all backdrop-blur-sm">
                        Cek Status Pengajuan
                    </Link>
                </div>
            </div>
        </div>
        
        <!-- Add a quick stats row to make it look like a real dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="text-gray-500 font-bold text-sm uppercase tracking-wider mb-2">Total Pengajuan</div>
                <div class="text-3xl font-black text-gray-900">{{ stats.total }}</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="text-amber-500 font-bold text-sm uppercase tracking-wider mb-2">Menunggu Verifikasi</div>
                <div class="text-3xl font-black text-amber-600">{{ stats.pending }}</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="text-emerald-500 font-bold text-sm uppercase tracking-wider mb-2">Selesai / Terbit</div>
                <div class="text-3xl font-black text-emerald-600">{{ stats.completed }}</div>
            </div>
        </div>
    </KuaLayout>
</template>
