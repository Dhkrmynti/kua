<script setup>
import { computed, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { HeartHandshake, Scale, FileX2, RefreshCcw, Check, UploadCloud, FileCheck2, X, ArrowLeft, ArrowRight, Send } from 'lucide-vue-next';
import AppLayout from '@/Layouts/AppLayout.vue';
import { cekUkuran, formatTanggal, formatUkuran, useEnums, warnaJenis } from '@/lib/helpers';

const props = defineProps({
    jenisAwal: String,
});

const page = usePage();
const { enums } = useEnums();
const ikon = { nikah: HeartHandshake, talak: Scale, cerai: FileX2, rujuk: RefreshCcw };

const langkah = ref(props.jenisAwal ? 1 : 0);
const daftarLangkah = ['Pilih Layanan', 'Data Pasangan', 'Dokumen', 'Periksa & Kirim'];

const form = useForm({
    jenis: props.jenisAwal ?? '',
    nama_pasangan: '',
    nik_pasangan: '',
    telepon_pasangan: '',
    alamat_pasangan: '',
    tanggal_rencana: '',
    lokasi_akad: 'kua',
    keterangan: '',
    dokumen: {},
});

const layanan = computed(() => enums.value.jenis.find((j) => j.value === form.jenis));
const minTanggal = computed(() => {
    const d = new Date();
    d.setDate(d.getDate() + (layanan.value?.minimalHari ?? 1));
    return d.toISOString().slice(0, 10);
});
const wajibBelumAda = computed(() => (layanan.value?.persyaratan ?? []).filter((s) => s.wajib && !form.dokumen[s.key]));

function pilihJenis(value) {
    if (form.jenis !== value) form.dokumen = {};
    form.jenis = value;
    langkah.value = 1;
}

function pilihFile(key, event) {
    const file = event.target.files[0];
    event.target.value = '';
    if (!file) return;
    const error = cekUkuran(file);
    if (error) return form.setError(`dokumen.${key}`, error);
    form.dokumen = { ...form.dokumen, [key]: file };
    form.clearErrors(`dokumen.${key}`);
}

function hapusFile(key) {
    const { [key]: _, ...sisa } = form.dokumen;
    form.dokumen = sisa;
}

function errorDokumen(key) {
    return form.errors[`dokumen.${key}`];
}

const fieldLangkah1 = ['nama_pasangan', 'nik_pasangan', 'telepon_pasangan', 'alamat_pasangan', 'tanggal_rencana', 'lokasi_akad', 'keterangan'];

function lanjut() {
    if (langkah.value === 1) {
        form.clearErrors(...fieldLangkah1);
        const e = {};
        if (!form.nama_pasangan) e.nama_pasangan = 'Nama pasangan wajib diisi.';
        if (!/^\d{16}$/.test(form.nik_pasangan)) e.nik_pasangan = 'NIK pasangan harus 16 digit angka.';
        if (!form.alamat_pasangan) e.alamat_pasangan = 'Alamat pasangan wajib diisi.';
        if (form.jenis === 'nikah' && !form.tanggal_rencana) e.tanggal_rencana = 'Rencana tanggal akad wajib diisi.';
        if (Object.keys(e).length) return form.setError(e);
    }
    if (langkah.value === 2 && wajibBelumAda.value.length) {
        const e = {};
        wajibBelumAda.value.forEach((s) => (e[`dokumen.${s.key}`] = `${s.label} wajib diunggah.`));
        return form.setError(e);
    }
    langkah.value++;
}

function kirim() {
    form.post('/pengajuan', {
        forceFormData: true,
        onError: (errors) => {
            const keys = Object.keys(errors);
            if (keys.includes('jenis')) langkah.value = 0;
            else if (keys.some((k) => fieldLangkah1.includes(k))) langkah.value = 1;
            else if (keys.some((k) => k.startsWith('dokumen'))) langkah.value = 2;
        },
    });
}
</script>

<template>
    <AppLayout title="Buat Pengajuan">
        <!-- Stepper -->
        <ol class="mb-6 grid grid-cols-4 gap-2">
            <li v-for="(nama, i) in daftarLangkah" :key="nama">
                <button
                    type="button"
                    class="w-full text-left disabled:cursor-default"
                    :disabled="i > langkah"
                    @click="i < langkah && (langkah = i)"
                >
                    <span class="block h-1.5 rounded-full" :class="i <= langkah ? 'bg-brand-600' : 'bg-stone-200'" />
                    <span class="mt-2 hidden text-xs font-semibold sm:block" :class="i <= langkah ? 'text-brand-700' : 'text-stone-400'">
                        {{ i + 1 }}. {{ nama }}
                    </span>
                </button>
            </li>
        </ol>

        <!-- 0. Pilih layanan -->
        <section v-if="langkah === 0">
            <h2 class="text-xl font-bold text-stone-900">Layanan apa yang Anda butuhkan?</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <button
                    v-for="j in enums.jenis"
                    :key="j.value"
                    type="button"
                    class="card flex items-start gap-4 p-5 text-left transition hover:border-brand-300 hover:shadow-md"
                    :class="form.jenis === j.value ? 'border-brand-500 ring-2 ring-brand-600/20' : ''"
                    @click="pilihJenis(j.value)"
                >
                    <span class="grid size-12 shrink-0 place-items-center rounded-xl" :class="[warnaJenis[j.value].bg, warnaJenis[j.value].text]">
                        <component :is="ikon[j.value]" class="size-6" />
                    </span>
                    <span>
                        <span class="block font-bold text-stone-900">{{ j.label }}</span>
                        <span class="mt-1 block text-sm text-stone-600">{{ j.deskripsi }}</span>
                    </span>
                </button>
            </div>
            <p v-if="form.errors.jenis" class="error-text">{{ form.errors.jenis }}</p>
        </section>

        <!-- 1. Data pasangan -->
        <section v-else-if="langkah === 1" class="card p-6">
            <h2 class="text-lg font-bold text-stone-900">Data {{ layanan.labelPasangan }}</h2>
            <p class="mt-1 text-sm text-stone-500">Data pemohon diambil dari akun Anda ({{ page.props.auth.user.name }}).</p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="nama_pasangan" class="label">Nama lengkap</label>
                    <input id="nama_pasangan" v-model="form.nama_pasangan" type="text" class="input" :class="{ 'input-error': form.errors.nama_pasangan }" />
                    <p v-if="form.errors.nama_pasangan" class="error-text">{{ form.errors.nama_pasangan }}</p>
                </div>
                <div>
                    <label for="nik_pasangan" class="label">NIK</label>
                    <input id="nik_pasangan" v-model="form.nik_pasangan" type="text" inputmode="numeric" maxlength="16" class="input" :class="{ 'input-error': form.errors.nik_pasangan }" />
                    <p v-if="form.errors.nik_pasangan" class="error-text">{{ form.errors.nik_pasangan }}</p>
                </div>
                <div>
                    <label for="telepon_pasangan" class="label">No. HP <span class="font-normal text-stone-400">(opsional)</span></label>
                    <input id="telepon_pasangan" v-model="form.telepon_pasangan" type="tel" class="input" />
                </div>
                <div>
                    <label for="tanggal_rencana" class="label">
                        {{ layanan.labelTanggal }}
                        <span v-if="form.jenis !== 'nikah'" class="font-normal text-stone-400">(opsional)</span>
                    </label>
                    <input id="tanggal_rencana" v-model="form.tanggal_rencana" type="date" :min="minTanggal" class="input" :class="{ 'input-error': form.errors.tanggal_rencana }" />
                    <p v-if="form.errors.tanggal_rencana" class="error-text">{{ form.errors.tanggal_rencana }}</p>
                    <p v-else-if="form.jenis === 'nikah'" class="mt-1 text-xs text-stone-500">Pendaftaran paling lambat 10 hari kerja sebelum akad.</p>
                </div>
                <div class="sm:col-span-2">
                    <label for="alamat_pasangan" class="label">Alamat</label>
                    <textarea id="alamat_pasangan" v-model="form.alamat_pasangan" rows="2" class="input" :class="{ 'input-error': form.errors.alamat_pasangan }" />
                    <p v-if="form.errors.alamat_pasangan" class="error-text">{{ form.errors.alamat_pasangan }}</p>
                </div>

                <fieldset v-if="form.jenis === 'nikah'" class="sm:col-span-2">
                    <legend class="label">Lokasi akad</legend>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label
                            v-for="o in [
                                { v: 'kua', t: 'Di Balai Nikah KUA', d: 'Pada jam kerja, tanpa biaya (Rp0).' },
                                { v: 'luar_kua', t: 'Di luar KUA', d: 'Rumah/gedung, dikenakan PNBP Rp600.000.' },
                            ]"
                            :key="o.v"
                            class="flex cursor-pointer gap-3 rounded-xl border p-4"
                            :class="form.lokasi_akad === o.v ? 'border-brand-500 bg-brand-50/50' : 'border-stone-200'"
                        >
                            <input v-model="form.lokasi_akad" type="radio" :value="o.v" class="mt-1 accent-brand-600" />
                            <span>
                                <span class="block text-sm font-semibold text-stone-800">{{ o.t }}</span>
                                <span class="block text-xs text-stone-500">{{ o.d }}</span>
                            </span>
                        </label>
                    </div>
                    <p v-if="form.errors.lokasi_akad" class="error-text">{{ form.errors.lokasi_akad }}</p>
                </fieldset>

                <div class="sm:col-span-2">
                    <label for="keterangan" class="label">Keterangan tambahan <span class="font-normal text-stone-400">(opsional)</span></label>
                    <textarea id="keterangan" v-model="form.keterangan" rows="3" class="input" placeholder="Mis. permohonan waktu, kondisi khusus, dsb." />
                </div>
            </div>
        </section>

        <!-- 2. Dokumen -->
        <section v-else-if="langkah === 2" class="card p-6">
            <h2 class="text-lg font-bold text-stone-900">Unggah Dokumen Persyaratan</h2>
            <p class="mt-1 text-sm text-stone-500">Format PDF/JPG/PNG, maksimal 2 MB per berkas. Pastikan hasil pindaian terbaca jelas.</p>

            <ul class="mt-6 space-y-3">
                <li
                    v-for="s in layanan.persyaratan"
                    :key="s.key"
                    class="flex flex-col gap-3 rounded-xl border p-4 sm:flex-row sm:items-center"
                    :class="errorDokumen(s.key) ? 'border-rose-300 bg-rose-50/40' : form.dokumen[s.key] ? 'border-emerald-200 bg-emerald-50/40' : 'border-stone-200'"
                >
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-stone-800">
                            {{ s.label }}
                            <span v-if="s.wajib" class="ml-1 text-rose-500">*</span>
                            <span v-else class="ml-1 text-xs font-normal text-stone-400">(opsional)</span>
                        </p>
                        <p v-if="form.dokumen[s.key]" class="mt-1 flex items-center gap-1.5 text-xs text-emerald-700">
                            <FileCheck2 class="size-3.5" /> {{ form.dokumen[s.key].name }} · {{ formatUkuran(form.dokumen[s.key].size) }}
                        </p>
                        <p v-if="errorDokumen(s.key)" class="error-text">{{ errorDokumen(s.key) }}</p>
                    </div>
                    <div class="flex gap-2">
                        <label class="btn-secondary cursor-pointer py-2">
                            <UploadCloud class="size-4" /> {{ form.dokumen[s.key] ? 'Ganti' : 'Pilih berkas' }}
                            <input type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" @change="pilihFile(s.key, $event)" />
                        </label>
                        <button v-if="form.dokumen[s.key]" type="button" class="btn-secondary px-2.5 py-2" aria-label="Hapus berkas" @click="hapusFile(s.key)">
                            <X class="size-4" />
                        </button>
                    </div>
                </li>
            </ul>
        </section>

        <!-- 3. Review -->
        <section v-else class="card p-6">
            <h2 class="text-lg font-bold text-stone-900">Periksa kembali pengajuan Anda</h2>
            <dl class="mt-5 grid gap-x-6 gap-y-4 text-sm sm:grid-cols-2">
                <div><dt class="text-stone-500">Layanan</dt><dd class="font-semibold text-stone-900">{{ layanan.label }}</dd></div>
                <div><dt class="text-stone-500">Pemohon</dt><dd class="font-semibold text-stone-900">{{ page.props.auth.user.name }}</dd></div>
                <div><dt class="text-stone-500">{{ layanan.labelPasangan }}</dt><dd class="font-semibold text-stone-900">{{ form.nama_pasangan }} ({{ form.nik_pasangan }})</dd></div>
                <div><dt class="text-stone-500">{{ layanan.labelTanggal }}</dt><dd class="font-semibold text-stone-900">{{ form.tanggal_rencana ? formatTanggal(form.tanggal_rencana) : '-' }}</dd></div>
                <div v-if="form.jenis === 'nikah'"><dt class="text-stone-500">Lokasi akad</dt><dd class="font-semibold text-stone-900">{{ form.lokasi_akad === 'kua' ? 'Balai Nikah KUA' : 'Di luar KUA' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-stone-500">Alamat pasangan</dt><dd class="text-stone-900">{{ form.alamat_pasangan }}</dd></div>
                <div class="sm:col-span-2">
                    <dt class="text-stone-500">Dokumen terlampir</dt>
                    <dd class="mt-1 flex flex-wrap gap-2">
                        <span v-for="s in layanan.persyaratan.filter((x) => form.dokumen[x.key])" :key="s.key" class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                            <Check class="size-3" /> {{ s.label }}
                        </span>
                    </dd>
                </div>
            </dl>
            <p class="mt-6 rounded-lg bg-stone-50 p-3 text-xs text-stone-600">
                Dengan mengirim, Anda menyatakan data dan dokumen yang diunggah adalah benar. Pemalsuan dokumen dapat dikenai sanksi hukum.
            </p>
            <div v-if="form.progress" class="mt-4 h-2 overflow-hidden rounded-full bg-stone-100">
                <div class="h-full bg-brand-600 transition-all" :style="{ width: `${form.progress.percentage}%` }" />
            </div>
        </section>

        <!-- Navigasi -->
        <div v-if="langkah > 0" class="mt-6 flex justify-between gap-3">
            <button type="button" class="btn-secondary" @click="langkah--"><ArrowLeft class="size-4" /> Kembali</button>
            <button v-if="langkah < 3" type="button" class="btn-primary" @click="lanjut">Lanjut <ArrowRight class="size-4" /></button>
            <button v-else type="button" class="btn-primary" :disabled="form.processing" @click="kirim">
                <Send class="size-4" /> {{ form.processing ? 'Mengirim…' : 'Kirim Pengajuan' }}
            </button>
        </div>
    </AppLayout>
</template>
