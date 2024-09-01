<template>
    <div class="sm:flex">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
            <img
                :src="comment.user.profile_photo_url"
                class="h-10 w-10 rounded-full"
            />
        </div>
        <div class="flex-1">
            <div
                class="prose prose-sm mt-1 max-w-none"
                v-html="comment.html"
            ></div>

            <span
                class="block pt-1 text-xs text-gray-600 first-letter:uppercase"
                >By {{ comment.user.name }}
                {{ relativeDate(comment.created_at) }} |
                <span class="text-pink-500">
                    {{ comment.likes_count }} likes</span
                ></span
            >
            <div class="mt-2 flex justify-end space-x-3 empty:hidden">
                <div v-if="$page.props.auth.user">
                    <Link
                        v-if="comment.can.like"
                        method="Post"
                        :href="route('likes.store', ['comment', comment.id])"
                        preserve-scroll
                        class="inline-block text-gray-700 transition-colors duration-300 hover:text-pink-500"
                    >
                        <HandThumbUpIcon class="mr-1 inline-block size-5" />
                        <span class="sr-only"> Like Comment</span>
                    </Link>
                    <Link
                        v-else
                        method="Delete"
                        :href="route('likes.destroy', ['comment', comment.id])"
                        preserve-scroll
                        class="inline-block text-gray-700 transition-colors duration-300 hover:text-pink-500"
                    >
                        <HandThumbDownIcon class="mr-1 inline-block size-5" />
                        <span class="sr-only"> Unlike Comment</span>
                    </Link>
                </div>
                <form
                    v-if="comment.can?.update"
                    @submit.prevent="$emit('edit', comment.id)"
                >
                    <button class="font-mono text-xs hover:font-semibold">
                        Edit
                    </button>
                </form>
                <form
                    v-if="comment.can?.delete"
                    @submit.prevent="$emit('delete', comment.id)"
                >
                    <button
                        class="font-mono text-xs text-red-700 hover:font-semibold"
                    >
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { relativeDate } from "@/Utilities/date.js";
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { HandThumbUpIcon, HandThumbDownIcon } from "@heroicons/vue/20/solid";
const props = defineProps(["comment"]);

const emit = defineEmits(["delete", "edit"]);
</script>
