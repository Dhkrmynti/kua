<script setup>
import StatusBadge from '@/Components/StatusBadge.vue';
import { formatTanggal } from '@/lib/helpers';

defineProps({
    items: { type: Array, required: true }, // [{ status, catatan?, created_at|waktu, user? }]
});
</script>

<template>
    <ol class="relative space-y-6 border-l-2 border-stone-200 pl-6">
        <li v-for="(item, i) in items" :key="i" class="relative">
            <span
                class="absolute top-1 -left-[31px] size-4 rounded-full border-2 border-white ring-2"
                :class="i === 0 ? 'bg-brand-600 ring-brand-200' : 'bg-stone-300 ring-stone-100'"
            />
            <div class="flex flex-wrap items-center gap-2">
                <StatusBadge :status="item.status" />
                <time class="text-xs text-stone-500">{{ formatTanggal(item.created_at ?? item.waktu, true) }}</time>
            </div>
            <p v-if="item.catatan" class="mt-1.5 text-sm text-stone-600">{{ item.catatan }}</p>
            <p v-if="item.user" class="mt-1 text-xs text-stone-400">oleh {{ item.user.name }}</p>
        </li>
    </ol>
</template>
