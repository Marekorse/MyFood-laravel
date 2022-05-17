<template>
    <div v-if="!isFetching" class="md:py-2">
        <div
            class="w-full lg:w-64 relative bg-white py-2 px-6 lg:rounded-3xl lg:my-4 sm:shadow-xl">
            <div class="lg:mt-4">
                <p class="text-xl font-semibold my-2 text-blue-400">
                    {{ $t('rating.addRating') }} :
                </p>
                <div class="block text-left text-gray-400 text-sm">
                    <div v-for="(item, index) in 5" :key="index + 1">
                        <li
                            @click.once="addRating(5 - index)"
                            class="flex list none justify-between cursor-pointer">
                            <stars :stars="5 - index"></stars>
                            <span class="font-bold text-lg"
                                >x {{ ratingFilter(5 - index, 'count') }}</span
                            >
                        </li>
                    </div>
                    <div v-if="errors">
                        <transition name="fade">
                            <li v-for="error in errors" class="list-none">
                                {{ error[0] }}
                            </li>
                        </transition>
                    </div>
                    <div v-if="message">
                        <transition name="fade">
                            <li class="list-none">
                                {{ message }}
                            </li>
                        </transition>
                    </div>
                    <div class="border-t-2"></div>
                    <div>
                        <p>{{ $t('rating.myRating') }} :</p>
                        <stars
                            v-if="myRating"
                            filed
                            :stars="myRating.stars"></stars>
                    </div>
                    <div
                        class="w-full flex justify-center mt-1 mb-6 font-semibold text-blue-400 transition transform duration-700 hover:scale-105">
                        <a
                            class="font font-semibold text-blue-400"
                            @click.prevent="showModal = true"
                            href=""
                            >{{ $t('rating.otherRatings') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <modal v-if="showModal" @closeModal="showModal = false">
            <div
                v-if="authId"
                class="rounded-xl m-1 shadow-md w-full md:w-4/5 lg:w-3/6 xl:w-2/6 overflow-y-auto bg-gray-50">
                <div
                    class="w-full text-left p-2 gradient-green-blue text-white">
                    <span>{{ $t('likes.userLikes') }}</span>
                </div>
                <ul class="p-2 max-h-80 overflow-y-auto">
                    <li
                        v-if="!recipeRatingsUsers.data.length"
                        class="p-4 list-none text-base text-blue-400 font font-semibold">
                        {{ $t('rating.empty') }}
                    </li>
                    <li
                        class="p-2"
                        v-for="rating in recipeRatingsUsers.data"
                        :key="rating.id">
                        <a
                            class="flex items-center justify-start transition transform duration-700 hover:translate-x-2"
                            :href="
                                encodeURI(
                                    $app_url + 'pouzivatel/' + rating.user.name
                                )
                            ">
                            <avatar-component
                                class="mr-1"
                                :image="rating.user.avatar"
                                :size="50"
                                thumb />

                            <p class="text-blue-400 text-sm ml-2">
                                {{ stringLimiter(rating.user.name, 25) }}
                            </p>
                        </a>
                    </li>
                    <div
                        v-if="recipeRatingsUsers.next_page_url"
                        class="w-full flex justify-center p-3 font-semibold text-blue-500">
                        <a @click.prevent="paginate()" href="">{{
                            $t('global.showMore')
                        }}</a>
                    </div>
                </ul>
            </div>
            <auth-alert v-else />
        </modal>
    </div>
</template>

<script>
import modal from '../basicComponents/modal.vue'
import dropdown from '../basicComponents/dropdown.vue'
import avatarComponent from '../basicComponents/avatarComponent.vue'
import stars from './stars.vue'
import authAlert from '../basicComponents/authAlert.vue'
export default {
    components: {
        stars,
        dropdown,
        avatarComponent,
        modal,
        authAlert,
    },
    props: { recipeId: Number, authId: Number },

    data: function () {
        return {
            showModal: false,
            isFetching: true,
            recipeRatings: null,
            recipeRatingsUsers: null,
            myRating: [],
            id: this.recipeId,
            errors: null,
            message: null,
        }
    },

    created() {
        axios.get(`${this.$app_url}rating?recipe_id=${this.id}`).then((res) => {
            this.recipeRatings = res.data.ratings
            this.myRating = this.recipeRatings.find((rating) => {
                return rating.user_id === this.authId
            })
            this.isFetching = false
        })

        axios
            .get(`${this.$app_url}rating/users?recipe_id=${this.id}`)
            .then((res) => {
                this.recipeRatingsUsers = res.data
            })
    },

    methods: {
        paginate() {
            axios
                .get(
                    `${this.recipeRatingsUsers.next_page_url}&&recipe_id=${this.id}`
                )
                .then((res) => {
                    this.recipeRatingsUsers.current_page = res.data.current_page
                    this.recipeRatingsUsers.next_page_url =
                        res.data.next_page_url
                    res.data.data.forEach((item) => {
                        this.recipeRatingsUsers.data.push(item)
                    })
                })
        },
        addRating(stars) {
            let formData = new FormData()
            formData.append('recipe_id', this.id)
            formData.append('stars', stars)

            if (this.authId) {
                formData.append('user_id', this.authId)
            }

            axios
                .post(`${this.$app_url}rating`, formData)
                .then((response) => {
                    this.recipeRatings.push(response.data.rating)
                    this.myRating = response.data.rating
                    this.message = this.$t('rating.thanksForRate')
                    if (this.authId) {
                        this.recipeRatingsUsers.data.push(response.data.rating)
                    }
                    setTimeout(() => {
                        this.message = null
                    }, 5000)
                })
                .catch((err) => {
                    this.errors = err.response.data.errors
                    setTimeout(() => {
                        this.errors = null
                    }, 5000)
                })
        },
        ratingFilter(stars, ...options) {
            if (options[0] === 'count') {
                let rating = this.recipeRatings.filter(
                    (element) => element.stars === stars
                )
                return rating.length
            } else {
                return this.recipeRatings.filter(
                    (element) => element.stars === stars
                )
            }
        },
    },
}
</script>
