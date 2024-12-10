<script setup>
import { onMounted, provide, ref } from "vue";
import Footer from "./components/Layouts/Footer.vue";
import Header from "./components/Layouts/Header.vue";
import { unAuthenticateUser } from "./Service/unAthenticateUser";

let authUser = ref({
    user: {},
});

console.log(authUser.value.user);

onMounted(() => {
    console.log("mounted");
    axios
        .get("api/me")
        .then(function (response) {
            authUser.value.user = response.data.data;
        })
        .catch(function (error) {
            unAuthenticateUser(error.status);
        });
});

provide("user", authUser.value.user);
</script>

<template>
    <Header></Header>
    <router-view></router-view>
    <Footer></Footer>
</template>
