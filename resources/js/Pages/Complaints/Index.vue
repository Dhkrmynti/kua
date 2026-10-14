<script setup>
import KuaLayout from '@/Layouts/KuaLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    complaints: {
        type: Array,
        required: true,
    }
});

const isModalOpen = ref(false);

const form = useForm({
    subject: '',
    message: '',
});

const submit = () => {
    form.post(route('complaints.store'), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        }
    });
};
</script>

<template>
    <Head title="Pengaduan Masyarakat" />

    <KuaLayout>
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Layanan Pengaduan</h2>
                <p class="text-slate-500 mt-1 font-medium">Sampaikan keluhan, kritik, atau saran Anda kepada KUA.</p>
            </div>
            <button @click="isModalOpen = true" class="inline-flex items-center justify-center bg-emerald-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-500/30 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Buat Pengaduan Baru
            </button>
        </div>

        <div v-if="complaints.length === 0" class="flex flex-col items-center justify-center bg-white rounded-3xl border border-slate-100 p-12 shadow-sm mt-6">
            <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Riwayat Tiket</h3>
            <p class="text-slate-500 text-center max-w-md">Anda belum pernah membuat laporan atau pengaduan. Silakan klik tombol di atas jika ada yang ingin disampaikan.</p>
        </div>

        <div v-else class="grid grid-cols-1 gap-6 mt-6">
            <div v-for="complaint in complaints" :key="complaint.id" class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 md:p-8 flex flex-col md:flex-row gap-6">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <span :class="['px-3 py-1 text-xs font-bold rounded-lg', complaint.status === 'open' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800']">
                            {{ complaint.status === 'open' ? 'Menunggu Balasan' : 'Selesai' }}
                        </span>
                        <span class="text-sm font-semibold text-slate-400">
                            {{ new Date(complaint.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">{{ complaint.subject }}</h3>
                    <p class="text-slate-600 leading-relaxed">{{ complaint.message }}</p>
                </div>
                
                <!-- Balasan Admin -->
                <div v-if="complaint.admin_reply" class="w-full md:w-1/3 bg-emerald-50 rounded-2xl p-6 border border-emerald-100/50 relative">
                    <div class="absolute -top-3 -left-3 w-6 h-6 bg-emerald-500 rounded-full text-white flex items-center justify-center border-2 border-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="font-bold text-emerald-800 text-sm mb-2 uppercase tracking-wide">Tanggapan KUA:</h4>
                    <p class="text-emerald-900 text-sm leading-relaxed">{{ complaint.admin_reply }}</p>
                </div>
                <div v-else class="w-full md:w-1/3 flex items-center justify-center p-6 bg-slate-50 border border-slate-100 border-dashed rounded-2xl">
                    <p class="text-sm font-medium text-slate-400 text-center">Belum ada tanggapan dari petugas. Mohon menunggu.</p>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Pengaduan -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 transform transition-all">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-xl font-bold text-slate-800">Tulis Pengaduan Baru</h3>
                    <button @click="isModalOpen = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form @submit.prevent="submit" class="p-8">
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Subjek / Topik</label>
                        <input type="text" v-model="form.subject" required class="block w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl px-4 py-3" placeholder="Contoh: Keterlambatan jadwal sidang" />
                    </div>
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Isi Pesan / Pengaduan</label>
                        <textarea v-model="form.message" required rows="4" class="block w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl px-4 py-3" placeholder="Ceritakan kendala Anda secara detail..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="isModalOpen = false" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" :disabled="form.processing" class="bg-emerald-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-emerald-700 transition-all disabled:opacity-50">
                            Kirim Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </KuaLayout>
</template>
