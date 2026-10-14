<script setup>
import KuaLayout from '@/Layouts/KuaLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Pengaturan Akun" />

    <KuaLayout>
        <div class="mb-8 flex items-center space-x-4">
            <Link :href="route('dashboard')" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-200 text-slate-500 hover:text-emerald-600 hover:border-emerald-200 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
            </Link>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pengaturan Akun</h2>
                <p class="text-slate-500 mt-1 font-medium">Kelola informasi profil, keamanan, dan pengaturan akun Anda.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Info / Nav column -->
            <div class="lg:col-span-4">
                <div class="bg-gradient-to-br from-[#022C22] to-emerald-800 rounded-3xl p-8 text-emerald-50 shadow-xl shadow-emerald-900/10 sticky top-8 relative overflow-hidden">
                    <div class="absolute -left-12 -top-12 w-48 h-48 bg-emerald-500/30 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-white/10 border border-white/20 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Pusat Kendali</h3>
                        <p class="text-emerald-100/90 text-sm leading-relaxed mb-6">
                            Gunakan halaman ini untuk memperbarui biodata diri Anda dan memastikan akun tetap aman.
                        </p>
                        
                        <div class="space-y-3">
                            <a href="#informasi-profil" class="flex items-center w-full text-left px-4 py-3 rounded-xl bg-emerald-900/50 text-white font-medium hover:bg-emerald-700/50 transition border border-emerald-500/20">
                                <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Informasi Profil
                            </a>
                            <a href="#keamanan" class="flex items-center w-full text-left px-4 py-3 rounded-xl bg-emerald-900/50 text-white font-medium hover:bg-emerald-700/50 transition border border-emerald-500/20">
                                <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                Ubah Password
                            </a>
                            <a href="#zona-bahaya" class="flex items-center w-full text-left px-4 py-3 rounded-xl bg-rose-900/30 text-rose-200 font-medium hover:bg-rose-900/50 transition border border-rose-500/20">
                                <svg class="w-5 h-5 mr-3 text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                Hapus Akun
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content column -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Profile Information -->
                <section id="informasi-profil" class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-8 sm:p-10">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </section>

                <!-- Update Password -->
                <section id="keamanan" class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-8 sm:p-10">
                    <UpdatePasswordForm class="max-w-xl" />
                </section>

                <!-- Delete Account (Danger Zone) -->
                <section id="zona-bahaya" class="bg-rose-50 rounded-3xl border border-rose-100 shadow-sm p-8 sm:p-10">
                    <DeleteUserForm class="max-w-xl" />
                </section>
            </div>
        </div>
    </KuaLayout>
</template>
