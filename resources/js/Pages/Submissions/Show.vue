<script setup>
import KuaLayout from '@/Layouts/KuaLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import QrcodeVue from 'qrcode.vue';
import { computed } from 'vue';

const props = defineProps({
    submission: {
        type: Object,
        required: true,
    }
});

const getStatusColor = (status) => {
    switch (status) {
        case 'pending': return 'bg-amber-100 text-amber-800 border-amber-200';
        case 'verified': return 'bg-blue-100 text-blue-800 border-blue-200';
        case 'scheduled': return 'bg-purple-100 text-purple-800 border-purple-200';
        case 'completed': return 'bg-emerald-100 text-emerald-800 border-emerald-200';
        case 'rejected': return 'bg-rose-100 text-rose-800 border-rose-200';
        default: return 'bg-slate-100 text-slate-800 border-slate-200';
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

const trackingId = computed(() => `E-KUA-${String(props.submission.id).padStart(5, '0')}`);
const qrValue = computed(() => `https://ekua.local/verify/${trackingId.value}`);
</script>

<template>
    <Head title="Detail Pengajuan" />

    <KuaLayout>
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <Link :href="route('submissions.index')" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-200 text-slate-500 hover:text-emerald-600 hover:border-emerald-200 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </Link>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Detail Pengajuan</h2>
                    <p class="text-slate-500 mt-1 font-medium">Informasi lengkap dan status layanan Anda.</p>
                </div>
            </div>
            
            <span :class="['px-5 py-2.5 rounded-xl text-sm font-extrabold border', getStatusColor(submission.status)]">
                {{ getStatusText(submission.status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Data Panel -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Form -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-8">
                    <div class="flex items-center mb-6 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 capitalize">Layanan: {{ submission.service_type }}</h3>
                            <p class="text-sm text-slate-500">Diajukan pada {{ new Date(submission.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                        <div v-for="(value, key) in submission.form_data" :key="key" class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">{{ key.replace('_', ' ') }}</span>
                            <span class="block font-semibold text-slate-800 text-lg">{{ value }}</span>
                        </div>
                    </div>
                </div>

                <!-- Catatan Petugas -->
                <div v-if="submission.admin_notes" class="bg-amber-50 rounded-3xl border border-amber-200 shadow-sm p-8 flex items-start space-x-4">
                    <div class="w-10 h-10 bg-amber-200 text-amber-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-amber-800 mb-1">Catatan dari KUA</h4>
                        <p class="text-amber-900 leading-relaxed">{{ submission.admin_notes }}</p>
                    </div>
                </div>
            </div>

            <!-- Right QR Ticket Panel -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-[#022C22] to-emerald-800 rounded-3xl text-white shadow-xl shadow-emerald-900/10 overflow-hidden relative sticky top-8">
                    <!-- Ticket Design Top -->
                    <div class="p-8 text-center border-b border-white/10 border-dashed relative">
                        <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-slate-50 rounded-full"></div>
                        <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-slate-50 rounded-full"></div>
                        
                        <h3 class="text-emerald-300 font-bold tracking-widest uppercase text-sm mb-2">E-Ticket Pengajuan</h3>
                        <div class="text-3xl font-black font-mono tracking-wider mb-6">{{ trackingId }}</div>
                        
                        <!-- QR Code Container -->
                        <div class="bg-white p-4 rounded-2xl inline-block shadow-lg mx-auto">
                            <qrcode-vue :value="qrValue" :size="180" level="H" foreground="#022C22" />
                        </div>
                    </div>
                    
                    <!-- Ticket Design Bottom -->
                    <div class="p-8 text-center bg-emerald-900/30">
                        <p class="text-sm text-emerald-100/90 leading-relaxed">
                            Tunjukkan QR Code ini kepada petugas loket KUA untuk verifikasi fisik berkas atau pemanggilan cepat.
                        </p>
                        <button class="mt-6 w-full py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl font-bold transition-colors">
                            Unduh Tiket (PDF)
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </KuaLayout>
</template>
