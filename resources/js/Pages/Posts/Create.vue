<template>
    <AppLayout title="Create a Post">
        <Container>
            <form @submit.prevent="createPost">
                <div class="">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Create a Post
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        This information will be displayed publicly so be
                        careful what you share.
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                    >
                        <div class="sm:col-span-4">
                            <InputLabel
                                for="title"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Title</InputLabel
                            >
                            <div class="mt-2">
                                <div
                                    class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md"
                                >
                                    <TextInput
                                        type="text"
                                        v-model="form.title"
                                        id="title"
                                        autocomplete="title"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        placeholder="Write a few sentences about your title."
                                    />
                                </div>
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.title"
                                />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel
                                for="body"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Body</InputLabel
                            >
                            <div class="mt-2">
                                <TextArea
                                    id="body"
                                    v-model="form.body"
                                    rows="10"
                                    placeholder="Write a few sentences about your post."
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                ></TextArea>
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.body"
                                />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <PrimaryButton type="submit" class="mb-3"
                                >Create a Post</PrimaryButton
                            >
                        </div>
                    </div>
                </div>
            </form>
        </Container>
    </AppLayout>
</template>

<script setup>
import Container from "@/Components/Container.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import TextInput from "@/Components/TextInput.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    title: "",
    body: "",
});

const createPost = () => {
    form.post(route("posts.store"), {});
};
</script>
