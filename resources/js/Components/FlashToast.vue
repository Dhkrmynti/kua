<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle2, AlertTriangle, X } from 'lucide-vue-next';

const page = usePage();
const pesan = ref(null);
let timer;

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success || flash?.error) {
            pesan.value = flash.success ? { tipe: 'success', teks: flash.success } : { tipe: 'error', teks: flash.error };
            clearTimeout(timer);
            timer = setTimeout(() => (pesan.value = null), 6000);
        }
    },
    { immediate: true, deep: true },
);
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        leave-active-class="transition duration-200 ease-in"
        leave-to-class="opacity-0"
    >
        <div v-if="pesan" class="fixed right-4 bottom-4 left-4 z-50 sm:left-auto sm:w-96" role="status">
            <div
                class="flex items-start gap-3 rounded-xl border bg-white p-4 shadow-lg"
                :class="pesan.tipe === 'success' ? 'border-emerald-200' : 'border-rose-200'"
            >
                <CheckCircle2 v-if="pesan.tipe === 'success'" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
                <AlertTriangle v-else class="mt-0.5 size-5 shrink-0 text-rose-600" />
                <p class="flex-1 text-sm text-stone-700">{{ pesan.teks }}</p>
                <button type="button" class="text-stone-400 hover:text-stone-600" aria-label="Tutup" @click="pesan = null">
                    <X class="size-4" />
                </button>
            </div>
        </div>
    </Transition>
</template>
