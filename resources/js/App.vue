<script setup>
import { onMounted, ref, watch } from "vue";
import Footer from "./components/Layouts/Footer.vue";
import Header from "./components/Layouts/Header.vue";
import {
    authenticationCheck,
    unAuthenticateUser,
} from "./Service/authentication";

const authUser = ref({});
onMounted(() => {
    authenticationCheck()
        .then((response) => {
            authUser.value = null;
            authUser.value = response.data;
        })
        .catch((error) => {
            unAuthenticateUser(error.status);
        });
});
</script>

<template>
    <Header :authUser="authUser"></Header>
    <div class="mt-10"></div>
    <router-view :authUser="authUser"></router-view>
    <div class="mt-10"></div>
    <Footer></Footer>
</template>
