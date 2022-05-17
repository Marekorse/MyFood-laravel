<template>
    <div id="comments" class="w-full mb-44" v-if="!loading">
        <div
            class="flex items-center md:px-6 px-2 font-semibold text-blue-400 text-xl mt-3">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8 text-blue-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
            <li class="list-none text-xl text-blue-400 font-bold ml-0.5">
                {{ usersComments.data.length }}
            </li>
            <h1 class="ml-2">{{ $tc('comments.comment', 2) }} :</h1>
        </div>
        <div v-if="!authId">
            <p
                class="inline-block bg-white text-blue-400 text-sm font-semibold m-2 px-6 py-1 rounded-lg">
                {{ $t('comments.loginRequired') }}
            </p>
        </div>
        <div v-if="authId" class="flex flex-col md:px-6 px-2">
            <span class="px-6 text-base font-semibold text-gray-500">
                {{ $t('comments.addNewComment') }}
            </span>
            <textarea
                v-model="comment"
                class="w-full m-1 md:w-2/3 lg:w-3/5 px-3 py-1 rounded-xl outline-none border-none focus:ring-0 resize-none shadow-md"
                name="text"
                id=""
                cols="30"
                rows="3"></textarea>
            <dropdown onClick align="left">
                <template v-slot:text
                    ><svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 ml-3 text-gray-600 cursor-pointer"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg
                ></template>
                <template v-slot:dropDown>
                    <div
                        class="bg-white max-h-36 overflow-y-auto hidebar rounded-xl p-2">
                        <emojis @addEmoji="appendEmoji"></emojis>
                    </div>
                </template>
            </dropdown>
            <div class="p-2">
                <button
                    :disabled="comment === ''"
                    @click.prevent="addNewComment"
                    type="submit"
                    class="flex items-center px-8 py-1 text-xl gradient-green-blue rounded-3xl text-white">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    {{ $t('comments.add') }}
                </button>
            </div>
        </div>
        <p
            class="my-10 font-semibold text-blue-400 text-lg"
            v-if="!usersComments.data.length">
            {{ $t('comments.recipeWithoutComments') }}
        </p>
        <div id="comments">
            <transition-group name="fade">
                <div
                    v-for="comment in usersComments.data"
                    :key="comment.id"
                    class="sm:rounded-xl m-3 sm:px-5 py-2">
                    <comment
                        @deleteComment="deleteComment"
                        :comment="comment"
                        :auth-id="authId"></comment></div
            ></transition-group>
        </div>
        <div
            v-if="usersComments.next_page_url"
            class="w-full flex justify-center p-3 font-semibold text-blue-500">
            <a @click.prevent="paginate()" href="">{{
                $t('global.showMore')
            }}</a>
        </div>
        <div ref="end"></div>
    </div>
</template>

<script>
import dropdown from './basicComponents/dropdown.vue'
import emojis from './conversations/emojis.vue'
import comment from './userComment.vue'
export default {
    components: { comment, emojis, dropdown },
    props: { recipeId: Number, authId: Number, recipeUserId: Number },

    data: function () {
        return {
            loading: true,
            comment: '',
            usersComments: null,
            data: '',
        }
    },
    methods: {
        paginate() {
            axios
                .get(
                    `${this.usersComments.next_page_url}&&recipe_id=${this.recipeId}`
                )
                .then((res) => {
                    this.usersComments.current_page = res.data.current_page
                    this.usersComments.next_page_url = res.data.next_page_url
                    res.data.data.forEach((item) => {
                        this.usersComments.data.push(item)
                    })
                })
        },
        appendEmoji(emoji) {
            this.comment = this.comment.concat(emoji)
        },
        addNewComment() {
            axios
                .post(`${this.$app_url}comments`, {
                    text: this.comment,
                    recipe_id: this.recipeId,
                    recipe_user_id: this.recipeUserId,
                })
                .then((response) => {
                    this.usersComments.data.splice(0, 0, response.data)
                    this.scrollIntoView()
                })

            this.comment = ''
        },
        deleteComment(comment) {
            this.usersComments.data.splice(
                this.usersComments.data.indexOf(comment),
                1
            )
        },
        scrollIntoView() {
            setTimeout(() => {
                document
                    .getElementById('comments')
                    .scrollIntoView({ behavior: 'smooth', block: 'nearest' })
            }, 200)
        },
    },
    created() {
        axios
            .get(`${this.$app_url}comments?recipe_id=${this.recipeId}`)
            .then((res) => {
                this.usersComments = res.data
                this.loading = false
            })
    },
}
</script>
