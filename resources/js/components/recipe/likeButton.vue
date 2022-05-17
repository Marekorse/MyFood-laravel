<template>
    <div>
        <div v-if="!loading && !disabled" class="flex item-center">
            <div @click="showModal = true" class="flex items-center">
                <div
                    class="bg-red-500 px-3 py-0.5 text-white rounded-xl flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 cursor-pointer"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                            clip-rule="evenodd" />
                    </svg>
                    <a
                        @click.prevent
                        class="text-xl text-white font-bold"
                        href=""
                        >{{ likes.total }}</a
                    >
                </div>
                <div v-if="authId" class="w-3 -ml-2 rounded-md inline-block">
                    <div class="h-3 bg-red-500 transform rotate-45"></div>
                </div>
            </div>

            <div class="relative h-12 w-full">
                <transition name="fade" mode="out-in">
                    <svg
                        @click.once="addLike()"
                        v-if="!loggedUserLike"
                        xmlns="http://www.w3.org/2000/svg"
                        class="absolute h-12 w-12 cursor-pointer text-red-500 group rounded-md inline-flex items-center text-base font-medium hover:text-red-700 focus:outline-none"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </transition>
                <transition name="fade" mode="out-in">
                    <svg
                        @click.once="removelike()"
                        v-if="loggedUserLike"
                        xmlns="http://www.w3.org/2000/svg"
                        class="absolute h-12 w-12 cursor-pointer text-red-500 group rounded-md inline-flex items-center text-base font-medium hover:text-red-700 focus:outline-none"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                            clip-rule="evenodd" />
                    </svg>
                </transition>
            </div>
            <modal v-if="showModal" @closeModal="showModal = false">
                <div
                    class="rounded-xl m-1 shadow-md w-full md:w-4/5 lg:w-3/6 xl:w-2/6 overflow-y-auto bg-gray-50">
                    <div
                        class="w-full text-left p-2 gradient-green-blue text-white">
                        <span>{{ $t('likes.userLikes') }}</span>
                    </div>
                    <ul class="p-2 max-h-80 overflow-y-auto">
                        <li
                            v-if="!likes.data.length"
                            class="p-4 list-none text-base text-blue-400 font font-semibold">
                            {{ $t('likes.empty') }}
                        </li>
                        <li></li>
                        <li
                            class="p-2"
                            v-for="like in likes.data"
                            :key="like.id">
                            <a
                                class="flex items-center justify-start transition transform duration-700 hover:translate-x-2"
                                :href="
                                    encodeURI(
                                        $app_url +
                                            'pouzivatel/' +
                                            like.user.name
                                    )
                                ">
                                <avatar-component
                                    class="mr-1"
                                    :image="like.user.avatar"
                                    :size="50"
                                    Thumb />

                                <p class="text-blue-400 text-sm ml-2">
                                    {{ stringLimiter(like.user.name, 25) }}
                                </p>
                            </a>
                        </li>
                        <div
                            v-if="likes.next_page_url"
                            class="w-full flex justify-center p-3 font-semibold text-blue-500">
                            <a @click.prevent="paginate()" href="">{{
                                $t('global.showMore')
                            }}</a>
                        </div>
                    </ul>
                </div>
            </modal>
        </div>
        <div class="flex item-center" v-if="!loading && disabled">
            <div @click="showAlert = true" class="flex items-center">
                <div
                    class="bg-red-500 px-3 py-0.5 text-white rounded-xl flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 cursor-pointer"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                            clip-rule="evenodd" />
                    </svg>
                    <a
                        @click.prevent
                        class="text-xl text-white font-bold"
                        href=""
                        >{{ likes.total }}</a
                    >
                </div>
                <div v-if="authId" class="w-3 -ml-2 rounded-md inline-block">
                    <div class="h-3 bg-red-500 transform rotate-45"></div>
                </div>
            </div>
            <svg
                @click="showAlert = true"
                xmlns="http://www.w3.org/2000/svg"
                class="h-12 w-12 cursor-pointer text-red-500 group rounded-md inline-flex items-center text-base font-medium hover:text-red-700 focus:outline-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <modal v-if="showAlert" @closeModal="showAlert = false">
                <auth-alert />
            </modal>
        </div>
    </div>
</template>

<script>
import modal from '../basicComponents/modal.vue'
import avatarComponent from '../basicComponents/avatarComponent.vue'
import dropdown from '../basicComponents/dropdown.vue'
import AuthAlert from '../basicComponents/authAlert.vue'
export default {
    components: { dropdown, avatarComponent, modal, AuthAlert },
    props: {
        recipeId: Number,
        authId: Number,
        disabled: Boolean,
    },

    data: function () {
        return {
            showAlert: false,
            showModal: false,
            loading: true,
            likes: null,
            loggedUserLike: null,
        }
    },
    created() {
        axios
            .get(`${this.$app_url}likes?recipe_id=${this.recipeId}`)
            .then((res) => {
                this.likes = res.data.likes
                this.loggedUserLike = res.data.logged_user_liked
                this.loading = false
            })
    },

    methods: {
        paginate() {
            axios
                .get(`${this.likes.next_page_url}&&recipe_id=${this.recipeId}`)
                .then((res) => {
                    this.likes.current_page = res.data.current_page
                    this.likes.next_page_url = res.data.next_page_url
                    res.data.data.forEach((item) => {
                        this.likes.data.push(item)
                    })
                })
        },
        removelike() {
            axios
                .delete(`${this.$app_url}likes/${this.loggedUserLike.id}`)
                .then((res) => {
                    this.likes.data.splice(
                        this.likes.data.indexOf(this.loggedUserLike),
                        1
                    )
                    this.likes.total--
                    this.loggedUserLike = null
                })
        },
        addLike() {
            axios
                .post(`${this.$app_url}likes`, {
                    recipe_id: this.recipeId,
                })
                .then((res) => {
                    this.loggedUserLike = res.data
                    this.likes.total++
                    this.likes.data.push(res.data)
                })
        },
    },
}
</script>
