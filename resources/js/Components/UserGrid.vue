<template>
    <ul
        role="list"
        class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
        v-if="users.length"
    >
        <li v-for="person in users" :key="person.email" class="col-span-1 flex flex-col divide-y divide-gray-200 rounded-lg bg-white text-center shadow">
            <div class="flex flex-1 flex-col p-8">
                <img class="mx-auto h-32 w-32 flex-shrink-0 rounded-full" :src="person.avatar" alt="" />
                <h3 class="mt-6 text-sm font-medium text-gray-900">{{ person.name }}</h3>
                <h3 class="mt-6 text-sm font-medium text-gray-900">{{ person.email }}</h3>
                <dl class="mt-1 flex flex-grow flex-col justify-between">
                    <dt class="sr-only">Job</dt>
                    <dd class="text-sm text-gray-500">{{ person.job }}</dd>
                    <dt class="sr-only">Role</dt>
                </dl>
            </div>
        </li>
    </ul>
</template>

<script setup>
const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
});
</script>
