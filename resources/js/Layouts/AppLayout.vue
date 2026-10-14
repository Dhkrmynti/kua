<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    LayoutDashboard, FileText, FilePlus2, MessageSquareWarning, UserCog, Users, LogOut, Menu, X, Globe,
} from 'lucide-vue-next';
import AppLogo from '@/Components/AppLogo.vue';
import FlashToast from '@/Components/FlashToast.vue';

defineProps({
    title: { type: String, default: '' },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const sidebarTerbuka = ref(false);

const menu = computed(() => {
    const role = user.value?.role;
    const badges = page.props.badges ?? {};

    if (role === 'masyarakat') {
        return [
            { label: 'Dashboard', href: '/dashboard', icon: LayoutDashboard },
            { label: 'Pengajuan Saya', href: '/pengajuan', icon: FileText, exact: true },
            { label: 'Buat Pengajuan', href: '/pengajuan/create', icon: FilePlus2 },
            { label: 'Pengaduan', href: '/pengaduan', icon: MessageSquareWarning },
            { label: 'Profil', href: '/profil', icon: UserCog },
        ];
    }

    return [
        { label: 'Dashboard', href: '/dashboard', icon: LayoutDashboard },
        { label: 'Kelola Pengajuan', href: '/admin/pengajuan', icon: FileText, badge: badges.pengajuan },
        { label: 'Kelola Pengaduan', href: '/admin/pengaduan', icon: MessageSquareWarning, badge: badges.pengaduan },
        ...(role === 'admin' ? [{ label: 'Pengguna', href: '/admin/users', icon: Users }] : []),
        { label: 'Profil', href: '/profil', icon: UserCog },
    ];
});

function aktif(item) {
    const url = page.url.split('?')[0];
    if (item.exact) {
        return url === item.href || (/^\/pengajuan\/\d+/.test(url) && item.href === '/pengajuan');
    }
    return url === item.href || url.startsWith(item.href + '/');
}

function keluar() {
    router.post('/logout');
}
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen lg:pl-64">
        <!-- Sidebar -->
        <div v-if="sidebarTerbuka" class="fixed inset-0 z-40 bg-stone-900/40 lg:hidden" @click="sidebarTerbuka = false" />
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-brand-900 pattern-islami transition-transform lg:translate-x-0"
            :class="sidebarTerbuka ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-16 items-center justify-between px-5">
                <Link href="/dashboard"><AppLogo light /></Link>
                <button class="text-brand-100 lg:hidden" aria-label="Tutup menu" @click="sidebarTerbuka = false"><X class="size-5" /></button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <Link
                    v-for="item in menu"
                    :key="item.href"
                    :href="item.href"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition"
                    :class="aktif(item) ? 'bg-white/10 text-white' : 'text-brand-100/80 hover:bg-white/5 hover:text-white'"
                    @click="sidebarTerbuka = false"
                >
                    <component :is="item.icon" class="size-[18px]" :class="aktif(item) ? 'text-gold-400' : ''" />
                    <span class="flex-1">{{ item.label }}</span>
                    <span v-if="item.badge" class="rounded-full bg-gold-500 px-2 py-0.5 text-[11px] font-bold text-brand-900">{{ item.badge }}</span>
                </Link>
            </nav>

            <div class="space-y-1 border-t border-white/10 p-3">
                <Link href="/" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-brand-100/80 hover:bg-white/5 hover:text-white">
                    <Globe class="size-[18px]" /> Halaman Publik
                </Link>
                <button type="button" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-brand-100/80 hover:bg-white/5 hover:text-white" @click="keluar">
                    <LogOut class="size-[18px]" /> Keluar
                </button>
            </div>
        </aside>

        <!-- Topbar -->
        <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-stone-200 bg-white/90 px-4 backdrop-blur sm:px-6">
            <button class="rounded-lg p-1.5 text-stone-600 lg:hidden" aria-label="Buka menu" @click="sidebarTerbuka = true">
                <Menu class="size-6" />
            </button>
            <h1 class="flex-1 truncate text-base font-bold text-stone-900 sm:text-lg">{{ title }}</h1>
            <div class="flex items-center gap-3">
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-semibold text-stone-800">{{ user?.name }}</p>
                    <p class="text-xs text-stone-500">{{ user?.roleLabel }}</p>
                </div>
                <span class="grid size-9 place-items-center rounded-full bg-brand-100 text-sm font-bold text-brand-700">
                    {{ user?.name?.charAt(0) }}
                </span>
            </div>
        </header>

        <main class="mx-auto max-w-6xl p-4 sm:p-6">
            <slot />
        </main>

        <FlashToast />
    </div>
</template>
