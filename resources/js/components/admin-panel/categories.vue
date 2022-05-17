<template>
    <div>
        <div class="">
            <draggable
                class="cursor-pointer inline-flex items-center justify-start"
                v-model="categories"
                group="people"
                @change="reorder">
                <transition-group class="inline-flex flex-wrap" name="fade">
                    <span
                        class="bg-white flex items-center text-blue-500 rounded-lg shadow-sm px-6 m-1 text-base font-semibold transition transform duration-500 hover:scale-105"
                        v-for="(category, index) in categories"
                        :key="category.id">
                        {{ category.name }}
                        <svg
                            @click="deleteCategory(category.id, index)"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-red-400 ml-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg> </span
                ></transition-group>
            </draggable>
            <div
                class="inline-flex items-center bg-white text-center h-6 w-56 text-blue-500 rounded-lg shadow-sm m-1 text-base font-semibold">
                <input
                    @keypress.enter="addNew()"
                    v-model="category"
                    ref="input"
                    class="h-full text-center w-full outline-none border-0 focus:ring-0 text-blue-500 ml-5 p-0"
                    :placeholder="$t('adminPanel.addCategory')"
                    type="text"
                    name=""
                    id="" />
                <div class="w-5">
                    <button
                        @click="addNew()"
                        v-if="category != ''"
                        type="submit">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-green-400"
                            viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
export default {
    components: {
        draggable,
    },
    data() {
        return {
            category: '',
            categories: [],
        }
    },
    methods: {
        deleteCategory(id, index) {
            axios
                .delete(`${this.$app_url}category/${id}`)
                .then((res) => {
                    this.categories.splice(index, 1)
                })
                .catch((err) => {
                    console.log(err)
                })
        },
        addNew() {
            axios
                .post(`${this.$app_url}category`, {
                    name: this.category,
                    position: this.categories.length + 1,
                })
                .then((res) => {
                    this.category = ''
                    this.categories.push(res.data)
                    this.$refs.input.focus()
                })
                .catch((err) => {
                    console.log(err)
                })
        },
        reorder(evt) {
            this.categories.map((category, index) => {
                category.position = index
            })

            axios
                .put(`${this.$app_url}category`, {
                    categories: this.categories,
                })
                .then((res) => {})
                .catch((err) => {
                    console.log(err)
                })
        },
    },
    created() {
        axios
            .get(`${this.$app_url}category`)
            .then((res) => {
                this.categories = res.data
            })
            .catch((err) => {
                console.log(err.response)
            })
    },
}
</script>

<style lang="scss" scoped></style>
