<script setup>
import NoImage from "./../../../../public/assets/image/no-img/no-img.jpg";

const props = defineProps({
    image: {
        type: String,
    },
    css: {
        type: String,
    },
});

const baseUrl = import.meta.env.VITE_API_URL;
</script>

<template>
    <img v-if="image" :src="baseUrl + image" :class="css" alt="image" />
    <img v-else :src="NoImage" alt="no image" :class="css" />
</template>
