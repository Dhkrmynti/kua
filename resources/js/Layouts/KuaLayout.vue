<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const showingNavigationDropdown = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value.role === 'admin' || user.value.role === 'kepalakua');

const menuItems = computed(() => {
    let items = [
        { name: 'Beranda', href: route('dashboard'), active: route().current('dashboard'), icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    ];

    if (!isAdmin.value) {
        items.push({ name: 'Layanan Saya', href: route('submissions.index'), active: route().current('submissions.*'), icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' });
        items.push({ name: 'Pengaduan', href: route('complaints.index'), active: route().current('complaints.*'), icon: 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z' });
    } else {
        items.push({ name: 'Kelola Pengajuan', href: route('admin.submissions.index'), active: route().current('admin.submissions.*'), icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' });
        items.push({ name: 'Kotak Pengaduan', href: route('admin.complaints.index'), active: route().current('admin.complaints.*'), icon: 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z' });
    }

    return items;
});
</script>

<template>
    <!-- Wrapper utama: h-screen dan overflow-hidden agar layout tidak scroll secara global -->
    <div class="h-screen flex overflow-hidden bg-[#F8FAFC]">
        
        <!-- Sidebar (Desktop) -->
        <aside class="hidden md:flex flex-col w-72 bg-[#022C22] shadow-2xl z-20 text-emerald-50 transition-all duration-300">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center px-8 border-b border-white/10 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/20 rounded-full blur-2xl"></div>
                <div class="relative flex items-center space-x-3">
                    <ApplicationLogo class="w-10 h-10 shrink-0" />
                    <div class="leading-tight">
                        <span class="block font-extrabold text-base text-white">Kantor Urusan Agama</span>
                        <span class="block text-xs font-medium text-emerald-300/80">Kec. Kemang, Kab. Bogor</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <div class="flex-1 overflow-y-auto py-6 custom-scrollbar">
                <nav class="space-y-2 px-4">
                    <Link
                        v-for="item in menuItems"
                        :key="item.name"
                        :href="item.href"
                        :class="[
                            item.active 
                                ? 'bg-emerald-800/60 text-white shadow-inner border-l-4 border-emerald-400' 
                                : 'text-emerald-100/70 hover:bg-emerald-800/30 hover:text-white border-l-4 border-transparent',
                            'group flex items-center px-4 py-3.5 text-sm font-semibold rounded-r-xl transition-all duration-200'
                        ]"
                    >
                        <svg :class="['mr-3 flex-shrink-0 h-5 w-5 transition-transform duration-200 group-hover:scale-110', item.active ? 'text-emerald-400' : 'text-emerald-500/70']" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" :d="item.icon" />
                        </svg>
                        {{ item.name }}
                    </Link>
                </nav>
            </div>

            <!-- Sidebar Footer / Profile -->
            <div class="p-5 border-t border-white/10 bg-black/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-700 flex items-center justify-center font-bold text-lg border-2 border-emerald-500/30">
                        {{ user.name.charAt(0) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ user.name }}</p>
                        <p class="text-xs font-medium text-emerald-400/80 capitalize truncate">{{ user.role }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area (Scrollable Independently) -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            
            <!-- Header (Glassmorphism + Sticky) -->
            <header class="sticky top-0 z-30 bg-slate-50/80 backdrop-blur-lg border-b border-gray-200/60 h-20 flex items-center justify-between px-6 lg:px-10 transition-all">
                <div class="flex items-center space-x-2 md:hidden font-extrabold text-emerald-800 text-lg tracking-tight">
                    <ApplicationLogo class="w-9 h-9 shrink-0" />
                    <span>KUA Kemang</span>
                </div>
                
                <div class="hidden md:block">
                    <!-- Optional: Breadcrumbs or Page Title could go here -->
                </div>

                <div class="flex items-center space-x-5">
                    <Link :href="route('profile.edit')" class="text-sm text-gray-500 hover:text-emerald-600 font-bold transition-colors">
                        Pengaturan
                    </Link>
                    <div class="w-px h-5 bg-gray-300"></div>
                    <Link :href="route('logout')" method="post" as="button" class="text-sm bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white px-5 py-2.5 rounded-full font-bold transition-all shadow-sm hover:shadow-md">
                        Keluar
                    </Link>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-10 relative">
                <!-- Mobile Navigation -->
                <div class="md:hidden mb-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex space-x-2 overflow-x-auto">
                    <Link
                        v-for="item in menuItems"
                        :key="item.name"
                        :href="item.href"
                        :class="[
                            item.active ? 'bg-emerald-50 text-emerald-700' : 'text-gray-500 hover:bg-gray-50',
                            'px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-colors'
                        ]"
                    >
                        {{ item.name }}
                    </Link>
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>
