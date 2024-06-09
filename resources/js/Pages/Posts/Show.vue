<template>
    <AppLayout :title="post.title">
        <Container>
            <PageHeading> {{ post.title }}</PageHeading>
            <p class="text-base font-semibold leading-7 text-indigo-600">
                {{ formattedDate }} ago by {{ post.user.name }}
            </p>
            <h1
                class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
            >
                {{ post.title }}
            </h1>

            <article
                class="prose prose-sm mt-6 max-w-none"
                v-html="post.html"
            ></article>

            <div class="mt-12">
                <h2 class="text-xl font-semibold">Comments</h2>

                <form
                    v-if="$page.props.auth.user"
                    @submit.prevent="
                        () =>
                            commentIdBeingEdited
                                ? updateComment()
                                : addComment()
                    "
                    class="mt-4"
                >
                    <div>
                        <InputLabel for="body" class="sr-only"
                            >Comment</InputLabel
                        >
                        <MarkdownEditor
                            ref="commentTextAreaRef"
                            id="body"
                            v-model="commentForm.body"
                            @keydown.enter.exact.prevent="addComment"
                            editorClass="min-h-[160px]"
                            placeholder="Speack your mind Boddy"
                        />
                        <InputError
                            :message="commentForm.errors.body"
                            class="mt-1"
                        />
                    </div>
                    <PrimaryButton
                        type="submit"
                        class="mt-3"
                        :disabled="commentForm.processing"
                        v-text="
                            commentIdBeingEdited
                                ? 'Update Comment'
                                : 'Add Comment'
                        "
                    ></PrimaryButton>
                    <SecondaryButton
                        v-if="commentIdBeingEdited"
                        @click="cancelEditComment"
                        class="ml-2"
                        >Cancel</SecondaryButton
                    >
                </form>
                <ul class="mt-4 divide-y">
                    <li
                        v-for="comment in comments.data"
                        :key="comment.id"
                        class="px-2 py-4"
                    >
                        <Comment
                            @edit="editComment"
                            @delete="deleteComment"
                            :comment="comment"
                        />
                    </li>
                </ul>
                <Pagination :meta="comments.meta" :only="['comments']" />
            </div>
        </Container>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Comment from "@/Components/Comment.vue";
import Pagination from "@/Components/Pagination.vue";
import { computed, ref } from "vue";
import { relativeDate } from "@/Utilities/date.js";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { router, useForm } from "@inertiajs/vue3";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useConfirm } from "@/Utilities/Composables/useConfirm.js";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";

const props = defineProps(["post", "comments"]);

const formattedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
    body: "",
});

const commentTextAreaRef = ref(null);
let commentIdBeingEdited = ref(null);

const commentBeingEdit = computed(() =>
    props.comments.data.find(
        (comment) => comment.id == commentIdBeingEdited.value,
    ),
);

const editComment = (commentId) => {
    commentIdBeingEdited.value = commentId;
    commentForm.body = commentBeingEdit.value?.body;
    commentTextAreaRef.value?.focus();
};

const cancelEditComment = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset();
};

const addComment = async () => {
    commentForm.post(route("posts.comments.store", props.post.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });
};

const { confirmation } = useConfirm();

const updateComment = async () => {
    if (
        !(await confirmation("Are you sure you want to update this comment?"))
    ) {
        commentTextAreaRef.value?.focus();
        return;
    }
    commentForm.put(
        route("comments.update", {
            comment: commentIdBeingEdited.value,
            page: props.comments.meta.current_page,
        }),
        {
            preserveScroll: true,
            onSuccess: cancelEditComment,
        },
    );
};

const deleteComment = async (commentId) => {
    if (
        !(await confirmation("Are you sure you want to delete this comment?"))
    ) {
        return;
    }
    router.delete(
        route("comments.destroy", {
            comment: commentId,
            page: props.comments.meta.current_page,
        }),
        {
            preserveScroll: true,
        },
    );
};
</script>
