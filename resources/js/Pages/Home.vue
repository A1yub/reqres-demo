<script setup>
import {ref} from "vue";
import UserGrid from "@/Components/UserGrid.vue";
import NewUserForm from "@/Forms/NewUserForm.vue";
import {ChevronDownIcon, InformationCircleIcon} from "@heroicons/vue/24/outline";
import UserProfileModal from "@/Components/UserProfileModal.vue";

const props = defineProps({
    users: {
        type: Object,
        default: () => [],
    },
})

const paginationConfigs = [
    {page: 1, per_page: 10},
    {page: 2, per_page: 4},
    {page: 100, per_page: 10},
]

let search = ref('');
let userData = ref(props.users);
let currentPageConfig = ref(0);
let loading = ref(false);
let showNewUserForm = ref(false);
let showUserProfile = ref(false);
let userProfile = ref(null);

const onUserCreated = (data) => {
    alert('The new user ID is: ' + data.user_id);
}

const nextPaginationConfig = () => {
    if (loading.value) {
        return;
    }

    if (currentPageConfig.value === paginationConfigs.length - 1) {
        currentPageConfig.value = 0;
    } else {
        currentPageConfig.value = currentPageConfig.value + 1;
    }

    loadUsers();
}

const loadUsers = () => {
    if (loading.value) {
        return;
    }

    loading.value = true;

    axios.get(route('api.users.index'), {
        params: paginationConfigs[currentPageConfig.value]
    }).then((response) => {
        userData.value = response.data;
    }).catch((error) => {
        console.log(error);
    }).finally(() => {
        loading.value = false;
    });
}

const searchUser = () => {
    if (loading.value) {
        return;
    }

    loading.value = true;

    axios.get(route('api.users.show', {id: search.value}))
        .then((response) => {
            userProfile.value = response.data.data;
            showUserProfile.value = true;
        }).catch((error) => {
            alert('User not found');
        }).finally(() => {
        loading.value = false;
    });
}

</script>

<template>
    <div class="relative min-h-screen flex flex-col selection:bg-black selection:text-white bg-gray-100">
        <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl mx-auto">
            <div class="w-full py-10 flex items-centers">
                <h1 class="text-4xl font-bold text-center">
                    REQRES
                    <span class="bg-black text-white px-2">DEMO</span>
                </h1>
            </div>
            <main class="w-full pb-10 space-y-6">
                <div class="space-y-4 md:space-y-0 md:flex items-center md:space-x-4 flex-1 w-full justify-between">
                    <p class="font-bold text-2xl">Users</p>

                    <div class="space-y-4 items-start md:space-y-0 md:flex md:items-center md:space-x-10 ml-auto">
                        <div class="bg-white rounded-md px-4 py-2 shadow flex items-center space-x-4"
                             @click="nextPaginationConfig"
                        >
                            <p class="font-bold text-base">Page: {{ userData.pagination.page }}</p>
                            <div class="h-full">|</div>
                            <p class="font-bold text-base flex-1">Per page: {{ userData.pagination.per_page }}</p>
                            <ChevronDownIcon class="h-6 w-6 text-black animate-bounce" />
                        </div>

                        <form @submit.prevent.stop="searchUser">
                            <input
                                type="text"
                                class="bg-white border border-gray-300 rounded-md shadow px-4 py-2 w-48 placeholder-gray-400 focus:ring-2 focus:ring-black focus:ring-opacity-50"
                                placeholder="Search user by ID"
                                v-model="search"
                            >
                        </form>

                        <button
                            class="py-2 px-4 bg-black hover:bg-gray-800 text-white font-medium rounded-md"
                            @click="showNewUserForm = true"
                        >
                            + Add user
                        </button>
                    </div>
                </div>
                <UserGrid :users="userData.data" />
                <div
                    v-if="!userData.data.length"
                    class="flex items-center bg-white rounded-2xl shadow-lg py-4 px-4"
                >
                    <InformationCircleIcon class="h-6 w-6 text-black mr-2" />
                    <p class="font-bold-text-lg">
                        No users found.
                    </p>
                </div>

                <NewUserForm
                    :open="showNewUserForm"
                    @onCreate="onUserCreated"
                    @close="showNewUserForm = false"
                />

                <UserProfileModal
                    :open="showUserProfile"
                    :user="userProfile"
                    @close="showUserProfile = false"
                />
            </main>
        </div>
    </div>
</template>

<style scoped>

</style>
