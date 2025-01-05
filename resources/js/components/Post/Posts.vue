<script setup>
import axios from "axios";
import {
    inject,
    onBeforeUnmount,
    onMounted,
    reactive,
    ref,
    watch,
    watchEffect,
} from "vue";
import PostAdd from "./PostAdd.vue";
import ImageShow from "../ImageShow/ImageShow.vue";
import moment from "moment";
import { getAllPost } from "../../Service/post";
import { useToast } from "vue-toast-notification";
import {
    authenticationCheck,
    unAuthenticateUser,
} from "../../middleware/authentication";

const authUser = inject("authUser");
const searchResponse = inject("posts");

const $toast = useToast();
let allPost = ref([]);
let allComment = ref([]);
let postId = ref(null);
let broascastPostId = ref(0);
let currentPage = ref(0);
let lastPage = ref(0);
const commentArea = ref(0);
const commentForm = ref({
    comment_message: "",
});

const handlePostDropDown = (key = null) => {
    postId.value = postId.value != null ? null : key;
};

const handlePostDelete = async (id) => {
    const formData = new FormData();
    formData.append("_method", "DELETE");

    await axios
        .delete(`/api/post/${id}`)
        .then(function (response) {
            $toast.info(response.data.message);
            showAllPost();
        })
        .catch(function (error) {
            console.log(error.response.data.message);
        });
};

const showAllPost = async () => {
    await getAllPost()
        .then((response) => {
            allPost.value = response.data;
            currentPage.value = response.current_page;
            lastPage.value = response.last_page;
        })
        .catch((error) => {
            $toast.error(error.response.data.message);
        });
};

const showAllComment = async () => {
    await axios
        .get(`/api/post-reacts/comments`)
        .then(function (response) {
            allComment.value = response.data.data;
        })
        .catch(function (error) {
            $toast.error(error.response.data.message);
        });
};

const handlePostPagination = async () => {
    currentPage.value++;
    await axios
        .get(`/api/post?page=${currentPage.value}`)
        .then(function (response) {
            allPost.value.push(...response.data.data);
        })
        .catch(function (error) {
            $toast.error(error.response.data.message);
        });
};

const handlePostLikeUnlike = (userId, postId, likeStatus) => {
    const formData = new FormData();
    formData.append("user_id", userId);
    formData.append("post_id", postId);
    formData.append("like_status", likeStatus);

    axios
        .post(`/api/post-reacts/like-unlike-store`, formData)
        .then(function (response) {
            commentArea.value = true;
            showAllPost();
            showAllComment();
            $toast.success(response.data.message);
        })
        .catch(function (error) {
            $toast.error(error.response.data.message);
        });
};

const handleWriteComment = (userId, postId, commentStatus) => {
    const formData = new FormData();
    formData.append("user_id", userId);
    formData.append("post_id", postId);
    formData.append("comment_status", commentStatus);
    formData.append("comment_message", commentForm.value.comment_message);

    axios
        .post(`/api/post-reacts/comment-store`, formData)
        .then(function (response) {
            showAllPost();
            showAllComment();
            $toast.success(response.data.message);
            commentForm.value.comment_message = "";
        })
        .catch(function (error) {
            $toast.error(error.response.data.message);
            console.log(error.response.data.message);
        });
};

const handleCommentDelete = (id) => {
    axios
        .get(`/api/post-reacts/comment-delete/${id}`)
        .then(function (response) {
            // showAllPost();
            showAllComment();
            $toast.success(response.data.message);
            commentForm.value.comment_message = "";
        })
        .catch(function (error) {
            $toast.error(error.response.data.message);
            console.log(error.response.data.message);
        });
};

Echo.channel(`post.like.event`).listen("PostLikeEvent", (post) => {
    if (authUser.value.id == post.post.user.id) {
        $toast.info(
            `${authUser.value.name} likes your post : ${post.post.barta.slice(
                0,
                10
            )}...`
        );
    }
});

watch(searchResponse, (newData, oldData) => {
    if (searchResponse.length != 0) {
        allPost.value = newData;
    }
});

onMounted(() => {
    showAllPost();
    showAllComment();
    authenticationCheck();
});

onBeforeUnmount(() => {
    console.log("before unmounted");
    Echo.leave(`post.like.event`);
});
</script>

<template>
    <main
        class="container max-w-xl mx-auto space-y-8 px-2 md:px-0 min-h-screen mt-24"
    >
        <PostAdd :showAllPost="showAllPost" />

        <!-- Newsfeed -->
        <section id="newsfeed" class="space-y-6">
            <!-- Barta Card -->

            <article
                v-for="post in allPost"
                :key="post.id"
                class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6"
            >
                <!-- Barta Card Top -->
                <header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                <ImageShow
                                    :image="post?.user?.image"
                                    css="h-10 w-10 rounded-full object-cover"
                                />
                            </div>
                            <!-- /User Avatar -->

                            <!-- User Info -->
                            <div
                                class="text-gray-900 flex flex-col min-w-0 flex-1"
                            >
                                <router-link
                                    :to="{
                                        name: 'Profile',
                                        params: {
                                            id: authUser?.id ?? 0,
                                        },
                                    }"
                                    class="hover:underline font-semibold line-clamp-1"
                                >
                                    {{ post.user.name }}
                                </router-link>

                                <router-link
                                    :to="{
                                        name: 'Profile',
                                        params: {
                                            id: authUser?.id ?? 0,
                                        },
                                    }"
                                    class="hover:underline text-sm text-gray-500 line-clamp-1"
                                >
                                    {{ post.user.email }}
                                </router-link>
                            </div>
                            <!-- /User Info -->
                        </div>

                        <!-- Card Action Dropdown -->
                        <div
                            class="flex flex-shrink-0 self-center"
                            v-if="post.user.id == authUser.id"
                        >
                            <div class="relative inline-block text-left">
                                <div>
                                    <button
                                        @click="handlePostDropDown(post.id)"
                                        type="button"
                                        class="-m-2 flex items-center rounded-full p-2 text-gray-400 hover:text-gray-600"
                                        id="menu-0-button"
                                    >
                                        <span class="sr-only"
                                            >Open options</span
                                        >
                                        <svg
                                            class="h-5 w-5"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"
                                            ></path>
                                        </svg>
                                    </button>
                                </div>
                                <!-- Dropdown menu -->
                                <div
                                    v-if="postId == post.id"
                                    :x-show="true"
                                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    role="menu"
                                    aria-orientation="vertical"
                                    aria-labelledby="user-menu-button"
                                    tabindex="-1"
                                >
                                    <router-link
                                        :to="{
                                            name: 'PostEdit',
                                            params: {
                                                id: post.id,
                                            },
                                        }"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        role="menuitem"
                                        tabindex="-1"
                                        id="user-menu-item-0"
                                        >Edit</router-link
                                    >
                                    <form
                                        @submit.prevent="
                                            handlePostDelete(post.id)
                                        "
                                        class="hover:bg-gray-100"
                                    >
                                        <button
                                            type="submit"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem"
                                            tabindex="-1"
                                            id="user-menu-item-1"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /Card Action Dropdown -->
                    </div>
                </header>

                <!-- Content -->
                <div class="py-4 text-gray-700 font-normal">
                    <router-link
                        :to="{
                            name: 'PostView',
                            params: {
                                id: post.id,
                            },
                        }"
                    >
                        <p>
                            {{ post.barta }}
                            <br />
                            <a
                                href="#laravel"
                                class="text-black font-semibold hover:underline"
                            ></a>
                            <br />
                            <br />
                        </p>
                    </router-link>

                    <ImageShow :image="post.image" css="" />
                </div>

                <!-- Date Created & View Stat -->
                <div class="flex items-center gap-2 text-gray-500 text-xs my-2">
                    <span class="">{{
                        moment(post.created_at).fromNow()
                    }}</span>
                    <span class="">•</span>
                    <span>450 views</span>
                </div>

                <!-- Barta Card Bottom -->
                <footer class="border-t border-gray-200 pt-2">
                    <!-- Card Bottom Action Buttons -->
                    <div class="flex items-center justify-between">
                        <div class="flex gap-8 text-gray-600 justify-between">
                            <!-- Like Button -->

                            <a
                                v-if="
                                    post?.like == null ||
                                    post?.like?.like_status == 0
                                "
                                href="#"
                                @click.prevent="
                                    handlePostLikeUnlike(
                                        post.user.id,
                                        post.id,
                                        1
                                    )
                                "
                                type="button"
                                class="-m-2 flex gap-2 text-xs items-center rounded-full p-2 text-gray-600 hover:text-gray-800"
                            >
                                <span class="sr-only">Like</span>
                                <img
                                    class="w-5"
                                    src="https://www.svgrepo.com/show/1198/like.svg"
                                    alt=""
                                />

                                <!-- <p>0</p> -->
                            </a>
                            <!-- /Like Button -->

                            <!-- /Unlike Button -->
                            <a
                                v-if="post?.like?.like_status == 1"
                                href="#"
                                @click.prevent="
                                    handlePostLikeUnlike(
                                        post?.user.id,
                                        post.id,
                                        0
                                    )
                                "
                                type="button"
                                class="-m-2 flex gap-2 text-xs items-center rounded-full p-2 text-gray-600 hover:text-gray-800"
                            >
                                <img
                                    class="w-5"
                                    src="https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/blue-like-button-icon.png"
                                    alt=""
                                />
                            </a>
                            <!-- /Unlike Button -->
                        </div>

                        <div class="flex gap-8 text-gray-600 justify-between">
                            <!-- Comment Button -->
                            <a
                                href="javascript:void(0)"
                                type="button"
                                class="-m-2 flex gap-2 text-xs items-center rounded-full p-2 text-gray-600 hover:text-gray-800"
                            >
                                <span class="sr-only">Comment</span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="w-5 h-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z"
                                    ></path>
                                </svg>

                                <!-- <p>0</p> -->
                            </a>
                            <!-- /Comment Button -->
                        </div>
                    </div>

                    <!-- Comment Write -->
                    <div class="my-2">
                        <hr />
                        <!-- Comment show area -->
                        <header v-for="comment in allComment">
                            <div
                                v-if="comment.post_id == post.id"
                                class="flex items-center justify-between my-1 p-2 bg-gray-100 rounded-lg"
                            >
                                <div class="flex items-center space-x-3">
                                    <!-- User Avatar -->
                                    <div class="flex-shrink-0">
                                        <ImageShow
                                            :image="comment?.users?.image"
                                            css="h-10 w-10 rounded-full object-cover"
                                        />
                                    </div>
                                    <!-- /User Avatar -->

                                    <!-- User Info -->
                                    <div
                                        class="text-gray-900 flex flex-col min-w-0 flex-1"
                                    >
                                        <router-link
                                            :to="{
                                                name: 'Profile',
                                                params: {
                                                    id: authUser?.id,
                                                },
                                            }"
                                            class="hover:underline font-semibold line-clamp-1"
                                        >
                                            {{ comment.users.name }}
                                        </router-link>

                                        <p>{{ comment.comment_message }}</p>
                                    </div>
                                    <!-- /User Info -->
                                </div>

                                <!-- Comment delete -->
                                <div
                                    class="flex flex-shrink-0 self-center"
                                    v-if="comment.users.id == authUser.id"
                                >
                                    <button
                                        class="w-5"
                                        @click="handleCommentDelete(comment.id)"
                                    >
                                        <img
                                            src="https://cdn-icons-png.flaticon.com/512/6861/6861362.png"
                                            alt=""
                                        />
                                    </button>
                                </div>
                                <!-- /Comment delete -->
                            </div>
                        </header>
                        <!-- /Comment show area -->
                        <form
                            @submit.prevent="
                                handleWriteComment(authUser.id, post.id, 1)
                            "
                        >
                            <textarea
                                name=""
                                v-model="commentForm.comment_message"
                                id=""
                                class="w-full p-1 outline-none border-2 border-gray-500 w-full mt-3 rounded-lg"
                                placeholder="Write your comment..."
                            ></textarea>

                            <button
                                class="bg-slate-200 py-1 px-2 rounded-sm shadow-lg flex justify-self-end"
                                type="submit"
                            >
                                <img
                                    src="https://cdn2.iconfinder.com/data/icons/arrow-vol-2-8/64/Send_Button-512.png"
                                    alt=""
                                    class="w-5"
                                />
                            </button>
                        </form>
                    </div>
                    <!-- /Comment Write -->

                    <!-- /Card Bottom Action Buttons -->
                </footer>
                <!-- /Barta Card Bottom -->
            </article>

            <article
                v-if="allPost.length < 1"
                class="bg-white border-2 border-red-900 rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6"
            >
                <!-- Barta Card Top -->
                <header>
                    <div class="flex items-center justify-center">
                        <div class="flex items-center space-x-3">
                            <!-- User Info -->
                            <div
                                class="text-red-900 flex flex-col min-w-0 flex-1"
                            >
                                <p class="text-red-900 font-bold">
                                    Data not found !
                                </p>
                            </div>
                        </div>
                    </div>
                </header>
            </article>

            <section class="flex justify-content-center">
                <button
                    v-if="currentPage != lastPage"
                    class="bg-gray-500 p-2 rounded-sm text-white text-center font-medium m-auto hover:text-black hover:bg-gray-100"
                    @click="handlePostPagination"
                >
                    Load More
                </button>
            </section>

            <!-- /Barta Card -->

            <!-- Barta Card With Image -->
            <!--        <article-->
            <!--          class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6">-->
            <!--          &lt;!&ndash; Barta Card Top &ndash;&gt;-->
            <!--          <header>-->
            <!--            <div class="flex items-center justify-between">-->
            <!--              <div class="flex items-center space-x-3">-->
            <!--                &lt;!&ndash; User Avatar &ndash;&gt;-->
            <!--                <div class="flex-shrink-0">-->
            <!--                  <img-->
            <!--                    class="h-10 w-10 rounded-full object-cover"-->
            <!--                    src="https://avatars.githubusercontent.com/u/61485238"-->
            <!--                    alt="Al Nahian" />-->
            <!--                </div>-->
            <!--                &lt;!&ndash; /User Avatar &ndash;&gt;-->

            <!--                &lt;!&ndash; User Info &ndash;&gt;-->
            <!--                <div class="text-gray-900 flex flex-col min-w-0 flex-1">-->
            <!--                  <a-->
            <!--                    href="https://github.com/alnahian2003"-->
            <!--                    class="hover:underline font-semibold line-clamp-1">-->
            <!--                    Al Nahian-->
            <!--                  </a>-->

            <!--                  <a-->
            <!--                    href="https://twitter.com/alnahian2003"-->
            <!--                    class="hover:underline text-sm text-gray-500 line-clamp-1">-->
            <!--                    @alnahian2003-->
            <!--                  </a>-->
            <!--                </div>-->
            <!--                &lt;!&ndash; /User Info &ndash;&gt;-->
            <!--              </div>-->

            <!--              &lt;!&ndash; Card Action Dropdown &ndash;&gt;-->
            <!--              <div class="flex flex-shrink-0 self-center">-->
            <!--                <div class="relative inline-block text-left">-->
            <!--                  <div>-->
            <!--                    <button-->
            <!--                      type="button"-->
            <!--                      class="-m-2 flex items-center rounded-full p-2 text-gray-400 hover:text-gray-600"-->
            <!--                      id="menu-0-button">-->
            <!--                      <span class="sr-only">Open options</span>-->
            <!--                      <svg-->
            <!--                        class="h-5 w-5"-->
            <!--                        viewBox="0 0 20 20"-->
            <!--                        fill="currentColor"-->
            <!--                        aria-hidden="true">-->
            <!--                        <path-->
            <!--                          d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"></path>-->
            <!--                      </svg>-->
            <!--                    </button>-->
            <!--                  </div>-->
            <!--                </div>-->
            <!--              </div>-->
            <!--              &lt;!&ndash; /Card Action Dropdown &ndash;&gt;-->
            <!--            </div>-->
            <!--          </header>-->

            <!--          &lt;!&ndash; Content &ndash;&gt;-->
            <!--          <div class="py-4 text-gray-700 font-normal space-y-2">-->
            <!--            <img-->
            <!--              src="https://images.pexels.com/photos/6261178/pexels-photo-6261178.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"-->
            <!--              class="min-h-auto w-full rounded-lg object-cover max-h-64 md:max-h-72"-->
            <!--              alt="" />-->
            <!--            <p>Beautiful 😍😘 Innit?</p>-->
            <!--          </div>-->

            <!--          &lt;!&ndash; Date Created & View Stat &ndash;&gt;-->
            <!--          <div class="flex items-center gap-2 text-gray-500 text-xs my-2">-->
            <!--            <span class="">2 days ago</span>-->
            <!--            <span class="">•</span>-->
            <!--            <span>1,189 views</span>-->
            <!--          </div>-->

            <!--          &lt;!&ndash; Barta Card Bottom &ndash;&gt;-->
            <!--          <footer class="border-t border-gray-200 pt-2">-->
            <!--            &lt;!&ndash; Card Bottom Action Buttons &ndash;&gt;-->
            <!--            <div class="flex items-center justify-between">-->
            <!--              <div class="flex gap-8 text-gray-600">-->
            <!--                &lt;!&ndash; Heart Button &ndash;&gt;-->
            <!--                <button-->
            <!--                  type="button"-->
            <!--                  class="-m-2 flex gap-2 text-xs items-center rounded-full p-2 text-gray-600 hover:text-gray-800">-->
            <!--                  <span class="sr-only">Like</span>-->
            <!--                  <svg-->
            <!--                    xmlns="http://www.w3.org/2000/svg"-->
            <!--                    fill="none"-->
            <!--                    viewBox="0 0 24 24"-->
            <!--                    stroke-width="2"-->
            <!--                    stroke="currentColor"-->
            <!--                    class="w-5 h-5">-->
            <!--                    <path-->
            <!--                      stroke-linecap="round"-->
            <!--                      stroke-linejoin="round"-->
            <!--                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />-->
            <!--                  </svg>-->

            <!--                  <p>24</p>-->
            <!--                </button>-->
            <!--                &lt;!&ndash; /Heart Button &ndash;&gt;-->

            <!--                &lt;!&ndash; Comment Button &ndash;&gt;-->
            <!--                <button-->
            <!--                  type="button"-->
            <!--                  class="-m-2 flex gap-2 text-xs items-center rounded-full p-2 text-gray-600 hover:text-gray-800">-->
            <!--                  <span class="sr-only">Comment</span>-->
            <!--                  <svg-->
            <!--                    xmlns="http://www.w3.org/2000/svg"-->
            <!--                    fill="none"-->
            <!--                    viewBox="0 0 24 24"-->
            <!--                    stroke-width="2"-->
            <!--                    stroke="currentColor"-->
            <!--                    class="w-5 h-5">-->
            <!--                    <path-->
            <!--                      stroke-linecap="round"-->
            <!--                      stroke-linejoin="round"-->
            <!--                      d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />-->
            <!--                  </svg>-->

            <!--                  <p>8</p>-->
            <!--                </button>-->
            <!--                &lt;!&ndash; /Comment Button &ndash;&gt;-->
            <!--              </div>-->

            <!--              <div>-->
            <!--                &lt;!&ndash; Share Button &ndash;&gt;-->
            <!--                <button-->
            <!--                  type="button"-->
            <!--                  class="-m-2 flex gap-2 text-xs items-center rounded-full p-2 text-gray-600 hover:text-gray-800">-->
            <!--                  <span class="sr-only">Share</span>-->
            <!--                  <svg-->
            <!--                    xmlns="http://www.w3.org/2000/svg"-->
            <!--                    fill="none"-->
            <!--                    viewBox="0 0 24 24"-->
            <!--                    stroke-width="1.5"-->
            <!--                    stroke="currentColor"-->
            <!--                    class="w-5 h-5">-->
            <!--                    <path-->
            <!--                      stroke-linecap="round"-->
            <!--                      stroke-linejoin="round"-->
            <!--                      d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />-->
            <!--                  </svg>-->
            <!--                </button>-->
            <!--                &lt;!&ndash; /Share Button &ndash;&gt;-->
            <!--              </div>-->
            <!--            </div>-->
            <!--            &lt;!&ndash; /Card Bottom Action Buttons &ndash;&gt;-->
            <!--          </footer>-->
            <!--          &lt;!&ndash; /Barta Card Bottom &ndash;&gt;-->
            <!--        </article>-->
            <!-- /Barta Card With Image -->
        </section>
        <!-- /Newsfeed -->
    </main>
</template>
