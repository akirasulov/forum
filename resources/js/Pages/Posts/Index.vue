<template>
    <AppLayout title="Posts">
        <section class="bg-white">
            <Container>
                <div class="mx-auto max-w-2xl lg:mx-0">
                    <h2
                        class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
                    >
                        From the blog
                    </h2>
                    <p class="mt-2 text-lg leading-8 text-gray-600">
                        Learn how to grow your business with our expert advice.
                    </p>
                </div>
                <div
                    class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3"
                >
                    <article
                        v-for="post in posts.data"
                        :key="post.id"
                        class="flex max-w-xl flex-col items-start justify-between rounded-md outline-offset-8 outline-gray-200 hover:outline"
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
import Pagination from "@/Components/Pagination.vue";
import { relativeDate } from "@/Utilities/date.js";
defineProps({
    posts: Object,
});

const formattedDate = (post) => relativeDate(post.created_at);
</script>
