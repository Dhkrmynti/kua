<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    HeartHandshake, Scale, FileX2, RefreshCcw, ArrowRight, CheckCircle2, Circle, Search,
    UserPlus, Upload, ShieldCheck, CalendarCheck, ChevronDown,
} from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { useEnums, warnaJenis } from '@/lib/helpers';

defineProps({
    statistik: Object,
});

const page = usePage();
const { enums } = useEnums();
const ikon = { nikah: HeartHandshake, talak: Scale, cerai: FileX2, rujuk: RefreshCcw };
const terbuka = ref('nikah');
const kode = ref('');

const alur = [
    { icon: UserPlus, judul: 'Buat Akun', teks: 'Daftar menggunakan NIK dan email aktif.' },
    { icon: Upload, judul: 'Isi & Unggah Berkas', teks: 'Lengkapi formulir dan unggah dokumen persyaratan.' },
    { icon: ShieldCheck, judul: 'Verifikasi Petugas', teks: 'Petugas memeriksa berkas. Bila kurang, Anda diminta memperbaiki.' },
    { icon: CalendarCheck, judul: 'Jadwal & Selesai', teks: 'Datang sesuai jadwal untuk akad, konsultasi, atau pencatatan.' },
];

function lacak() {
    if (kode.value.trim()) router.get('/lacak', { kode: kode.value.trim() });
}
</script>

<template>
    <Head title="Beranda" />

    <PublicLayout>
        <!-- Hero -->
        <section class="relative overflow-hidden bg-brand-800 pattern-islami">
            <div class="absolute -top-24 -right-24 size-96 rounded-full bg-gold-400/10 blur-3xl" />
            <div class="relative mx-auto grid max-w-6xl items-center gap-10 px-4 py-16 md:py-24 lg:grid-cols-[1.2fr_1fr]">
                <div>
                    <p class="font-arab text-2xl text-gold-400" lang="ar" dir="rtl">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</p>
                    <h1 class="mt-4 text-4xl leading-tight font-extrabold tracking-tight text-white md:text-5xl">
                        Urus administrasi nikah, talak, cerai &amp; rujuk tanpa antre.
                    </h1>
                    <p class="mt-5 max-w-xl text-lg text-brand-100/90">
                        Ajukan berkas, pantau verifikasi, dan sampaikan pengaduan ke Kantor Urusan Agama {{ page.props.kua.nama }} langsung dari rumah.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <Link :href="page.props.auth.user ? '/pengajuan/create' : '/register'" class="btn bg-gold-500 px-5 py-3 text-brand-900 hover:bg-gold-400">
                            Mulai Pengajuan <ArrowRight class="size-4" />
                        </Link>
                        <Link :href="page.props.auth.user ? '/pengaduan/create' : '/login'" class="btn border border-white/30 px-5 py-3 text-white hover:bg-white/10">
                            Sampaikan Pengaduan
                        </Link>
                    </div>
                </div>

                <!-- Lacak cepat -->
                <div class="rounded-2xl bg-white p-6 shadow-xl">
                    <h2 class="text-lg font-bold text-stone-900">Lacak status berkas</h2>
                    <p class="mt-1 text-sm text-stone-500">Masukkan kode pengajuan (mis. NKH-261010-AB12) atau kode pengaduan (ADU-…).</p>
                    <form class="mt-4 flex gap-2" @submit.prevent="lacak">
                        <input v-model="kode" type="text" class="input uppercase" placeholder="Kode berkas" aria-label="Kode berkas" />
                        <button type="submit" class="btn-primary shrink-0" aria-label="Cari"><Search class="size-4" /></button>
                    </form>
                    <dl class="mt-6 grid grid-cols-3 gap-3 border-t border-stone-100 pt-5 text-center">
                        <div>
                            <dt class="text-xs text-stone-500">Pengajuan</dt>
                            <dd class="text-2xl font-extrabold text-brand-700">{{ statistik.pengajuan }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stone-500">Selesai</dt>
                            <dd class="text-2xl font-extrabold text-brand-700">{{ statistik.selesai }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stone-500">Pengaduan</dt>
                            <dd class="text-2xl font-extrabold text-brand-700">{{ statistik.pengaduan }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        <!-- Layanan -->
        <section id="layanan" class="mx-auto max-w-6xl scroll-mt-20 px-4 py-20">
            <div class="max-w-2xl">
                <p class="text-sm font-bold tracking-wider text-gold-500 uppercase">Layanan</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-stone-900">Empat layanan, satu pintu</h2>
                <p class="mt-3 text-stone-600">Pilih layanan untuk melihat dokumen yang perlu disiapkan sebelum mengajukan.</p>
            </div>

            <div class="mt-10 grid gap-4 lg:grid-cols-2">
                <article
                    v-for="j in enums.jenis"
                    :key="j.value"
                    class="card overflow-hidden transition"
                    :class="terbuka === j.value ? 'ring-2 ring-brand-600/30' : ''"
                >
                    <button type="button" class="flex w-full items-start gap-4 p-5 text-left" :aria-expanded="terbuka === j.value" @click="terbuka = terbuka === j.value ? null : j.value">
                        <span class="grid size-12 shrink-0 place-items-center rounded-xl" :class="[warnaJenis[j.value].bg, warnaJenis[j.value].text]">
                            <component :is="ikon[j.value]" class="size-6" />
                        </span>
                        <span class="flex-1">
                            <span class="block text-lg font-bold text-stone-900">{{ j.label }}</span>
                            <span class="mt-1 block text-sm text-stone-600">{{ j.deskripsi }}</span>
                        </span>
                        <ChevronDown class="mt-1 size-5 shrink-0 text-stone-400 transition" :class="terbuka === j.value ? 'rotate-180' : ''" />
                    </button>

                    <div v-if="terbuka === j.value" class="border-t border-stone-100 bg-stone-50/60 px-5 py-4">
                        <p class="mb-3 text-xs font-bold tracking-wide text-stone-500 uppercase">Persyaratan dokumen</p>
                        <ul class="space-y-2">
                            <li v-for="s in j.persyaratan" :key="s.key" class="flex gap-2 text-sm">
                                <CheckCircle2 v-if="s.wajib" class="mt-0.5 size-4 shrink-0 text-brand-600" />
                                <Circle v-else class="mt-0.5 size-4 shrink-0 text-stone-300" />
                                <span :class="s.wajib ? 'text-stone-800' : 'text-stone-500'">{{ s.label }}</span>
                            </li>
                        </ul>
                        <div class="mt-4 flex items-center justify-between gap-3">
                            <span class="text-xs text-stone-500">● wajib &nbsp; ○ bila diperlukan</span>
                            <Link :href="`/pengajuan/create?jenis=${j.value}`" class="btn-primary py-2">Ajukan {{ j.label }}</Link>
                        </div>
                    </div>
                </article>
            </div>

            <p class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                <strong>Catatan:</strong> Putusan talak dan cerai ditetapkan oleh <strong>Pengadilan Agama</strong>. KUA melayani konsultasi,
                mediasi (BP4), dan surat pengantar yang diperlukan sebelum perkara diajukan.
            </p>
        </section>

        <!-- Alur -->
        <section id="alur" class="scroll-mt-20 border-y border-stone-200 bg-white">
            <div class="mx-auto max-w-6xl px-4 py-20">
                <p class="text-sm font-bold tracking-wider text-gold-500 uppercase">Alur</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-stone-900">Bagaimana prosesnya?</h2>
                <ol class="mt-10 grid gap-6 md:grid-cols-4">
                    <li v-for="(a, i) in alur" :key="a.judul" class="relative">
                        <div class="flex items-center gap-3">
                            <span class="grid size-11 place-items-center rounded-full bg-brand-600 text-white"><component :is="a.icon" class="size-5" /></span>
                            <span class="text-sm font-bold text-stone-400">0{{ i + 1 }}</span>
                        </div>
                        <h3 class="mt-4 font-bold text-stone-900">{{ a.judul }}</h3>
                        <p class="mt-1 text-sm text-stone-600">{{ a.teks }}</p>
                    </li>
                </ol>
            </div>
        </section>

        <!-- CTA -->
        <section class="mx-auto max-w-6xl px-4 py-20">
            <div class="flex flex-col items-start justify-between gap-6 rounded-3xl bg-brand-50 p-8 md:flex-row md:items-center md:p-12">
                <div>
                    <h2 class="text-2xl font-extrabold text-brand-900">Punya keluhan atau saran untuk pelayanan kami?</h2>
                    <p class="mt-2 text-brand-800/80">Setiap pengaduan ditindaklanjuti dan dapat dipantau statusnya.</p>
                </div>
                <Link :href="page.props.auth.user ? '/pengaduan/create' : '/login'" class="btn-primary px-6 py-3">Buat Pengaduan</Link>
            </div>
        </section>
    </PublicLayout>
</template>
