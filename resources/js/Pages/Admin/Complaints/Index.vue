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

const selectedComplaint = ref(null);

const form = useForm({
    admin_reply: '',
    status: 'closed',
});

const openReplyModal = (complaint) => {
    selectedComplaint.value = complaint;
    form.admin_reply = complaint.admin_reply || '';
    form.status = complaint.status;
};

const submitReply = () => {
    form.put(route('admin.complaints.update', selectedComplaint.value.id), {
        onSuccess: () => {
            selectedComplaint.value = null;
            form.reset();
        }
    });
};
</script>

<template>
    <Head title="Kelola Pengaduan" />

    <KuaLayout>
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Kotak Pengaduan Masyarakat</h2>
            <p class="text-slate-500 mt-1 font-medium">Tinjau dan berikan tanggapan untuk keluhan yang masuk.</p>
        </div>

        <div v-if="complaints.length === 0" class="flex flex-col items-center justify-center bg-white rounded-3xl border border-slate-100 p-12 shadow-sm mt-6">
            <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Kotak Pengaduan Kosong</h3>
            <p class="text-slate-500 text-center max-w-md">Belum ada pengaduan masyarakat yang masuk saat ini.</p>
        </div>

        <div v-else class="grid grid-cols-1 gap-6 mt-6">
            <div v-for="complaint in complaints" :key="complaint.id" class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 md:p-8 flex flex-col lg:flex-row gap-6 items-start">
                
                <div class="flex-1 w-full">
                    <div class="flex items-center space-x-3 mb-3">
                        <span :class="['px-3 py-1 text-xs font-bold rounded-lg', complaint.status === 'open' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800']">
                            {{ complaint.status === 'open' ? 'Menunggu Balasan' : 'Selesai' }}
                        </span>
                        <span class="text-sm font-semibold text-slate-400">
                            {{ new Date(complaint.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-800 mb-1">{{ complaint.subject }}</h3>
                    <p class="text-sm font-bold text-emerald-600 mb-4">Pengirim: {{ complaint.user.name }} ({{ complaint.user.email }})</p>
                    <p class="text-slate-600 leading-relaxed mb-6">{{ complaint.message }}</p>
                    
                    <div v-if="complaint.admin_reply" class="bg-emerald-50 rounded-2xl p-5 border border-emerald-100/50 relative">
                        <h4 class="font-bold text-emerald-800 text-sm mb-2 uppercase tracking-wide">Tanggapan KUA:</h4>
                        <p class="text-emerald-900 text-sm leading-relaxed">{{ complaint.admin_reply }}</p>
                    </div>
                </div>

                <div class="w-full lg:w-48 flex-shrink-0 lg:text-right border-t lg:border-t-0 pt-4 lg:pt-0 border-slate-100">
                    <button @click="openReplyModal(complaint)" class="w-full lg:w-auto inline-flex items-center justify-center bg-slate-800 text-white font-bold px-6 py-3 rounded-xl hover:bg-slate-700 transition-all">
                        {{ complaint.admin_reply ? 'Edit Balasan' : 'Beri Balasan' }}
                    </button>
                </div>

            </div>
        </div>

        <!-- Modal Balas Pengaduan -->
        <div v-if="selectedComplaint" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 transform transition-all">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-xl font-bold text-slate-800">Tanggapi Pengaduan</h3>
                    <button @click="selectedComplaint = null" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form @submit.prevent="submitReply" class="p-8">
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pesan Tanggapan (Terlihat oleh User)</label>
                        <textarea v-model="form.admin_reply" required rows="4" class="block w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl px-4 py-3" placeholder="Tuliskan solusi atau tanggapan Anda..."></textarea>
                    </div>
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 mb-3">Status Tiket</label>
                        <select v-model="form.status" class="block w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl bg-slate-50 py-3 font-medium">
                            <option value="open">Biarkan Terbuka (Open)</option>
                            <option value="closed">Selesai (Closed)</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="selectedComplaint = null" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" :disabled="form.processing" class="bg-emerald-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-emerald-700 transition-all disabled:opacity-50">
                            Kirim Balasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </KuaLayout>
</template>
