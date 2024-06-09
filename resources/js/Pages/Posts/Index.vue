<template>
    <AppLayout title="Posts">
        <section class="bg-white">
            <Container>
                <div class="mx-auto max-w-2xl lg:mx-0">
                    <Link
                        v-if="selectedTopic"
                        class="underline"
                        :href="route('posts.index')"
                        >Back to all post</Link
                    >
                    <!-- <PageHeading
                        v-text="
                            selectedTopic ? selectedTopic.name : 'All Posts'
                        "
                    /> -->
                    <p
                        v-if="selectedTopic"
                        class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
                    >
                        {{ selectedTopic.description }}
                    </p>
                    <h2
                        class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
                    >
                        From the blog
                    </h2>
                    <p class="mt-2 text-lg leading-8 text-gray-600">
                        Learn how to grow your business with our expert advice.
                    </p>
                    <menu class="flex space-x-2 overflow-x-auto pb-3 pt-1">
                        <li>
                            <Pill
                                :filled="!selectedTopic"
                                :href="route('posts.index')"
                                >All Posts</Pill
                            >
                        </li>
                        <li v-for="topic in topics" :key="topic.id">
                            <Pill
                                :filled="selectedTopic?.id === topic.id"
                                :href="
                                    route('posts.index', {
                                        topic: topic.slug,
                                    })
                                "
                            >
                                {{ topic.name }}
                            </Pill>
                        </li>
                    </menu>
                </div>
                <div
                    class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3"
                >
                    <article
                        v-for="post in posts.data"
                        :key="post.id"
                        class="flex max-w-xl flex-col items-start justify-between rounded-lg p-4 transition-colors duration-300 hover:bg-gray-100"
                    >
                        <div class="flex items-center gap-x-4 text-xs">
                            <time
                                :datetime="post.datetime"
                                class="text-gray-500"
                                >{{ formattedDate(post) }} ago</time
                            >
                        </div>
                        <div class="group relative">
                            <h3
                                class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600"
                            >
                                <Link :href="post.routes.show">
                                    <span class="absolute inset-0" />
                                    {{ post.title }}
                                </Link>
                            </h3>
                            <p
                                class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600"
                            >
                                {{ post.body }}
                            </p>
                        </div>
                        <Pill
                            :href="
                                route('posts.index', { topic: post.topic.slug })
                            "
                            class="mt-5 line-clamp-3 rounded-full border border-pink-500 px-2 text-sm leading-6 text-pink-500"
                        >
                            {{ post.topic.slug }}
                        </Pill>
                        <div class="relative mt-8 flex items-center gap-x-4">
                            <img
                                :src="
                                    post.user.profile_photo_url ??
                                    'https://ui-avatars.com/api/?backgroud=0D8ABC&color=fff&name='
                                        .post.user.name
                                "
                                alt=""
                                class="h-10 w-10 rounded-full bg-gray-50"
                            />
                            <div class="text-sm leading-6">
                                <p class="font-semibold text-gray-900">
                                    <Link :href="post.routes.show">
                                        <span class="absolute inset-0" />
                                        {{ post.user.name }}
                                    </Link>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
                <Pagination class="mt-20" :meta="posts.meta" />
            </Container>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pill from "@/Components/Pill.vue";
import PageHeading from "@/Components/PageHeading.vue";
import Pagination from "@/Components/Pagination.vue";
import { relativeDate } from "@/Utilities/date.js";
import { Link } from "@inertiajs/vue3";
defineProps({
    posts: Object,
    topics: Object,
    selectedTopic: Object,
});

const formattedDate = (post) => relativeDate(post.created_at);
</script>
