<script setup>
import { ref } from "vue";
import router from "@/router";
import { useToast } from "vue-toast-notification";
import { authenticationCheck } from "../../middleware/authentication";

const $toast = useToast();
let form = ref({
    email: "",
    password: "",
});
let errors = ref({});

const handleUserLogin = async () => {
    const formData = new FormData();
    formData.append("email", form.value.email);
    formData.append("password", form.value.password);

    await axios
        .post("/api/login", formData)
        .then(function (response) {
            localStorage.setItem("loggedIn", "true");
            router.push({ name: "Dashboard" });
            authenticationCheck();
            $toast.success(response.data.message);
        })
        .catch(function (error) {
            $toast.error("Login faild !");
            errors.value = error.response.data.errors;
        });
};
</script>

<template>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <a
                href="./index.html"
                class="text-center text-6xl font-bold text-gray-900"
                ><h1>Barta</h1></a
            >

            <h1
                class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
            >
                Sign in to your account
            </h1>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" @submit.prevent="handleUserLogin">
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
                            placeholder="bruce@wayne.com"
                            required
                            class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6"
                        />
                    </div>
                    <span class="text-red-700" v-if="errors?.email">
                        {{ errors?.email[0] }}
                    </span>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label
                            for="password"
                            class="block text-sm font-medium leading-6 text-gray-900"
                            >Password</label
                        >
                        <div class="text-sm">
                            <a
                                href="#"
                                class="font-semibold text-black hover:text-black"
                                >Forgot password?</a
                            >
                        </div>
                    </div>
                    <div class="mt-2">
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            required
                            class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6"
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
                        Sign in
                    </button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                Don't have an account yet?
                <router-link
                    :to="{name: 'Register'}"
                    class="font-semibold leading-6 text-black hover:text-black"
                    >Sign Up</router-link
                >
            </p>
        </div>
    </div>
</template>
