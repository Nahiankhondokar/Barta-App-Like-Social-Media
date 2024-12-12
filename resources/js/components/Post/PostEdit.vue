<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import NoImage from "./../../../../public/assets/image/no-img/no-img.jpg";
import { useToast } from "vue-toast-notification";
import router from "@/router";

const $toast = useToast();
const editPost = ref({});
let imageUrl = ref(null);
const baseUrl = "http://127.0.0.1:8000/";
const { params } = useRoute();
const id = params.id;

function handleImageUpload(e) {
    editPost.value.image = "";
    let file = event.target.files[0];
    imageUrl.value = URL.createObjectURL(file);
    URL.revokeObjectURL(file); // security perpouse

    if (file) {
        editPost.value.image = file;
    }
}

const handlePostUpdate = async () => {
    const formData = new FormData();
    formData.append("_method", "PUT");
    formData.append("barta", editPost.value.barta);

    if (editPost.value.image) {
        formData.append("image", editPost.value.image);
    }

    await axios
        .post(`/api/post/${id}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then(function (response) {
            imageUrl.value = null;
            $toast.success(response.data.message);
            router.push({ name: "Dashboard" });
        })
        .catch(function (error) {
            console.log(error);
        });
};

const getSinglePost = (id) => {
    axios
        .get(`/api/post/${id}/edit`)
        .then(function (response) {
            editPost.value = response.data.data;
        })
        .catch(function (error) {
            console.log(error);
        });
};

onMounted(() => {
    getSinglePost(id);
});
</script>

<template>
    <main
        class="container max-w-xl mx-auto space-y-8 mt-8 px-2 md:px-0 min-h-screen"
    >
        <!-- Profile Edit Form -->

        <form @submit.prevent="handlePostUpdate">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-xl font-semibold leading-7 text-gray-900">
                        Edit Post
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        This information will be displayed publicly so be
                        careful what you share.
                    </p>

                    <div class="col-span-full mt-10 pb-10">
                        <label
                            for="photo"
                            class="block text-sm font-medium leading-6 text-gray-900"
                            >Photo</label
                        >
                        <div class="mt-2 flex items-center gap-x-3">
                            <input
                                class="hidden"
                                type="file"
                                name="image"
                                id="avatar"
                                @change="handleImageUpload"
                            />
                            <!-- Uncomment this image tag if required -->
                            <img
                                :src="baseUrl + editPost.image ?? NoImage"
                                class="h-10 w-10 rounded-full object-cover"
                                alt="main"
                            />
                            <!-- preview image -->
                            <img
                                :src="imageUrl ?? NoImage"
                                class="h-10 w-10 rounded-full object-cover"
                                alt="preview"
                            />
                            <label for="avatar">
                                <div
                                    class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                                >
                                    Change
                                </div>
                            </label>
                        </div>
                    </div>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                    >
                        <div class="col-span-full">
                            <label
                                for="bio"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Barta</label
                            >
                            <div class="mt-2">
                                <textarea
                                    id="bio"
                                    v-model="editPost.barta"
                                    rows="3"
                                    class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6"
                                >
                                {{ editPost.barta }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button
                    type="button"
                    class="text-sm font-semibold leading-6 text-gray-900"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                >
                    Save
                </button>
            </div>
        </form>
        <!-- /Profile Edit Form -->
    </main>
</template>
