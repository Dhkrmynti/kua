import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const TZ = 'Asia/Jakarta';

export function formatTanggal(value, withTime = false) {
    if (!value) return '-';
    const opts = { day: 'numeric', month: 'long', year: 'numeric', timeZone: TZ };
    if (withTime) Object.assign(opts, { hour: '2-digit', minute: '2-digit' });
    return new Intl.DateTimeFormat('id-ID', opts).format(new Date(value));
}

export function formatUkuran(bytes) {
    if (!bytes) return '-';
    return bytes > 1024 * 1024 ? `${(bytes / 1024 / 1024).toFixed(1)} MB` : `${Math.round(bytes / 1024)} KB`;
}

export const MAKS_BERKAS = 2 * 1024 * 1024; // sama dengan aturan max:2048 di backend

/** Pesan error bila berkas melebihi batas, atau null bila aman. */
export function cekUkuran(file) {
    return file && file.size > MAKS_BERKAS ? `Ukuran berkas ${formatUkuran(file.size)}, maksimal 2 MB. Kompres terlebih dahulu.` : null;
}

/** Ubah ISO (UTC) menjadi nilai untuk <input type="datetime-local"> di zona WIB. */
export function toDatetimeLocal(value) {
    if (!value) return '';
    const parts = new Intl.DateTimeFormat('sv-SE', {
        timeZone: TZ, year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit',
    }).format(new Date(value));
    return parts.replace(' ', 'T');
}

/** Akses enum yang dibagikan dari backend (HandleInertiaRequests). */
export function useEnums() {
    const page = usePage();
    const enums = computed(() => page.props.enums);

    const find = (group, value) => enums.value?.[group]?.find((o) => o.value === value);
    const label = (group, value) => find(group, value)?.label ?? value;

    return { enums, find, label };
}

export const warnaStatus = {
    pengajuan: {
        diajukan: 'bg-sky-50 text-sky-700 ring-sky-600/20',
        diverifikasi: 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
        perlu_perbaikan: 'bg-amber-50 text-amber-800 ring-amber-600/30',
        dijadwalkan: 'bg-violet-50 text-violet-700 ring-violet-600/20',
        selesai: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        ditolak: 'bg-rose-50 text-rose-700 ring-rose-600/20',
    },
    pengaduan: {
        baru: 'bg-sky-50 text-sky-700 ring-sky-600/20',
        diproses: 'bg-amber-50 text-amber-800 ring-amber-600/30',
        selesai: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        ditolak: 'bg-rose-50 text-rose-700 ring-rose-600/20',
    },
};

export const warnaJenis = {
    nikah: { bg: 'bg-emerald-50', text: 'text-emerald-700', bar: 'bg-emerald-500' },
    talak: { bg: 'bg-amber-50', text: 'text-amber-700', bar: 'bg-amber-500' },
    cerai: { bg: 'bg-rose-50', text: 'text-rose-700', bar: 'bg-rose-500' },
    rujuk: { bg: 'bg-sky-50', text: 'text-sky-700', bar: 'bg-sky-500' },
};
