import { createRouter, createWebHistory } from "vue-router";
import NotFound from "../components/NotFound/NotFound.vue";
import Posts from "../components/Post/Posts.vue";
import ProfileView from "../components/Profile/ProfileView.vue";
import ProfileEdit from "../components/Profile/ProfileEdit.vue";
import Register from "../components/Auth/Register.vue";
import Login from "../components/Auth/Login.vue";

const routes = [
    {
        path: "/login",
        name : "Login",
        component: Login
    },
    {
        path: "/register",
        name : "Register",
        component: Register
    },
    {
        path: "/dashboard",
        name : "Dashboard",
        component: Posts
    },
    {
        path: '/profile/:id',
        name : "Profile",
        component: ProfileView
    },
    {
        path: '/profile-edit/:id',
        name : "ProfileEdit",
        component: ProfileEdit
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