<template>
    <div class="pt-20 px-4">
        <stats-cards />
        <div class="flex items-start gap-2 justify-around flex-col xl:flex-row">
            <div class="px-1 w-full">
                <recipes-panel />
            </div>
            <div class="px-1 w-full">
                <users-panel :authId="authId" />
            </div>
        </div>
        <div class="px-1 w-full">
            <h2
                class="inline-block bg-blue-500 shadow-lg rounded-md m-2 py-1 px-8 border-b-4 border-blue-600 text-white font-medium">
                {{ $tc('adminPanel.categories', 2) }}
            </h2>
            <categories />
        </div>
        <div class="w-full pb-10">
            <h2
                class="inline-block bg-blue-500 shadow-lg rounded-md m-2 py-1 px-8 border-b-4 border-blue-600 text-white font-medium">
                Dev Todo
            </h2>
            <dev-todo />
        </div>
    </div>
</template>

<script>
import categories from './categories.vue'
import statsCards from './statsCards.vue'
import recipesPanel from './recipesPanel.vue'
import usersPanel from './usersPanel.vue'
import devTodo from '../todo/devTodo.vue'
import Calendar from '../basicComponents/calendar.vue'

export default {
    components: {
        statsCards,
        recipesPanel,
        usersPanel,
        devTodo,
        Calendar,
        categories,
    },
    props: ['auth-id'],
    data: function () {
        return {
            stats: [],
        }
    },
}
</script>
