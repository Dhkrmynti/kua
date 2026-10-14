<script setup>
import KuaLayout from '@/Layouts/KuaLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const form = useForm({
    service_type: 'nikah',
    form_data: {
        suami_name: '',
        istri_name: '',
        tanggal_usulan: '',
        nomor_putusan: ''
    },
    documents: null
});

const handleFileChange = (e, type) => {
    if (!form.documents) form.documents = {};
    form.documents[type] = e.target.files[0];
};

const submit = () => {
    form.post(route('submissions.store'));
};
</script>

<template>
    <Head title="Buat Pengajuan Baru" />

    <KuaLayout>
        <div class="mb-8 flex items-center space-x-4">
            <Link :href="route('submissions.index')" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-200 text-slate-500 hover:text-emerald-600 hover:border-emerald-200 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
            </Link>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Form Pendaftaran Layanan</h2>
                <p class="text-slate-500 mt-1 font-medium">Lengkapi data dan dokumen persyaratan di bawah ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Left Info Panel -->
            <div class="bg-emerald-800 rounded-3xl p-8 text-emerald-50 shadow-xl shadow-emerald-900/10 relative overflow-hidden hidden lg:block sticky top-8">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-emerald-500/30 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <svg class="w-12 h-12 text-emerald-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-white mb-4">Panduan Pengisian</h3>
                    <ul class="space-y-4 text-emerald-100/90 text-sm leading-relaxed">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-emerald-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Pilih jenis layanan yang sesuai dengan kebutuhan Anda.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-emerald-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Pastikan dokumen yang diunggah terbaca dengan jelas (Maks. 2MB).
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-emerald-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Setelah dikirim, status dapat dipantau di menu Riwayat Layanan.
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="lg:col-span-2">
                <form @submit.prevent="submit" class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-8 sm:p-10">
                    <!-- Pilihan Layanan -->
                    <div class="mb-10">
                        <label class="block text-sm font-bold text-slate-700 mb-3">Pilih Kategori Layanan</label>
                        <select v-model="form.service_type" class="block w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl bg-slate-50 py-3 transition-colors text-slate-700 font-medium cursor-pointer">
                            <option value="nikah">Pendaftaran Nikah</option>
                            <option value="talak">Pelaporan Akta Talak</option>
                            <option value="cerai">Pelaporan Akta Cerai</option>
                            <option value="rujuk">Pendaftaran Rujuk</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.service_type" />
                    </div>

                    <!-- Form Dinamis: NIKAH & RUJUK -->
                    <div v-if="['nikah', 'rujuk'].includes(form.service_type)" class="space-y-6 mb-10 p-6 bg-emerald-50/50 rounded-2xl border border-emerald-100/50">
                        <h4 class="font-bold text-emerald-800 text-lg border-b border-emerald-100 pb-3 mb-4">Data Pasangan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama (Calon) Suami</label>
                                <input type="text" class="block w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl px-4 py-3 bg-white" v-model="form.form_data.suami_name" required placeholder="Sesuai KTP" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama (Calon) Istri</label>
                                <input type="text" class="block w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl px-4 py-3 bg-white" v-model="form.form_data.istri_name" required placeholder="Sesuai KTP" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Usulan Tanggal Pelaksanaan</label>
                                <input type="date" class="block w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl px-4 py-3 bg-white" v-model="form.form_data.tanggal_usulan" required />
                            </div>
                        </div>
                    </div>

                    <!-- Form Dinamis: TALAK & CERAI -->
                    <div v-if="['talak', 'cerai'].includes(form.service_type)" class="space-y-6 mb-10 p-6 bg-emerald-50/50 rounded-2xl border border-emerald-100/50">
                        <h4 class="font-bold text-emerald-800 text-lg border-b border-emerald-100 pb-3 mb-4">Data Putusan Pengadilan</h4>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Putusan Pengadilan Agama</label>
                            <input type="text" class="block w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl px-4 py-3 bg-white" v-model="form.form_data.nomor_putusan" required placeholder="Contoh: 123/Pdt.G/2023/PA.Xyz" />
                        </div>
                    </div>

                    <!-- Upload Dokumen Wajib -->
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg mb-4">Unggah Dokumen Persyaratan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <div class="relative border-2 border-dashed border-slate-200 rounded-2xl p-6 hover:bg-slate-50 hover:border-emerald-400 transition-colors group cursor-pointer">
                                <label class="cursor-pointer block text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-400 group-hover:text-emerald-500 mb-3 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="block text-sm font-bold text-slate-700">Scan KTP Pemohon</span>
                                    <span class="block text-xs text-slate-500 mt-1">Klik untuk memilih file</span>
                                    <input type="file" @change="e => handleFileChange(e, 'ktp')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                </label>
                            </div>
                            
                            <div class="relative border-2 border-dashed border-slate-200 rounded-2xl p-6 hover:bg-slate-50 hover:border-emerald-400 transition-colors group cursor-pointer">
                                <label class="cursor-pointer block text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-400 group-hover:text-emerald-500 mb-3 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="block text-sm font-bold text-slate-700">Scan Kartu Keluarga</span>
                                    <span class="block text-xs text-slate-500 mt-1">Klik untuk memilih file</span>
                                    <input type="file" @change="e => handleFileChange(e, 'kk')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                </label>
                            </div>

                            <div v-if="form.service_type === 'nikah'" class="relative border-2 border-dashed border-slate-200 rounded-2xl p-6 hover:bg-slate-50 hover:border-emerald-400 transition-colors group cursor-pointer">
                                <label class="cursor-pointer block text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-400 group-hover:text-emerald-500 mb-3 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="block text-sm font-bold text-slate-700">Surat N1-N4</span>
                                    <span class="block text-xs text-slate-500 mt-1">(Dari Desa/Kelurahan)</span>
                                    <input type="file" @change="e => handleFileChange(e, 'n1_n4')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                </label>
                            </div>

                            <div v-if="['talak', 'cerai', 'rujuk'].includes(form.service_type)" class="relative border-2 border-dashed border-slate-200 rounded-2xl p-6 hover:bg-slate-50 hover:border-emerald-400 transition-colors group cursor-pointer">
                                <label class="cursor-pointer block text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-400 group-hover:text-emerald-500 mb-3 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="block text-sm font-bold text-slate-700">Akta Cerai / Putusan</span>
                                    <span class="block text-xs text-slate-500 mt-1">Scan Dokumen Asli</span>
                                    <input type="file" @change="e => handleFileChange(e, 'akta_cerai')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end">
                        <button type="submit" :disabled="form.processing" class="bg-emerald-600 text-white font-bold px-8 py-3.5 rounded-xl hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-500/30 transition-all active:scale-95 disabled:opacity-50 flex items-center">
                            Kirim Pengajuan
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </KuaLayout>
</template>
