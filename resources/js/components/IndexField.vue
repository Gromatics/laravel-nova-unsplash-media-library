<template>
    <div>
        <div v-if="fieldValue " class="flex items-start">
            <div class="bg-gray-50 dark:bg-gray-700 relative aspect-square flex items-center justify-center border-1 overflow-hidden rounded-lg border-gray-200 dark:border-gray-700" style="max-width: 50px;">
                <img :src="fieldValue " class="aspect-square object-scale-down">
            </div>
        </div>
        <span v-else> - </span>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'field'],

    computed: {
        fieldValue() {
            return this.field.displayedAs || this.field.value
        },
    }
}
</script>
