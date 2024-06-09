<template>
    <Link
        :href="href"
        class="mt-5 line-clamp-3 inline-block whitespace-nowrap rounded-full border border-pink-500 px-2 text-sm leading-6 text-pink-500 hover:border-indigo-600 hover:bg-indigo-500 hover:text-indigo-50"
        :class="{ 'border-indigo-600 bg-indigo-500 !text-indigo-50': filled }"
    >
        <slot></slot>
    </Link>
</template>

<script setup>
defineProps({
    href: {
        type: String,
        required: true,
    },
    filled: {
        default: false,
    },
});
</script>
