<script setup>
import { onMounted, ref } from "vue";
import { useToast } from "vue-toast-notification";
import { useRoute } from "vue-router";
import NoImage from "./../../../../public/assets/image/no-img/no-img.jpg";
import { unAuthenticateUser } from "../../middleware/authentication";
import router from "@/router";

const $toast = useToast();
let editProfile = ref({});
let imageUrl = ref(null);
const baseUrl = import.meta.env.VITE_API_URL;

const route = useRoute();
const id = route.params.id;

function handleImageUpload(e) {
    editProfile.value.image = "";
    let file = event.target.files[0];
    imageUrl.value = URL.createObjectURL(file);
    URL.revokeObjectURL(file); // security perpouse

    if (file) {
        editProfile.value.newImage = file;
    }
}

const handleProfileUpdate = async () => {
    const formData = new FormData();
    formData.append("name", editProfile.value.name);
    formData.append("username", editProfile.value.username);
    formData.append("email", editProfile.value.email);
    formData.append("bio", editProfile.value.bio);
    formData.append("password", editProfile.value.password);

    if (editProfile.value.newImage) {
        formData.append("newImage", editProfile.value.newImage);
    }

    await axios
        .post(`/api/profile/update/${id}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then(function (response) {
            getUserProfile();
            imageUrl.value = null;
            $toast.success(response.data.message);
            router.push({ name: "Dashboard" });
        })
        .catch(function (error) {
            console.log(error.response.data.message);
        });
};

const getUserProfile = () => {
    axios
        .get(`/api/profile/edit/${id}`)
        .then(function (response) {
            editProfile.value = response.data.data;
            editProfile.value.password = "";
            editProfile.value.newImage = "";
        })
        .catch(function (error) {
            unAuthenticateUser(error.status);
            $toast.warning("Please login again");
        });
};

onMounted(() => {
    getUserProfile();
});
</script>

<template>
    <main
        class="container max-w-xl mx-auto space-y-8 mt-8 px-2 md:px-0 min-h-screen"
    >
        <!-- Profile Edit Form -->

        <form @submit.prevent="handleProfileUpdate">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-xl font-semibold leading-7 text-gray-900">
                        Edit Profile
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        This information will be displayed publicly so be
                        careful what you share.
                    </p>

                    <div class="mt-10 border-b border-gray-900/10 pb-12">
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
                                    id="avatar"
                                    @change="handleImageUpload()"
                                />
                                <!-- Uncomment this image tag if required -->
                                <img
                                    :src="
                                        baseUrl + editProfile.image ?? NoImage
                                    "
                                    class="h-10 w-10 rounded-full object-cover"
                                    alt=""
                                />
                                <!-- preview image -->
                                <img
                                    :src="imageUrl ?? NoImage"
                                    class="h-10 w-10 rounded-full object-cover"
                                    alt=""
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
                            class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                        >
                            <div class="sm:col-span-3">
                                <label
                                    for="first-name"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Name</label
                                >
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        id="first-name"
                                        autocomplete="given-name"
                                        v-model="editProfile.name"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label
                                    for="last-name"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Username</label
                                >
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        id="last-name"
                                        v-model="editProfile.username"
                                        autocomplete="family-name"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div class="col-span-full">
                                <label
                                    for="email"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Email address</label
                                >
                                <div class="mt-2">
                                    <input
                                        id="email"
                                        v-model="editProfile.email"
                                        type="email"
                                        autocomplete="email"
                                        value=""
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div class="col-span-full">
                                <label
                                    for="password"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Password</label
                                >
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        id="password"
                                        v-model="editProfile.password"
                                        autocomplete="password"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                    >
                        <div class="col-span-full">
                            <label
                                for="bio"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Bio</label
                            >
                            <div class="mt-2">
                                <textarea
                                    id="bio"
                                    v-model="editProfile.bio"
                                    rows="3"
                                    class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6"
                                >
                                    bio</textarea
                                >
                            </div>
                            <p class="mt-3 text-sm leading-6 text-gray-600">
                                Write a few sentences about yourself.
                            </p>
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
