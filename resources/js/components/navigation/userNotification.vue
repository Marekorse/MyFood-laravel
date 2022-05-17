<template>
    <div class="flex items-center py-2 text-xs">
        <div
            @click="markAsRead(notification.id)"
            :class="{
                'border-l-4 border-yellow-500': !notification.read_at,
            }"
            class="flex items-center w-full justify-start px-3">
            <div>
                <i
                    v-if="notification.data.icon === 'info'"
                    class="fas fa-info-circle text-blue-300 text-4xl p-0 m-0"></i>
                <avatar-component
                    v-if="notification.data.icon === 'avatar'"
                    :image="notification.data.user.avatar"
                    :size="40"
                    thumb />
            </div>
            <div class="flex flex-col justify-start ml-2">
                <a v-if="notification.data.user" :href="notification.data.link">
                    <li class="list none text-base text-blue-400 font-semibold">
                        {{ stringLimiter(notification.data.user.name) }}
                    </li>
                </a>
                <li
                    v-else
                    class="list none text-base text-blue-400 font-semibold">
                    {{ $app_name }}
                </li>
                <li class="list none text-xs font-semibold text-gray-400">
                    {{ notification.data.message }}
                </li>
            </div>
        </div>
        <span @click="$emit('delete', notification)">
            <i
                class="fas fa-times text-red-400 text-sm m-1 p-1 cursor-pointer"></i>
        </span>
    </div>
</template>

<script>
import avatarComponent from '../basicComponents/avatarComponent.vue'
export default {
    components: {
        avatarComponent,
    },
    props: { notification: Object },
    methods: {
        markAsRead(id) {
            axios.put(`${this.$app_url}notification?id=${id}`).then((res) => {
                this.notification.read_at = res.data
            })
        },
    },
}
</script>
