<template>
    <div>
        <div class="w-full mb-5">
            <div
                class="relative flex flex-col justify-center bg-white py-4 lg:py-6 md:px-6 lg:rounded-3xl lg:w-64 lg:my-4 shadow-xl">
                <h2 class="text-xl font-semibold my-2 text-blue-400">
                    {{ $t('recipesBook.improvementsByUser') }}
                </h2>
                <ul class="list-none cursor-pointer">
                    <span
                        class="flex justify-center lg:justify-start items-center gap-4 text-gray-500 text-base font-semibold"
                        v-for="improvement in improvements"
                        :key="improvement.id">
                        <div class="flex items-center justify-start gap-2">
                            <span
                                class="hover:text-blue-300"
                                @click="setImprovement(improvement)">
                                {{ improvement.user.name }}
                            </span>
                            <span
                            class="text-xs text-red-400"
                                @click.prevent="areYouSure = true"
                                v-if="authId == improvement.user.id">
                                <a class="text-xs text-red-400" @click.prevent href=""></a>
                                {{ $t('global.delete') }}
                            </span>
                        </div>
                        <are-you-sure
                            v-if="areYouSure"
                            @submitEvent="deleteImprovement(improvement)"
                            @closeEvent="areYouSure = false"
                            :button="$t('global.delete')"
                            :message="
                                $t('areYouSure.deleteImprovement')
                            "></are-you-sure>
                    </span>

                    <li
                        @click="setImprovement('original')"
                        class="flex justify-center lg:justify-start items-center gap-4 text-gray-500 text-base hover:text-blue-300 font-semibold">
                        {{ $t('recipesBook.original') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import areYouSure from '../basicComponents/areYouSure.vue'
export default {
    components: {
        areYouSure,
    },
    props: {
        authId: {
            type: Number,
            default: null,
        },
        bookId: {
            type: Number,
            default: null,
        },

        improvements: {
            type: Array,
            default: null,
        },
    },
    data() {
        return {
            areYouSure: false,
        }
    },
    methods: {
        deleteImprovement(improvement) {
            axios
                .delete(
                    `${this.$app_url}receptar/${this.bookId}/recept/${improvement.id}`
                )
                .then((res) => {
                    this.improvements.splice(
                        this.improvements.indexOf(improvement),
                        1
                    )
                    this.$emit('changeImprovement', 'original')
                })
                .catch((err) => {
                    console.log(err)
                })
        },
        setImprovement(improvement) {
            if (improvement === 'original') {
                return this.$emit('changeImprovement', improvement)
            }
            const ignoreFields = ['user_id', 'book_id', 'recipe_id']

            for (const [key, value] of Object.entries(improvement)) {
                if (ignoreFields.includes(key)) {
                    delete improvement[key]
                }
            }

            this.$emit('changeImprovement', improvement)
        },
    },
}
</script>

<style lang="scss" scoped></style>
