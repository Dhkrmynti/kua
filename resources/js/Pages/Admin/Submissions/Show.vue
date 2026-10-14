<script setup>
import KuaLayout from '@/Layouts/KuaLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    submission: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    status: props.submission.status,
    admin_notes: props.submission.admin_notes || ''
});

const submit = () => {
    form.put(route('admin.submissions.status.update', props.submission.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Review Pengajuan" />

    <KuaLayout>
        <div class="mb-6 flex items-center space-x-4">
            <Link :href="route('admin.submissions.index')" class="text-gray-500 hover:text-gray-700 font-medium">
                &larr; Kembali
            </Link>
            <h2 class="text-2xl font-bold text-gray-800">Review Pengajuan</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Data Pengajuan -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-emerald-800 border-b pb-2 mb-4">Informasi Layanan</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Nama Pemohon</p>
                            <p class="font-medium">{{ submission.user.name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Jenis Layanan</p>
                            <p class="font-medium capitalize">{{ submission.service_type }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Tanggal Pengajuan</p>
                            <p class="font-medium">{{ new Date(submission.created_at).toLocaleString('id-ID') }}</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-emerald-800 border-b pb-2 mt-6 mb-4">Data Detail Form</h3>
                    <ul class="space-y-3">
                        <li v-for="(value, key) in submission.form_data" :key="key" class="bg-gray-50 p-3 rounded-md">
                            <span class="block text-xs text-gray-500 uppercase tracking-wider font-semibold">{{ key.replace('_', ' ') }}</span> 
                            <span class="text-gray-900 font-medium">{{ value }}</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-emerald-800 border-b pb-2 mb-4">Dokumen Terlampir</h3>
                    <div v-if="submission.documents.length === 0" class="text-gray-500 italic">
                        Tidak ada dokumen yang dilampirkan.
                    </div>
                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a v-for="doc in submission.documents" :key="doc.id" :href="`/storage/${doc.file_path}`" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-emerald-50 transition group">
                            <svg class="h-6 w-6 text-gray-400 group-hover:text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span class="font-medium text-gray-700 uppercase">{{ doc.document_type.replace('_', ' ') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Action / Update Status -->
            <div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 sticky top-6">
                    <h3 class="text-lg font-semibold text-emerald-800 border-b pb-2 mb-4">Tindak Lanjut</h3>
                    <form @submit.prevent="submit" class="space-y-4">
                        
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Update Status</label>
                            <select v-model="form.status" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="pending">Menunggu Verifikasi</option>
                                <option value="verified">Terverifikasi (Dokumen Lengkap)</option>
                                <option value="scheduled">Dijadwalkan</option>
                                <option value="completed">Selesai / Terbit</option>
                                <option value="rejected">Ditolak / Berkas Kurang</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Catatan Petugas (Opsional)</label>
                            <textarea v-model="form.admin_notes" rows="4" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" placeholder="Berikan catatan jika ada dokumen kurang atau informasi jadwal..."></textarea>
                        </div>

                        <PrimaryButton class="w-full justify-center bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Simpan Perubahan
                        </PrimaryButton>

                        <div v-if="form.recentlySuccessful" class="text-sm text-green-600 text-center mt-2 font-medium">
                            Berhasil disimpan!
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </KuaLayout>
</template>
