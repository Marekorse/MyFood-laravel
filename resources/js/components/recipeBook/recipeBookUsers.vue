<template>
    <div>
        <div v-if="bookUsers">
            <div class="block text-center mt-4">
                <leaderboard-panel class="m-2 mt-5">
                    <template v-slot:icon>
                        <div class="flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 p-0.5 text-sky bg-white rounded-full border-4 border-sky"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span
                                class="text-gray-500 ml-2 font font-semibold"
                                >{{ $t('recipesBook.users') }}</span
                            >
                        </div>
                    </template>
                    <template v-slot:labels>
                        <slider>
                            <recipe-book-user-card
                                v-for="user in bookUsers.data"
                                :key="user.id"
                                :user="user" />
                        </slider>
                    </template>
                </leaderboard-panel>
            </div>
            <div
                class="flex w-full flex-wrap justify-center items-center gap-1">
                <div
                    v-if="authUser.id && bookUserId !== authUser.id"
                    class="h-12 flex items-center justify-center">
                    <transition name="fade" mode="out-in">
                        <button
                            v-if="!shared && !requested"
                            @click="sendShareRequest()"
                            class="h-6 w-40 left-2/4 -ml-20 md:-ml-0 md:left-0 m-2 md:m-0 flex items-center justify-center text-sm font-semibold text-white bg-indigo-600 rounded-full transform transition-all duration-300 hover:scale-105 hover:bg-indigo-500"
                            type="submit">
                            {{ $t('recipesBook.shareRequest') }}
                        </button>

                        <button
                            v-else-if="shared"
                            @click="removeShare(authUser)"
                            class="ml-10 h-6 w-32 md:-ml-0 md:left-0 m-2 md:m-0 flex items-center justify-center text-sm font-semibold text-white bg-indigo-600 rounded-full transform transition-all duration-300 hover:scale-105 hover:bg-indigo-500"
                            type="submit">
                            {{ $t('recipesBook.removeShare') }}
                        </button>

                        <span
                            v-else-if="requested && !shared"
                            class="h-10 w-40 left-2/4 -ml-20 md:-ml-0 md:left-0 m-2 md:m-0 flex items-center justify-center px-3 font-xs text-blue-400 font-semibold"
                            type="submit">
                            {{ $t('recipesBook.requested') }}
                        </span>
                    </transition>
                </div>
                <span
                    @click="showModal = true"
                    class="text-sm font-semibold text-white bg-indigo-600 rounded-full px-6 py-0.5 cursor-pointer transform transition-all duration-300 hover:scale-105 hover:bg-indigo-500">
                    {{ $t('recipesBook.users') }}
                </span>
                <modal v-if="showModal" @closeModal="showModal = false">
                    <div
                        class="w-full md:w-4/5 lg:w-3/6 xl:w-2/6 bg-white rounded-xl overflow-hidden m-1">
                        <div
                            class="w-full text-center bg-indigo-600 text-white">
                            <span class="text-lg m-4 text-white font-semibold">
                                {{ $t('recipesBook.users') }}
                            </span>
                        </div>

                        <ul
                            class="w-full text-center p-3 max-h-96 overflow-y-auto">
                            <li
                                class="text-base m-1 w-full text-gray-500 font-semibold"
                                v-for="user in bookUsers.data"
                                :key="user.id">
                                <div
                                    class="flex w-full justify-between flex-shrink-0 items-center gap-2">
                                    <div class="flex justify-between gap-1">
                                        <avatar-component
                                            :image="user.avatar"
                                            :size="50"
                                            showOnClick
                                            thumb />
                                        <div
                                            class="flex flex-col flex-shrink-0 justify-start">
                                            <a
                                                class="text-blue-400 text-left"
                                                :href="
                                                    $app_url +
                                                    'pouzivatel/' +
                                                    user.name
                                                "
                                                >{{ user.name }}
                                            </a>
                                            <span>
                                                {{ user.email }}
                                            </span>
                                        </div>
                                    </div>
                                    <span
                                        v-if="owner && user.id != authUser.id"
                                        @click="beforeRemove(user)"
                                        class="text-sm font-semibold text-red-500">
                                        {{ $t('global.delete') }}
                                    </span>
                                </div>
                            </li>
                        </ul>
                        <div
                            v-if="bookUsers.next_page_url"
                            class="w-full flex justify-center p-3 font-semibold text-blue-500">
                            <a @click.prevent="paginate()" href="">{{
                                $t('global.showMore')
                            }}</a>
                        </div>
                    </div>
                </modal>
            </div>
        </div>
        <are-you-sure
            v-if="areYouSure"
            @submitEvent="removeShare(selectedUser)"
            @closeEvent="showModal = true"
            :message="$t('areYouSure.deleteUser')"
            :button="$t('global.delete')"></are-you-sure>
    </div>
</template>

<script>
import leaderboardPanel from '../recipe/leaderboardPanel.vue'
import areYouSure from '../basicComponents/areYouSure.vue'
import modal from '../basicComponents/modal.vue'
import dropdown from '../basicComponents/dropdown.vue'
import recipeBookUserCard from './recipeBookUserCard.vue'
import Slider from '../basicComponents/slider.vue'
import AvatarComponent from '../basicComponents/avatarComponent.vue'
export default {
    components: {
        dropdown,
        modal,
        areYouSure,
        leaderboardPanel,
        recipeBookUserCard,
        Slider,
        AvatarComponent,
    },
    props: {
        owner: Boolean,
        bookId: Number,
        bookUserId: Number,
        authUser: Object,
    },

    data: function () {
        return {
            selectedUser: null,
            areYouSure: false,
            showModal: false,
            requested: false,
            shared: false,
            bookUsers: [],
        }
    },
    created() {
        axios
            .get(`${this.$app_url}book/users?id=${this.bookId}`)
            .then((res) => {
                this.shared = res.data.shared
                this.requested = res.data.requested
                this.bookUsers = res.data.book_users
            })
    },

    methods: {
        paginate() {
            axios
                .get(`${this.bookUsers.next_page_url}&&id=${this.bookId}`)
                .then((res) => {
                    this.bookUsers.current_page =
                        res.data.book_users.current_page
                    this.bookUsers.next_page_url =
                        res.data.book_users.next_page_url
                    res.data.book_users.data.forEach((item) => {
                        this.bookUsers.data.push(item)
                    })
                })
        },
        beforeRemove(user) {
            this.selectedUser = user
            this.showModal = false
            this.areYouSure = true
        },
        removeShare(user) {
            axios
                .delete(`${this.$app_url}book/${this.bookId}/user/${user.id}`)
                .then((res) => {
                    this.shared = false
                    const user = this.bookUsers.data.find((user) => {
                        return user.id === user.id
                    })
                    if (user) {
                        this.bookUsers.data.splice(
                            this.bookUsers.data.indexOf(user),
                            1
                        )
                    }
                    this.reset()
                })
                .catch((err) => {
                    console.log(err)
                })
        },
        reset() {
            this.selectedUser = null
            this.showModal = false
            this.areYouSure = false
        },
        sendShareRequest() {
            axios
                .post(`${this.$app_url}book/request`, {
                    to_user: this.bookUserId,
                    from_user: this.authUser.id,
                })
                .then((res) => {
                    this.requested = true
                })
        },
    },
}
</script>
