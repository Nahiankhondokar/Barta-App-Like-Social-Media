<script setup>
import { onMounted, provide, reactive, ref, watch } from "vue";
import Footer from "./components/Layouts/Footer.vue";
import Header from "./components/Layouts/Header.vue";
import { authenticationCheck, authUser } from "./middleware/authentication";

let posts = ref([]);
provide("authUser", authUser);
provide("posts", posts);

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
