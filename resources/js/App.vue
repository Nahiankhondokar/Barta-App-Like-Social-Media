<script setup>
import { onMounted, provide, ref, watch } from "vue";
import Footer from "./components/Layouts/Footer.vue";
import Header from "./components/Layouts/Header.vue";
import { authenticationCheck, authUser } from "./middleware/authentication";

provide("authUser", authUser);

onMounted(() => {
    authenticationCheck();
});
</script>

<template>
    <Header></Header>
    <div class="mt-10"></div>
    <router-view :authUser="authUser"></router-view>
    <div class="mt-10"></div>
    <Footer></Footer>
</template>
