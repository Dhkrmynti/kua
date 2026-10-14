<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, X, MapPin, Phone, Mail, Clock } from 'lucide-vue-next';
import AppLogo from '@/Components/AppLogo.vue';
import FlashToast from '@/Components/FlashToast.vue';

const page = usePage();
const menuTerbuka = ref(false);

const nav = [
    { label: 'Beranda', href: '/' },
    { label: 'Layanan', href: '/#layanan' },
    { label: 'Alur', href: '/#alur' },
    { label: 'Lacak Berkas', href: '/lacak' },
];
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <header class="sticky top-0 z-40 border-b border-stone-200/80 bg-white/90 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4">
                <Link href="/"><AppLogo /></Link>

                <nav class="hidden items-center gap-1 md:flex">
                    <a
                        v-for="item in nav"
                        :key="item.href"
                        :href="item.href"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-stone-600 hover:bg-stone-100 hover:text-stone-900"
                    >{{ item.label }}</a>
                </nav>

                <div class="hidden items-center gap-2 md:flex">
                    <Link v-if="page.props.auth.user" href="/dashboard" class="btn-primary">Dashboard</Link>
                    <template v-else>
                        <Link href="/login" class="btn-secondary">Masuk</Link>
                        <Link href="/register" class="btn-primary">Daftar</Link>
                    </template>
                </div>

                <button type="button" class="rounded-lg p-2 text-stone-600 md:hidden" aria-label="Menu" @click="menuTerbuka = !menuTerbuka">
                    <X v-if="menuTerbuka" class="size-6" />
                    <Menu v-else class="size-6" />
                </button>
            </div>

            <div v-if="menuTerbuka" class="border-t border-stone-200 bg-white px-4 py-3 md:hidden">
                <a v-for="item in nav" :key="item.href" :href="item.href" class="block rounded-lg px-3 py-2 text-sm font-medium text-stone-700" @click="menuTerbuka = false">
                    {{ item.label }}
                </a>
                <div class="mt-2 grid grid-cols-2 gap-2">
                    <Link v-if="page.props.auth.user" href="/dashboard" class="btn-primary col-span-2">Dashboard</Link>
                    <template v-else>
                        <Link href="/login" class="btn-secondary">Masuk</Link>
                        <Link href="/register" class="btn-primary">Daftar</Link>
                    </template>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <footer class="bg-brand-900 text-brand-100">
            <div class="mx-auto grid max-w-6xl gap-8 px-4 py-12 md:grid-cols-3">
                <div>
                    <AppLogo light />
                    <p class="mt-4 text-sm leading-relaxed text-brand-200/80">
                        Layanan pengajuan dan pengaduan nikah, talak, cerai, dan rujuk secara daring untuk masyarakat {{ page.props.kua.kabupaten }}.
                    </p>
                </div>
                <div>
                    <h3 class="mb-3 text-sm font-bold text-white">Kontak</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex gap-2"><MapPin class="mt-0.5 size-4 shrink-0 text-gold-400" />{{ page.props.kua.alamat }}</li>
                        <li class="flex gap-2"><Phone class="mt-0.5 size-4 shrink-0 text-gold-400" />{{ page.props.kua.telepon }}</li>
                        <li class="flex gap-2"><Mail class="mt-0.5 size-4 shrink-0 text-gold-400" />{{ page.props.kua.email }}</li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-3 text-sm font-bold text-white">Jam Layanan</h3>
                    <p class="flex gap-2 text-sm"><Clock class="mt-0.5 size-4 shrink-0 text-gold-400" />{{ page.props.kua.jam_layanan }}</p>
                    <p class="mt-4 text-xs text-brand-200/70">
                        Seluruh layanan pencatatan nikah di KUA pada jam kerja tidak dipungut biaya (PP No. 48 Tahun 2014).
                    </p>
                </div>
            </div>
            <div class="border-t border-white/10 py-4 text-center text-xs text-brand-200/60">
                © {{ new Date().getFullYear() }} Kantor Urusan Agama {{ page.props.kua.nama }}
            </div>
        </footer>

        <FlashToast />
    </div>
</template>
