<template>
    <AppLayout title="Posts">
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id">
                    <Link
                        class="group block px-2 py-4"
                        :href="route('posts.show', post.id)"
                    >
                        <span
                            class="text-lg font-bold transition-colors duration-200 group-hover:text-indigo-500"
                        >
                            {{ post.title }}</span
                        >

                        <span class="block pt-1 text-sm text-gray-600"
                            >{{ formattedDate(post) }} ago by
                            {{ post.user.name }}</span
                        >
                    </Link>
                </li>
            </ul>
            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import { relativeDate } from "@/Utilities/date.js";
defineProps({
    posts: Object,
});

const formattedDate = (post) => relativeDate(post.created_at);
</script>
