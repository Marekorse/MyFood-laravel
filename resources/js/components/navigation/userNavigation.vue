<template>
    <div>
        <dropdown onClick hover :fixed="width < 750" align="right">
            <template v-slot:text>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 md:w-10 md:h-10 gradient-green-blue text-white p-1.5 font-bold rounded-full shadow-inner flex items-center custom z-10"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </template>
            <template v-slot:dropDown>
                <div
                    v-if="!user"
                    class="w-72 top-10 flex flex-col bg-white rounded-2xl shadow-md p-4"
                    :class="{ 'h-screen': width < 750, 'h-96': width > 750 }">
                    <div class="w-full flex justify-center">
                        <figure
                            class="w-12 max-h-12 flex items-center justify-center">
                            <img
                                class="w-full object-cover"
                                :src="
                                    $app_url + 'storage/images/logo/cupCake.svg'
                                "
                                alt=""
                                srcset="" />
                        </figure>
                    </div>
                    <div
                        class="mt-2 w-full h-0.5 rounded-2xl bg-gradient-to-r from-transparent via-green-300 to-transparent"></div>

                    <a
                        :href="$app_url + 'prihlasenie'"
                        class="text-lg font-semibold cursor-pointer text-blue-400 transition transform duration-700 hover:translate-x-2"
                        >{{ $t('nav.authOptions.login') }}</a
                    >

                    <a
                        :href="$app_url + 'registracia'"
                        class="text-lg font-semibold cursor-pointer text-blue-400 transition transform duration-700 hover:translate-x-2"
                        >{{ $t('nav.authOptions.register') }}</a
                    >
                </div>
                <div
                    v-if="user"
                    class="w-72 top-10 bg-white rounded-2xl shadow-md"
                    :class="{ 'h-screen': width < 750, 'h-96': width > 750 }">
                    <image-component
                        class="max-h-44 w-full rounded-t-2xl"
                        :image="user.cover" />
                    <div class="relative">
                        <avatar-component
                            class="absolute -top-10 left-2/4 -ml-10"
                            :image="user.avatar"
                            thumb />
                    </div>
                    <div
                        class="w-full text-enter px-4 font-semibold text-base text-blue-400">
                        <a
                            :href="
                                encodeURI($app_url + 'pouzivatel/' + user.name)
                            ">
                            <h4
                                class="text-lg mt-10 mb-1.5 text-blue-400 transition transform duration-400 hover:text-blue-600 w-full text-center font-semibold">
                                {{ user.name }}
                            </h4>
                        </a>
                        <a
                            v-if="width < 750"
                            class="flex items-center transition transform duration-700 hover:translate-x-2"
                            :href="$app_url + 'recept/novy'">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-gray-400 mr-3"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <li class="list-none">
                                {{ $t('nav.userNav.newRecipe') }}
                            </li>
                        </a>
                        <a
                            class="flex items-center transition transform duration-700 hover:translate-x-2"
                            :href="$app_url + 'receptar'">
                            <i
                                class="fas fa-book-open text-lg text-gray-400 mr-3"></i>
                            <li class="list-none">
                                {{ $t('nav.userNav.recipeBook') }}
                            </li>
                        </a>
                        <a
                            class="flex items-center transition transform duration-700 hover:translate-x-2"
                            :href="$app_url + 'spravy'">
                            <i
                                class="fas fa-comments text-lg text-gray-400 mr-3"></i>
                            <li class="list-none">
                                {{ $t('nav.userNav.messages') }}
                            </li>
                        </a>
                        <a
                            class="flex items-center transition transform duration-700 hover:translate-x-2"
                            :href="$app_url + 'nastavenia'">
                            <i
                                class="fas fa-cogs text-lg text-gray-400 mr-3"></i>
                            <li class="list-none">
                                {{ $t('nav.userNav.settings') }}
                            </li>
                        </a>
                        <a
                            class="flex items-center transition transform duration-700 hover:translate-x-2"
                            v-if="user.role_id === 1"
                            :href="$app_url + 'admin'">
                            <i
                                class="fas fa-id-card text-lg text-gray-400 mr-3"></i>
                            <li>{{ $t('nav.userNav.adminPanel') }}</li>
                        </a>

                        <a
                            class="flex items-center transition transform duration-700 hover:translate-x-2"
                            @click.prevent="logout()"
                            href="">
                            <i
                                class="fas fa-sign-out-alt text-lg text-gray-400 mr-3"></i>
                            <li>{{ $t('nav.userNav.logout') }}</li>
                        </a>
                    </div>
                </div>
            </template>
        </dropdown>
    </div>
</template>

<script>
import dropdown from '../basicComponents/dropdown.vue'
import imageComponent from '../basicComponents/imageComponent.vue'
import avatarComponent from '../basicComponents/avatarComponent.vue'
export default {
    components: {
        dropdown,
        imageComponent,
        avatarComponent,
    },
    props: { user: [Object, Number], width: Number },
    data: function () {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content'),
        }
    },
    methods: {
        logout() {
            axios.post(`${this.$app_url}logout`).then((response) => {
                if (response.status === 200) {
                    window.location.href = '/'
                }
            })
        },
    },
}
</script>
