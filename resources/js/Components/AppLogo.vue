<script setup>
import { usePage } from '@inertiajs/vue3';

defineProps({
    light: { type: Boolean, default: false },
});

const page = usePage();
</script>

<template>
    <span class="flex items-center gap-2.5">
        <img src="/logo-kemenag.png" alt="Logo Kementerian Agama" class="size-9 shrink-0 object-contain" />
        <span class="leading-tight">
            <span class="block text-sm font-extrabold tracking-tight" :class="light ? 'text-white' : 'text-stone-900'">Kantor Urusan Agama</span>
            <span class="block text-[11px] font-medium" :class="light ? 'text-brand-100' : 'text-stone-500'">{{ page.props.kua?.nama }}</span>
        </span>
    </span>
</template>
