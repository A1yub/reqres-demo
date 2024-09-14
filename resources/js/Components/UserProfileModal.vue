<template>
    <TransitionRoot as="template" :show="open">
        <Dialog class="relative z-10" @close="emit('close')">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300"
                                     enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                     leave-from="opacity-100 translate-y-0 sm:scale-100"
                                     leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                            <div v-if="user">
                                <div class="flex flex-1 flex-col p-8">
                                    <img
                                        v-if="user.avatar"
                                        class="mx-auto h-32 w-32 flex-shrink-0 rounded-full aspect-square"
                                        :src="user.avatar"
                                        alt=""
                                    />
                                    <h3 class="mt-6 text-sm font-medium text-gray-900 text-center">ID: {{ user.id }}</h3>
                                    <h3 class="mt-6 text-sm font-medium text-gray-900 text-center">{{ user.name }}</h3>
                                    <h3 class="mt-6 text-sm font-medium text-gray-900 text-center">{{ user.email }}</h3>
                                    <dl class="mt-1 flex flex-grow flex-col justify-between">
                                        <dt class="sr-only">Job</dt>
                                        <dd class="text-sm text-gray-500">{{ user.job }}</dd>
                                        <dt class="sr-only">Role</dt>
                                    </dl>
                                </div>
                                <div class="mt-5 sm:mt-6">
                                    <button
                                        type="button"
                                        class="inline-flex w-full justify-center rounded-md bg-black px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black"
                                        @click="emit('close')"
                                    >
                                        Close
                                    </button>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from '@headlessui/vue'

const emit = defineEmits(['close'])

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
    user: {
        type: Object,
        default: null,
    },
})
</script>
