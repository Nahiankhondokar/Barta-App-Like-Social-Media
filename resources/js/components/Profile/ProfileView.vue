<script setup>
// import { onMounted, ref } from "vue";
// import { toast } from "vue3-toastify";
// import ImageShow from "../ImageShow/ImageShow.vue";

// const authUser = ref({});
// let form = ref({
//     barta: "",
//     image: null,
// });

// function handleImageUpload(e) {
//     let file = event.target.files[0];
//     if (file) {
//         form.value.image = file;
//     }
// }

// const handlePostSubmit = async () => {
//     const formData = new FormData();
//     formData.append("barta", form.value.barta);
//     if (form.value.image) {
//         formData.append("image", form.value.image);
//     }

//     await axios
//         .post("post", formData, {
//             headers: {
//                 "Content-Type": "multipart/form-data",
//             },
//         })
//         .then(function (response) {
//             console.log("created");
//             toast("Post Created Successfully !", {
//                 autoClose: 1000,
//             });
//         })
//         .catch(function (error) {
//             console.log(error);
//         });
// };

// onMounted(() => {
//     axios
//         .get("me")
//         .then(function (response) {
//             authUser.value = response.data;
//         })
//         .catch(function (error) {
//             console.log(error);
//         });
// });
</script>

<template>
    <h1>Profile</h1>
</template>
