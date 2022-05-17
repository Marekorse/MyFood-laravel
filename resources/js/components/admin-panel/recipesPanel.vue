<template>
    <div class="">
        <admin-table>
            <template v-slot:title>{{ $tc('adminPanel.recipe', 2) }}</template>
            <template v-slot:options>
                <div
                    class="text-sm bg-white py-4 px-8 flex flex-col gap-3 rounded-lg font-semibold text-gray-600 shadow-md border border-gray-200 cursor-pointer">
                    <div>
                        <div class="flex gap-2 items-center">
                            <span class="text-lg">
                                {{ $tc('adminPanel.recipe', 1) }}
                            </span>
                            <span
                                v-if="filter.user"
                                class="hover:text-red-500 text-red-400 text-xs font-semibold"
                                @click=";(filter.user = ''), paginate()">
                                {{ $t('global.cancel') }}
                            </span>
                        </div>

                        <div
                            class="h-0.5 mt-1 w-full bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>

                        <ul
                            class="text-sm rounded-lg font-semibold text-gray-600 cursor-pointer">
                            <li
                                :class="{
                                    'text-blue-400': filter.user === 'user',
                                }"
                                class="hover:text-blue-400"
                                @click=";(filter.user = 'user'), paginate()">
                                {{ $tc('adminPanel.user', 1) }}
                            </li>
                            <li
                                :class="{
                                    'text-blue-400': filter.user === 'guest',
                                }"
                                class="hover:text-blue-400"
                                @click=";(filter.user = 'guest'), paginate()">
                                {{ $t('adminPanel.unregistredUser') }}
                            </li>
                        </ul>
                    </div>

                    <div>
                        <div class="flex gap-2 items-center">
                            <span class="text-lg">
                                {{ $t('adminPanel.createdAt') }}
                            </span>
                            <span
                                v-if="filter.groupBy"
                                class="hover:text-red-500 text-red-400 text-xs font-semibold"
                                @click=";(filter.groupBy = ''), paginate()">
                                {{ $t('global.cancel') }}
                            </span>
                        </div>

                        <div
                            class="h-0.5 mt-1 w-full bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>

                        <ul
                            class="text-sm rounded-lg font-semibold text-gray-600 cursor-pointer">
                            <li
                                :class="{
                                    'text-blue-400':
                                        filter.groupBy === 'newest',
                                }"
                                class="hover:text-blue-400"
                                @click="
                                    ;(filter.groupBy = 'newest'), paginate()
                                ">
                                {{ $t('adminPanel.newest') }}
                            </li>
                            <li
                                :class="{
                                    'text-blue-400':
                                        filter.groupBy === 'oldest',
                                }"
                                class="hover:text-blue-400"
                                @click="
                                    ;(filter.groupBy = 'oldest'), paginate()
                                ">
                                {{ $t('adminPanel.oldest') }}
                            </li>
                        </ul>
                    </div>

                    <div>
                        <div class="flex gap-2 items-center">
                            <span class="text-lg">
                                {{ $t('adminPanel.confirmStatus') }}
                            </span>
                            <span
                                v-if="filter.confirmation"
                                class="hover:text-red-500 text-red-400 text-xs font-semibold"
                                @click="
                                    ;(filter.confirmation = ''), paginate()
                                ">
                                {{ $t('global.cancel') }}
                            </span>
                        </div>

                        <div
                            class="h-0.5 mt-1 w-full bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>

                        <ul
                            class="text-sm rounded-lg font-semibold text-gray-600 cursor-pointer">
                            <li
                                :class="{
                                    'text-blue-400':
                                        filter.confirmation === 'confirmed',
                                }"
                                class="hover:text-blue-400"
                                @click="
                                    ;(filter.confirmation = 'confirmed'),
                                        paginate()
                                ">
                                {{ $t('adminPanel.confirmed') }}
                            </li>
                            <li
                                :class="{
                                    'text-blue-400':
                                        filter.confirmation === 'unconfirmed',
                                }"
                                class="hover:text-blue-400"
                                @click="
                                    ;(filter.confirmation = 'unconfirmed'),
                                        paginate()
                                ">
                                {{ $t('adminPanel.unconfirmedRecipe') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </template>
            <template v-slot:content>
                <transition-group name="fade-in" mode="out-in">
                    <div
                        @click="showRecipe(recipe)"
                        class="flex gap-4 items-center w-full flex-wrap bg-white rounded-lg m-2 p-2 cursor-pointer"
                        v-for="recipe in recipes.data"
                        :key="recipe.id">
                        <image-component
                            class="w-14 h-10 rounded-lg"
                            thumb
                            :image="recipe.image"
                            :default-image="'images/Default/cover.jpg'" />
                        <span class="w-80 text font-semibold text-blue-400">{{
                            stringLimiter(recipe.title, 35)
                        }}</span>
                        <span class="text-gray-500">{{
                            dateFormat(recipe.created_at)
                        }}</span>
                        <span
                            v-if="recipe.confirmation === 1"
                            class="custom-text inline-block px-2 py-0.5 text-center text-green-400 border border-green-400 rounded-full">
                            {{ $t('adminPanel.confirmed') }}
                        </span>
                        <span
                            v-else
                            class="custom-text inline-block px-2 py-0.5 text-center text-yellow-400 border border-yellow-400 rounded-full">
                            {{ $t('adminPanel.waitingForApprove') }}
                        </span>
                    </div>
                </transition-group>
            </template>
        </admin-table>
        <paginate
            v-if="recipes"
            size="medium"
            :next-page-url="recipes.next_page_url"
            :prev-page-url="recipes.prev_page_url"
            :last-page="recipes.last_page"
            :current-page="recipes.current_page"
            @paginated="paginate"></paginate>

        <modal v-if="showModal" @closeModal="showModal = false">
            <div
                class="bg-white relative h-custom-modal rounded-lg py-2 sm:w-full md:w-3/4 lg:w-1/2">
                <div class="bg-white rounded-lg p-2 h-full w-full z-50">
                    <div
                        class="flex items-center justify-between m-3 absolute top-2 left-2">
                        <div
                            class="flex items-center justify-center px-8 py-0.5 rounded-full bg-blue-500">
                            <a
                                v-if="!selectedRecipe.confirmation"
                                class="text-base text-white font-semibold py-0.5 px-3 transform transition-all duration-300 hover:scale-105"
                                @click.prevent="aproveeRecipe(selectedRecipe)"
                                href=""
                                >{{ $t('adminPanel.approve') }}</a
                            >
                            <a
                                v-if="!editRecipe"
                                class="text-base text-white font-semibold py-0.5 px-3 transform transition-all duration-300 hover:scale-105"
                                :href="
                                    $app_url +
                                    'recept/' +
                                    selectedRecipe.id +
                                    '/edit'
                                "
                                >{{ $t('global.edit') }}</a
                            >
                            <a
                                class="text-base text-white font-semibold py-0.5 px-3 transform transition-all duration-300 hover:scale-105"
                                @click.prevent="beforeDelete()"
                                href=""
                                >{{ $t('global.delete') }}</a
                            >
                        </div>
                        <a
                            class="text-base text-gray-500 underline font-semibold py-0.5 px-5 transform transition-all duration-300 hover:scale-110"
                            @click.prevent="
                                ;(showModal = false), (editRecipe = false)
                            "
                            href=""
                            >{{ $t('global.back') }}</a
                        >
                    </div>
                    <div class="h-full pt-5 overflow-y-auto">
                        <recipe-preview
                            v-if="!editRecipe"
                            :recipe="selectedRecipe"></recipe-preview>
                    </div>
                </div>
            </div>
        </modal>

        <are-you-sure
            v-if="areYouSure"
            @submitEvent="deleteRecipe(selectedRecipe)"
            @closeEvent="beforeDelete()"
            :message="$t('areYouSure.deleteRecipe')"
            :button="$t('global.delete')"></are-you-sure>
    </div>
</template>

<script>
import imageComponent from '../basicComponents/imageComponent.vue'
import recipeForm from '../recipe/recipeForm.vue'
import areYouSure from '../basicComponents/areYouSure.vue'
import recipePreview from './recipePreview.vue'
import recipe from '../recipe/recipe.vue'
import modal from '../basicComponents/modal.vue'
import paginate from '../basicComponents/paginate.vue'
import dropdown from '../basicComponents/dropdown.vue'
import adminTable from './adminTable.vue'

export default {
    components: {
        paginate,
        modal,
        recipe,
        recipePreview,
        areYouSure,
        recipeForm,
        dropdown,
        adminTable,
        imageComponent,
    },

    data: function () {
        return {
            filter: {
                confirmation: '',
                user: '',
                groupBy: '',
            },
            recipes: '',
            showModal: false,
            areYouSure: false,
            selectedRecipe: '',
            editRecipe: false,
        }
    },
    methods: {
        paginate(pageNumber = 1) {
            axios
                .get(
                    `${this.$app_url}admin/recipes?page=${pageNumber}&confirmation=${this.filter.confirmation}&user=${this.filter.user}&group=${this.filter.groupBy}`
                )
                .then((response) => {
                    this.recipes = response.data
                    this.recipes.data.forEach((data) => {
                        data.ingredients = JSON.parse(data.ingredients)
                        data.approach = JSON.parse(data.approach)
                    })
                })
        },
        showRecipe(recipe) {
            this.selectedRecipe = recipe

            this.showModal = true
        },
        aproveeRecipe() {
            axios
                .put(`${this.$app_url}admin/recipe/${this.selectedRecipe.id}`, {
                    confirmation: 1,
                })
                .then((response) => {
                    this.showModal = false
                    this.recipes.data[
                        this.recipes.data.indexOf(this.selectedRecipe)
                    ].confirmation = 1
                })
        },
        beforeDelete() {
            if (this.showModal) {
                this.showModal = false
                this.areYouSure = true
            } else {
                this.showModal = true
                this.areYouSure = false
            }
        },

        deleteRecipe(recipe) {
            axios
                .delete(`${this.$app_url}recept/${recipe.id}`)
                .then((response) => {
                    this.areYouSure = false
                    this.showModal = false
                    this.recipes.data.splice(
                        this.recipes.data.indexOf(recipe),
                        1
                    )
                })
        },
    },
    created() {
        this.paginate()
    },
}
</script>

<style lang="scss" scoped>
.fade-in-enter-active {
    transition: all, 0.3s;
}
.fade-in-leave-active {
    opacity: 0;
}
.fade-in-enter,
.fade-in-leave-to {
    opacity: 0;
    transform: scale(0.55);
}

.h-custom-modal {
    height: 85%;
}
.custom-text {
    font-size: 0.6rem /* 12px */;
    line-height: 1rem /* 16px */;
}
</style>
