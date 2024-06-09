<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field>

            <div v-if="value" class="flex items-center mb-5">
                <div class="bg-gray-50 dark:bg-gray-700 relative aspect-square flex items-center justify-center border-1 overflow-hidden rounded-lg border-gray-200 dark:border-gray-700" style="max-width: 150px;">
                    <img :src="value" class="aspect-square object-scale-down">
                </div>
                <div @click="value = null"
                     class="ml-3 border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:[&amp;:not(:disabled)]:bg-primary-400 hover:[&amp;:not(:disabled)]:border-primary-400 text-white dark:text-gray-900">
                    Remove image
                </div>
            </div>
            <span v-else> - </span>

            <div v-if="!this.field.unsplashClientId" class="text-red-500">
                <strong>No Unsplash Client ID set</strong>
                <br>- Add <small class="inline">
                <pre class="inline">'unsplash_client_id' => env('UNSPLASH_CLIENT_ID')</pre>
            </small> to config/services.php
                <br>- Add <small class="inline">
                <pre class="inline">UNSPLASH_CLIENT_ID={YOUR_CLIENT_ID}</pre>
            </small> to .env
            </div>

            <div v-else class="flex items-center max-w-sm">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <input v-model="query" type="text" id="simple-search" class="w-full form-control form-input form-control-bordered" placeholder="Search for images on Unsplash"/>
                </div>
                <div @click="searchUnsplash"
                     class="ml-3 border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:[&amp;:not(:disabled)]:bg-primary-400 hover:[&amp;:not(:disabled)]:border-primary-400 text-white dark:text-gray-900">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                    <span class="sr-only">Search</span>
                </div>
            </div>

            <div v-if="unsplashResponse && unsplashResponse.errors" class="text-red-500">
                <strong>Unsplash Errors:</strong><br>
                <div class="italic" v-for="error in unsplashResponse.errors"> - {{ error }}</div>
            </div>
            <div v-if="unsplashResponse && unsplashResponse.results">
                <div v-if="unsplashResponse.results.length > 0" class="py-3">
                    Select an image to add to your media library.
                </div>

                <div v-else class="py-3 text-red-500">
                    No results found
                </div>

                <div class="grid grid-cols-4 gap-x-6 gap-y-2 mb-5">
                    <div v-for="(result) in unsplashResponse.results" class="h-full flex items-start justify-center">
                        <div class="relative w-full">
                            <div @click="value = result.urls.raw" class="bg-gray-50 dark:bg-gray-700 relative aspect-square flex items-center justify-center border-2  overflow-hidden rounded-lg cursor-pointer"
                                 :class="value === result.urls.raw ? 'border-primary-500' : 'border-gray-200 dark:border-gray-700'">
                                <img :src="result.urls.thumb" class="aspect-square object-scale-down" :title="result.description ? result.description : result.alt_description"></div>
                            <p class="font-semibold text-xs mt-1">{{ truncate(result.description ? result.description : result.alt_description, 80) }}</p></div>
                    </div>
                </div>

                <div v-if="value && unsplashResponse.results.length > 0" @click="value = null"
                     class="my-3 border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:[&amp;:not(:disabled)]:bg-primary-400 hover:[&amp;:not(:disabled)]:border-primary-400 text-white dark:text-gray-900">
                    Deselect image
                </div>

                <input
                    :id="field.attribute"
                    type="hidden"
                    :class="errorClasses"
                    :placeholder="field.name"
                    v-model="value"
                />
            </div>
        </template>
    </DefaultField>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import ax from 'axios'

export default {
    mixins: [FormField, HandlesValidationErrors],

    data() {
        return {
            query: '',
            unsplashResponse: null
        }
    },

    props: ['resourceName', 'resourceId', 'field'],


    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.fieldAttribute, this.value || '')
        },

        searchUnsplash() {
            ax.get("https://api.unsplash.com/search/photos", {
                params: {
                    'page': 1,
                    'query': this.query,
                    'client_id': this.field.unsplashClientId
                }
            }).then((response) => {
                this.unsplashResponse = response.data
            })
        },

        truncate(str, n) {
            return (str.length > n) ? str.slice(0, n - 1) + '...' : str;
        }


    },
}
</script>
