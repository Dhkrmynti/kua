<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Selamat Datang" />

    <div class="min-h-screen bg-slate-50 font-sans text-gray-900 selection:bg-emerald-500 selection:text-white">
        <!-- Navbar -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <ApplicationLogo class="w-12 h-12 shrink-0" />
                    <div class="leading-tight">
                        <span class="block text-lg sm:text-xl font-bold text-emerald-800">Kantor Urusan Agama</span>
                        <span class="block text-xs sm:text-sm text-gray-500">Kecamatan Kemang, Kab. Bogor, Jawa Barat</span>
                    </div>
                </div>
                
                <nav v-if="canLogin" class="flex items-center space-x-6">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-emerald-700 hover:text-emerald-900 font-semibold transition"
                    >
                        Ke Dashboard &rarr;
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-gray-600 hover:text-emerald-600 font-medium transition"
                        >
                            Masuk
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-full font-medium transition shadow-md hover:shadow-lg"
                        >
                            Daftar Sekarang
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <main>
            <section class="relative bg-emerald-700 overflow-hidden text-white">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')]"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-32 lg:pb-40 text-center">
                    <p class="text-sm md:text-base font-semibold uppercase tracking-widest text-emerald-200 mb-4">
                        Kecamatan Kemang, Kab. Bogor, Jawa Barat
                    </p>
                    <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight mb-6">
                        Kantor Urusan Agama dalam <span class="text-emerald-200">Satu Genggaman</span>
                    </h1>
                    <p class="mt-4 text-xl md:text-2xl text-emerald-100 max-w-3xl mx-auto mb-10 leading-relaxed">
                        Layanan pengajuan dan pengaduan Nikah, Talak, Cerai, dan Rujuk secara terintegrasi, transparan, dan mudah tanpa harus antre lama di kantor.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="route('register')"
                            class="bg-white text-emerald-700 px-8 py-4 rounded-full font-bold text-lg hover:bg-emerald-50 transition shadow-xl"
                        >
                            Mulai Pengajuan
                        </Link>
                        <a
                            href="#fitur"
                            class="border-2 border-emerald-300 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-emerald-600 transition"
                        >
                            Pelajari Fitur
                        </a>
                    </div>
                </div>
                
                <!-- Decorative bottom wave -->
                <div class="absolute bottom-0 inset-x-0">
                    <svg class="w-full h-12 md:h-24 fill-slate-50" viewBox="0 0 1440 120" preserveAspectRatio="none">
                        <path d="M0,0 C320,120 420,120 720,60 C1020,0 1120,0 1440,120 L1440,120 L0,120 Z"></path>
                    </svg>
                </div>
            </section>

            <!-- Features Section -->
            <section id="fitur" class="py-20 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-emerald-800">Kenapa Menggunakan Layanan Ini?</h2>
                        <p class="mt-4 text-lg text-gray-600">Pelayanan yang lebih efisien untuk masyarakat Kecamatan Kemang, Kabupaten Bogor, Prov. Jawa Barat.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition">
                            <div class="bg-emerald-100 w-16 h-16 rounded-xl flex items-center justify-center mb-6 text-emerald-600">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Lebih Cepat & Praktis</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Tidak perlu lagi membawa map tebal. Cukup unggah dokumen dari rumah dan tunggu verifikasi secara online.
                            </p>
                        </div>
                        
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition">
                            <div class="bg-emerald-100 w-16 h-16 rounded-xl flex items-center justify-center mb-6 text-emerald-600">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Transparan</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Lacak status pengajuan Anda kapan saja (Menunggu, Diverifikasi, atau Dijadwalkan) langsung dari Dashboard akun.
                            </p>
                        </div>
                        
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition">
                            <div class="bg-emerald-100 w-16 h-16 rounded-xl flex items-center justify-center mb-6 text-emerald-600">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Layanan Pengaduan</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Mengalami kendala dalam proses? Sampaikan keluhan langsung melalui sistem tiket yang terintegrasi cepat dengan admin.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="flex items-start space-x-4">
                    <ApplicationLogo class="w-14 h-14 shrink-0" />
                    <div>
                        <p class="text-lg font-bold text-emerald-800">Kantor Urusan Agama</p>
                        <p class="text-gray-600">Kecamatan Kemang, Kab. Bogor, Jawa Barat</p>
                    </div>
                </div>
                <dl class="space-y-3 text-gray-600">
                    <div class="flex">
                        <dt class="w-24 shrink-0 font-semibold text-gray-900">Lokasi</dt>
                        <dd>Jl. Kemang Kiara No. 06, RT: 04/05, Desa Kemang, Kec. Kemang, Kab. Bogor, Prov. Jawa Barat</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-24 shrink-0 font-semibold text-gray-900">No. Telp</dt>
                        <dd><a href="tel:+6287739429167" class="hover:text-emerald-700 transition">0877-3942-9167</a></dd>
                    </div>
                    <div class="flex">
                        <dt class="w-24 shrink-0 font-semibold text-gray-900">E-mail</dt>
                        <dd><a href="mailto:kuakemangkabbogor@gmail.com" class="hover:text-emerald-700 transition break-all">kuakemangkabbogor@gmail.com</a></dd>
                    </div>
                </dl>
            </div>
            <div class="border-t py-6 px-4 text-center text-gray-500">
                <p>&copy; 2026 Kantor Urusan Agama Kecamatan Kemang, Kab. Bogor. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>
