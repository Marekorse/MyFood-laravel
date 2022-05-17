<template>
    <div>
        <div v-if="followers" class="">
            <div
                class="flex justify-center md:justify-start items-center space-x-2 text-gray-500 text-base hover:text-blue-300 font-semibold">
                <p>
                    <a @click.prevent="showModal = true" href="">
                        <span>{{ $tc('followers.follower', 1) }}</span>
                    </a>
                </p>
            </div>
            <modal v-if="showModal" @closeModal="showModal = false">
                <div
                    v-if="authId"
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
                <auth-alert v-else />
            </modal>
            <div class="text-xl text-blue-400 text-center font-semibold">
                {{ followers.data.length }}
            </div>

            <div
                v-if="authId && recipeUserId !== authId"
                class="relative w-full">
                <transition name="fade">
                    <button
                        v-if="!followed && !requested"
                        @click.once="sendFollowerRequest()"
                        class="absolute h-6 w-32 text-center left-2/4 -ml-16 mx-2 text-sm rounded-full gradient-green-blue text-white"
                        type="submit">
                        {{ $t('followers.followButton') }}
                    </button>
                    <button
                        v-else-if="followed"
                        @click.once="removeFollow()"
                        class="absolute h-6 w-32 text-center left-2/4 -ml-16 mx-2 text-sm rounded-full gradient-green-blue text-white"
                        type="submit">
                        {{ $t('followers.removeFollowButton') }}
                    </button>
                    <span
                        v-else-if="requested"
                        class="absolute w-32 text-center left-2/4 -ml-16 mx-2 text-sm text-blue-500"
                        type="submit">
                        {{ $t('followers.requested') }}
                    </span>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
import AuthAlert from '../basicComponents/authAlert.vue'
import avatarComponent from '../basicComponents/avatarComponent.vue'
import modal from '../basicComponents/modal.vue'
export default {
    components: { modal, avatarComponent, AuthAlert },
    props: {
        recipeUserId: Number,
        authId: Number,
    },

    data: function () {
        return {
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
                    console.log('poziadal som ')
                    this.requested = true
                })
        },
    },
}
</script>
