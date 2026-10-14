<script setup>
import { computed } from 'vue';
import { useEnums, warnaStatus } from '@/lib/helpers';

const props = defineProps({
    status: { type: String, required: true },
    tipe: { type: String, default: 'pengajuan' }, // pengajuan | pengaduan
});

const { label } = useEnums();
const teks = computed(() => label(props.tipe === 'pengaduan' ? 'statusPengaduan' : 'statusPengajuan', props.status));
const warna = computed(() => warnaStatus[props.tipe]?.[props.status] ?? 'bg-stone-100 text-stone-700 ring-stone-500/20');
</script>

<template>
    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold whitespace-nowrap ring-1 ring-inset" :class="warna">
        <span class="size-1.5 rounded-full bg-current opacity-70" />
        {{ teks }}
    </span>
</template>
