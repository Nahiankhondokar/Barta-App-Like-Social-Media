<script setup>
import NoImage from "./../../../../public/assets/image/no-img/no-img.jpg";

const props = defineProps({
    ïmage: {
        type: String,
    },
    css: {
        type: String,
    },
});
</script>

<template>
    <img v-if="ïmage" :src="ïmage" alt="" :class="css" />
    <img v-else :src="NoImage" alt="no image" :class="css" />
</template>
