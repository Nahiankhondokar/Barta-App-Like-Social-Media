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
        component: Login,
        meta : {requiresAuth : false}
    },
    {
        path: "/register",
        name : "Register",
        component: Register,
        meta : {requiresAuth : false}
    },
    {
        path: "/",
        name : "Dashboard",
        component: Posts,
        meta: { requiresAuth: true }
    },
    {
        path: '/profile/:id',
        name : "Profile",
        component: ProfileView,
        meta: { requiresAuth: true }
    },
    {
        path: '/profile-edit/:id',
        name : "ProfileEdit",
        component: ProfileEdit,
        meta: { requiresAuth: true }
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

// router.beforeEach((to, from, next) => {
//     const isAuthenticated = !!localStorage.getItem('loggedIn');
//     console.log(isAuthenticated)
//     if (to.meta.requiresAuth && !isAuthenticated) {
//       next({ name: 'Login' });
//     } else if (!to.meta.requiresAuth && isAuthenticated) {
//       next({ name: 'Dashboard' });
//     } else {
//       next();
//     }
//   });

export default router;