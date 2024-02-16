<template>
    <header class="p-5">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-16">
            <h1 class="text-xl font-medium tracking-tight text-gray-900">{{ header }}</h1>
        </div>
    </header>
    <main>
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="px-4 sm:px-0">
                <slot></slot>
            </div>
        </div>
    </main>
</template>

<script setup>

const props = defineProps({
    header: String,
});
</script>
<style></style>