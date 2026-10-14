<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
        <div class="w-full flex flex-col md:flex-row min-h-screen">
            
            <!-- Left Branding Side -->
            <div class="md:w-1/2 bg-emerald-700 bg-gradient-to-br from-emerald-800 to-emerald-600 text-white flex flex-col justify-center items-center p-12 text-center shadow-xl z-10 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')]"></div>
                
                <div class="relative z-10">
                    <Link href="/">
                        <ApplicationLogo class="w-32 h-32 mx-auto mb-6" />
                    </Link>
                    <h1 class="text-4xl font-extrabold tracking-tight mb-2">Kantor Urusan Agama</h1>
                    <p class="text-lg font-semibold text-emerald-200 mb-4">Kecamatan Kemang, Kab. Bogor, Jawa Barat</p>
                    <p class="text-lg text-emerald-100 max-w-md mx-auto leading-relaxed">
                        Sistem informasi layanan pengajuan dan pengaduan nikah, talak, cerai, dan rujuk secara cerdas, cepat, dan transparan.
                    </p>
                </div>
            </div>

            <!-- Right Form Side -->
            <div class="md:w-1/2 flex items-center justify-center p-6 bg-slate-50">
                <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
                    <div class="mb-8 md:hidden text-center">
                        <Link href="/">
                            <ApplicationLogo class="w-20 h-20 mx-auto" />
                        </Link>
                        <h2 class="mt-4 text-2xl font-bold text-gray-900">Kantor Urusan Agama</h2>
                        <p class="text-sm text-gray-500">Kecamatan Kemang, Kab. Bogor, Jawa Barat</p>
                    </div>
                    
                    <slot />
                </div>
            </div>

        </div>
    </div>
</template>
