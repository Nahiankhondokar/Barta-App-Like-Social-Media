<script setup>
import { reactive, ref } from "vue";
import { useToast } from "vue-toast-notification";
import router from "@/router";

const $toast = useToast();
let form = ref({
    name: "",
    username: "",
    email: "",
    password: "",
});
let errors = ref({});

const handleUserRegister = async () => {
    const formData = new FormData();
    formData.append("name", form.value.name);
    formData.append("username", form.value.username);
    formData.append("email", form.value.email);
    formData.append("password", form.value.password);

    await axios
        .post("/api/register", formData)
        .then(function (response) {
            $toast.success(response.data.message);
            router.push({ name: "Login" });
        })
        .catch(function (error) {
            errors.value = error.response.data.errors;
        });
};
</script>

<template>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <a href="#" class="text-center text-6xl font-bold text-gray-900"
                ><h1>Barta</h1></a
            >
            <h1
                class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
            >
                Create a new account
            </h1>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form @submit.prevent="handleUserRegister" class="space-y-6">
                <!-- Name -->
                <div>
                    <label
                        for="name"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Name</label
                    >
                    <div class="mt-2">
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            autocomplete="name"
                            placeholder="Alp Arslan"
                            required
                            class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6"
                        />
                    </div>
                    <span class="text-red-700" v-if="errors?.name">
                        {{ errors?.name[0] }}
                    </span>
                </div>

                <!-- Username -->
                <div>
                    <label
                        for="username"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Username</label
                    >
                    <div class="mt-2">
                        <input
                            id="username"
                            v-model="form.username"
                            type="text"
                            autocomplete="username"
                            placeholder="alparslan1029"
                            required
                            class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6"
                        />
                    </div>
                    <span class="text-red-700" v-if="errors?.username">
                        {{ errors?.username[0] }}
                    </span>
                </div>

                <!-- Email -->
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Email address</label
                    >
                    <div class="mt-2">
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            autocomplete="email"
                            placeholder="alp.arslan@mail.com"
                            required
                            class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6"
                        />
                    </div>
                    <span class="text-red-700" v-if="errors?.email">
                        {{ errors?.email[0] }}
                    </span>
                </div>

                <!-- Password -->
                <div>
                    <label
                        for="password"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Password</label
                    >
                    <div class="mt-2">
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            required
                            class="block w-full rounded-md border-0 p-2 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6"
                        />
                    </div>
                    <span class="text-red-700" v-if="errors?.password">
                        {{ errors?.password[0] }}
                    </span>
                </div>

                <div>
                    <button
                        type="submit"
                        class="flex w-full justify-center rounded-md bg-black px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black"
                    >
                        Register
                    </button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                Already a member?
                <router-link
                    to="/login"
                    class="font-semibold leading-6 text-black hover:text-black"
                    >Sign In</router-link
                >
            </p>
        </div>
    </div>
</template>
