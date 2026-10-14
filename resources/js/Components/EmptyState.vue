<script setup>
import { Inbox } from 'lucide-vue-next';

defineProps({
    judul: { type: String, default: 'Belum ada data' },
    teks: { type: String, default: '' },
});
</script>

<template>
    <div class="flex flex-col items-center px-6 py-12 text-center">
        <span class="mb-3 grid size-12 place-items-center rounded-full bg-stone-100 text-stone-400">
            <Inbox class="size-6" />
        </span>
        <p class="font-semibold text-stone-700">{{ judul }}</p>
        <p v-if="teks" class="mt-1 max-w-sm text-sm text-stone-500">{{ teks }}</p>
        <div class="mt-4"><slot /></div>
    </div>
</template>
