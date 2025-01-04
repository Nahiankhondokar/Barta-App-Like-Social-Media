<script setup>
import axios from "axios";
import { inject, onMounted, ref } from "vue";

const authUser = inject("authUser");
let notifications = ref([]);

const handlePostNotification = async (e) => {
    await axios
        .get("/api/notifications")
        .then(function (response) {
            notifications.value = response.data.data;
            // console.log(response);
        })
        .catch(function (error) {
            console.log(error.response.data.message);
        });
};

onMounted(() => {
    handlePostNotification();
});
</script>

<template>
    <section class="my-32 container m-auto">
        <div class="head-line">
            <h3 class="text-2xl font-bold text-center my-6">
                Notification List
            </h3>
            <hr />
        </div>
        <div
            class="flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-lg bg-clip-border"
        >
            <table class="w-full text-left table-auto min-w-max">
                <thead>
                    <tr>
                        <th class="p-4 border-b border-slate-300 bg-slate-50">
                            <p
                                class="block text-sm font-normal leading-none text-slate-500"
                            >
                                #
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-300 bg-slate-50">
                            <p
                                class="block text-sm font-normal leading-none text-slate-500"
                            >
                                Message
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-300 bg-slate-50">
                            <p
                                class="block text-sm font-normal leading-none text-slate-500"
                            >
                                Post ID
                            </p>
                        </th>

                        <th class="p-4 border-b border-slate-300 bg-slate-50">
                            <p
                                class="block text-sm font-normal leading-none text-slate-500"
                            >
                                User ID
                            </p>
                        </th>

                        <th class="p-4 border-b border-slate-300 bg-slate-50">
                            <p
                                class="block text-sm font-normal leading-none text-slate-500"
                            ></p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class="hover:bg-slate-50"
                        v-for="(notification, index) in notifications"
                    >
                        <td class="p-4 border-b border-slate-200">
                            <p class="block text-sm text-slate-800">
                                {{ index + 1 }}
                            </p>
                        </td>

                        <td class="p-4 border-b border-slate-200">
                            <p class="block text-sm text-slate-800">
                                {{ notification.data.message }}
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-200">
                            <p class="block text-sm text-slate-800">
                                {{ notification.data.post_id }}
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-200">
                            <p class="block text-sm text-slate-800">
                                {{ notification.data.user_id }}
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-200">
                            <a
                                href="#"
                                class="block text-sm font-semibold text-slate-800"
                            >
                                Read
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>
