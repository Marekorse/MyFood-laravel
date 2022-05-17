<template>
    <div class="relative rounded-lg px-1 py-6">
        <h3 class="flex items-center absolute left-2 -top-4">
            <slot name="icon"> </slot>
        </h3>
        <ul class="flex flex-wrap gap-1 list-none cursor-pointer">
            <slot name="labels">
                <span
                    class="px-5 py-1 text-sm font-semibold text-gray-600 bg-gray-200 rounded-md">
                    {{ $t('leaderboard.weDontHaveData') }}
                </span>
            </slot>
        </ul>
    </div>
</template>
