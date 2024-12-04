import { createRouter, createWebHistory } from "vue-router";
import NotFound from "../components/NotFound/NotFound.vue";
import Posts from "../components/Post/Posts.vue";
import ProfileView from "../components/Profile/ProfileView.vue";

const routes = [
    {
        path: "/dashboard",
        name : "Dashboard",
        component: Posts
    },
    {
        path: "/profile/:id",
        name : "Profile",
        component: ProfileView
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound
    }
];

const router = createRouter({
    history : createWebHistory(),
    routes
});

export default router;