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
            authUser.value = response.data;
        })
        .catch((error) => {
            unAuthenticateUser(error.status);
        });
});
console.log(authUser.value);
console.log("root call");
</script>

<template>
    <Header :authUser="authUser"></Header>
    <router-view :authUser="authUser"></router-view>
    <Footer></Footer>
</template>
