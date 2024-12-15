<script setup>
import { onMounted, ref, watch } from "vue";
import Footer from "./components/Layouts/Footer.vue";
import Header from "./components/Layouts/Header.vue";
import {
    authenticationCheck,
    unAuthenticateUser,
} from "./middleware/authentication";

const authUser = ref({});
// console.log('before- 1'+authUser.value.name)
const handleAuthCheck = async () => {
    try {
        const response = await authenticationCheck();
        // authUser.value = {};
        // console.log('before 2- '+authUser.value.name)
        authUser.value = response.data;
        // console.log('after- 1'+authUser.value.name)
    } catch (error) {
        unAuthenticateUser(error.status);
    }    
}
// console.log('after 2- '+authUser.value.name)
onMounted(() => {
    handleAuthCheck();
});
</script>

<template>
    <Header></Header>
    <div class="mt-10"></div>
    <router-view :authUser="authUser"></router-view>
    <div class="mt-10"></div>
    <Footer></Footer>
</template>
