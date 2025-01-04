<script setup>
import { onMounted, provide, reactive, ref, watch } from "vue";
import Footer from "./components/Layouts/Footer.vue";
import Header from "./components/Layouts/Header.vue";
import { authenticationCheck, authUser } from "./middleware/authentication";
import { useRoute } from "vue-router";

let posts = ref([]);
provide("authUser", authUser);
provide("posts", posts);
const route = useRoute();

onMounted(() => {
    authenticationCheck();
});
</script>

<template>
    <Header
        v-if="route.path !== '/login' && route.path !== '/register'"
    ></Header>

    <router-view></router-view>

    <Footer
        v-if="route.path !== '/login' && route.path !== '/register'"
    ></Footer>
</template>
