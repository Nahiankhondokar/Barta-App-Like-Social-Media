<script setup>
import { ref } from "vue";
import Footer from "./components/Layouts/Footer.vue";
import Header from "./components/Layouts/Header.vue";
</script>

<template>
    <Header></Header>
    <router-view></router-view>
    <Footer></Footer>
</template>
