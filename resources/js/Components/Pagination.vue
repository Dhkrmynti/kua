<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    meta: { type: Object, required: true }, // objek paginator Laravel
});
</script>

<template>
    <div v-if="meta.last_page > 1" class="flex flex-col items-center justify-between gap-3 border-t border-stone-200 px-4 py-3 text-sm sm:flex-row">
        <p class="text-stone-500">
            Menampilkan {{ meta.from }}–{{ meta.to }} dari {{ meta.total }} data
        </p>
        <nav class="flex flex-wrap gap-1">
            <template v-for="(link, i) in meta.links" :key="i">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    preserve-scroll
                    class="rounded-md px-3 py-1.5 font-medium transition"
                    :class="link.active ? 'bg-brand-600 text-white' : 'text-stone-600 hover:bg-stone-100'"
                    v-html="link.label"
                />
                <span v-else class="rounded-md px-3 py-1.5 text-stone-300" v-html="link.label" />
            </template>
        </nav>
    </div>
</template>
