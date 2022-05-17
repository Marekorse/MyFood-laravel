<template>
    <div>
        <ul class="flex gap-1 items-center">
            <a
                v-if="!user || (user && width > 750)"
                :href="$app_url + 'recept/novy'"
                type="button"
                class="">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 md:w-10 md:h-10 relative gradient-green-blue text-white p-1.5 md:mr-2 font-bold rounded-full shadow-inner flex items-center custom z-10"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </a>
            <user-notifications
                :width="width"
                v-if="user"
                :user="user"></user-notifications>
            <user-navigation :width="width" :user="user"> </user-navigation>
        </ul>
    </div>
</template>

<script>
import userNotifications from './userNotifications.vue'
import UserNavigation from './userNavigation.vue'
export default {
    components: {
        UserNavigation,
        userNotifications,
    },

    props: { user: [Object, Number], width: Number },
    data: function () {
        return {}
    },
}
</script>
