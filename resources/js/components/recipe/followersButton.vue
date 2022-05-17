<template>
    <div>
        <div v-if="followers && !disabled" class="">
            <div
                class="flex justify-center md:justify-start items-center space-x-2 text-gray-500 text-base hover:text-blue-300 font-semibold">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                </svg>
                <p>
                    <a @click.prevent="showModal = true" href="">
                        {{ followers.data.length }}
                        <span v-if="followers.data.length > 1">{{
                            $tc('followers.follower', 2)
                        }}</span>
                        <span v-else>{{ $tc('followers.follower', 1) }}</span>
                    </a>
                </p>
            </div>

            <div
                v-if="authId && recipeUserId !== authId"
                class="relative h-12 w-full mt-2">
                <transition name="fade">
                    <button
                        v-if="!followed && !requested"
                        @click.once="sendFollowerRequest()"
                        class="absolute h-10 w-40 left-2/4 -ml-20 md:-ml-0 md:left-0 m-2 md:m-0 flex items-center justify-center px-3 font-xs font-medium gradient-green-blue focus:outline-none rounded-full px- text-white"
                        type="submit">
                        {{ $t('followers.followButton') }}
                    </button>
                    <button
                        v-else-if="followed"
                        @click.once="removeFollow()"
                        class="absolute h-10 w-40 left-2/4 -ml-20 md:-ml-0 md:left-0 m-2 md:m-0 flex items-center justify-center px-3 font-xs font-medium gradient-green-blue hover:from-green-400 focus:outline-none rounded-full px- text-white"
                        type="submit">
                        {{ $t('followers.removeFollowButton') }}
                    </button>
                    <span
                        v-else-if="requested"
                        class="absolute h-10 w-40 left-2/4 -ml-20 md:-ml-0 md:left-0 m-2 md:m-0 flex items-center justify-center px-3 font-xs font-medium text-blue-400 font-semibold"
                        type="submit">
                        {{ $t('followers.requested') }}
                    </span>
                </transition>
            </div>
            <modal v-if="showModal" @closeModal="showModal = false">
                <div
                    class="rounded-xl m-1 shadow-md w-full md:w-4/5 lg:w-3/6 xl:w-2/6 overflow-y-auto bg-gray-50">
                    <div
                        class="w-full text-left p-2 gradient-green-blue text-white">
                        <span>{{ $tc('followers.follower', 1) }}</span>
                    </div>
                    <ul class="p-2 max-h-80 overflow-y-auto">
                        <li
                            v-if="!followers.data.length"
                            class="p-4 list-none text-base text-blue-400 font font-semibold">
                            {{ $t('followers.empty') }}
                        </li>
                        <li></li>
                        <li
                            v-for="follower in followers.data"
                            :key="follower.id">
                            <a
                                class="flex items-center justify-start transition transform duration-700 hover:translate-x-2"
                                :href="
                                    encodeURI(
                                        $app_url + 'pouzivatel/' + follower.name
                                    )
                                ">
                                <avatar-component
                                    class="mr-1"
                                    :image="follower.avatar"
                                    :size="50"
                                    thumb />

                                <p
                                    class="text-blue-400 font-semibold text-sm ml-2">
                                    {{ stringLimiter(follower.name, 25) }}
                                </p>
                            </a>
                        </li>
                        <div
                            v-if="followers.next_page_url"
                            class="w-full flex justify-center p-3 font-semibold text-blue-500">
                            <a @click.prevent="paginate()" href="">{{
                                $t('global.showMore')
                            }}</a>
                        </div>
                    </ul>
                </div>
            </modal>
        </div>
        <div v-if="followers && disabled">
            <div
                class="flex justify-center md:justify-start items-center space-x-2 text-gray-500 text-base hover:text-blue-300 font-semibold">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                </svg>
                <p>
                    <a @click.prevent="showAlert = true" href="">
                        {{ followers.data.length }}
                        <span v-if="followers.data.length > 1">{{
                            $tc('followers.follower', 2)
                        }}</span>
                        <span v-else>{{ $tc('followers.follower', 1) }}</span>
                    </a>
                </p>
            </div>
            <button
                @click="showAlert = true"
                class="h-10 w-40 mt-1 left-2/4 -ml-20 md:-ml-0 md:left-0 m-2 md:m-0 flex items-center justify-center px-3 font-xs font-medium gradient-green-blue focus:outline-none rounded-full px- text-white"
                type="submit">
                {{ $t('followers.followButton') }}
            </button>
            <modal v-if="showAlert" @closeModal="showAlert = false">
                <auth-alert />
            </modal>
        </div>
    </div>
</template>

<script>
import modal from '../basicComponents/modal.vue'
import dropdown from '../basicComponents/dropdown.vue'
import avatarComponent from '../basicComponents/avatarComponent.vue'
import authAlert from '../basicComponents/authAlert.vue'
export default {
    components: { dropdown, avatarComponent, modal, authAlert },
    props: {
        recipeUserId: Number,
        authId: Number,
        disabled: Boolean,
    },

    data: function () {
        return {
            showAlert: false,
            showModal: false,
            followers: null,
            followed: false,
            requested: false,
        }
    },
    created() {
        axios
            .get(`${this.$app_url}followers?id=${this.recipeUserId}`)
            .then((res) => {
                this.followed = res.data.followed
                this.requested = res.data.requested
                this.followers = res.data.followers
            })
    },

    methods: {
        paginate() {
            axios
                .get(
                    `${this.followers.next_page_url}&&recipe_id=${this.recipeId}`
                )
                .then((res) => {
                    this.followers.current_page = res.data.current_page
                    this.followers.next_page_url = res.data.next_page_url
                    res.data.data.forEach((item) => {
                        this.followers.data.push(item)
                    })
                })
        },
        removeFollow() {
            axios
                .delete(`${this.$app_url}followers/${this.recipeUserId}`)
                .then((res) => {
                    this.followed = false
                    this.followers.data.splice(
                        this.followers.data.indexOf(this.loggedUserFollow),
                        1
                    )
                })
        },
        sendFollowerRequest() {
            axios
                .post(`${this.$app_url}followers/request`, {
                    to_user: this.recipeUserId,
                    from_user: this.authId,
                })
                .then((res) => {
                    this.requested = true
                })
        },
    },
}
</script>
