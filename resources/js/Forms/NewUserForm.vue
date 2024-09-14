<template>
    <TransitionRoot as="template" :show="open">
        <Dialog class="relative z-10" @close="close">
            <div class="fixed inset-0"/>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        <TransitionChild as="template"
                                         enter="transform transition ease-in-out duration-500 sm:duration-700"
                                         enter-from="translate-x-full" enter-to="translate-x-0"
                                         leave="transform transition ease-in-out duration-500 sm:duration-700"
                                         leave-from="translate-x-0" leave-to="translate-x-full">
                            <DialogPanel class="pointer-events-auto w-screen max-w-md">
                                <form
                                    @submit.prevent.stop="submit"
                                    class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl"
                                >
                                    <div class="h-0 flex-1 overflow-y-auto">
                                        <div class="bg-black px-4 py-6 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <DialogTitle class="text-base font-semibold leading-6 text-white">New
                                                    User
                                                </DialogTitle>
                                                <div class="ml-3 flex h-7 items-center">
                                                    <button
                                                        type="button"
                                                        class="relative rounded-md bg-black text-gray-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                                                        @click="close"
                                                    >
                                                        <span class="absolute -inset-2.5"/>
                                                        <span class="sr-only">Close panel</span>
                                                        <XMarkIcon class="h-6 w-6" aria-hidden="true"/>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-1">
                                                <p class="text-sm text-gray-300">
                                                    Add a new user by filling in the information below.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 flex-col justify-between">
                                            <div class="divide-y divide-gray-200 px-4 sm:px-6">
                                                <div class="space-y-6 pb-5 pt-6">
                                                    <div
                                                        v-if="error"
                                                        class="bg-red-50 text-red-600 font-medium text-base px-4 py-2 flex items-center"
                                                    >
                                                        <ExclamationCircleIcon class="h-5 w-5 inline-block mr-2" aria-hidden="true"/>
                                                        <p>{{ error }}</p>
                                                    </div>
                                                    <div>
                                                        <label
                                                            for="name"
                                                            class="block text-sm font-medium leading-6 text-gray-900"
                                                        >
                                                            Name
                                                        </label>
                                                        <div class="mt-2">
                                                            <input
                                                                v-model="form.name"
                                                                type="text"
                                                                name="name"
                                                                id="name"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label
                                                            for="job"
                                                            class="block text-sm font-medium leading-6 text-gray-900"
                                                        >
                                                            Job title
                                                        </label>
                                                        <div class="mt-2">
                                                            <input
                                                                v-model="form.job"
                                                                type="text"
                                                                name="job"
                                                                id="job"
                                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6"
                                                            />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-shrink-0 justify-end px-4 py-4">
                                        <button
                                            type="button"
                                            class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                                            @click="close"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            class="ml-4 inline-flex justify-center rounded-md bg-black px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black"
                                        >
                                            Create user
                                        </button>
                                    </div>
                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {ref} from 'vue'
import {useForm} from "@inertiajs/vue3";
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import { XMarkIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline'

const emit = defineEmits(['' +
    'close',
    'onCreate',
])

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
});

const error = ref(null);
const saving = ref(false);
const form = useForm({
    name: '',
    job: '',
});

const close = () => {
    form.reset();
    emit('close');
}

const submit = () => {
    if (saving.value) {
        return;
    }

    error.value = null;
    saving.value = true;

    axios.post(route('api.users.store'), form.data())
        .then((response) => {
            emit('onCreate', response.data);
            close();
        }).catch((errorResponse) => {
            error.value = errorResponse.response.data.message;
            console.log(errorResponse);
        }).finally(() => {
            saving.value = false;
        });
}
</script>
