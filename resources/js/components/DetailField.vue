<template>
    <PanelItem :index="index" :field="field">
        <template v-slot:value>

            <div v-if="field.value" class="h-[90px] w-[90px]  flex items-start justify-center">
                <div class="relative w-full">
                    <div class="bg-gray-50 dark:bg-gray-700 relative aspect-square flex items-center justify-center border-2  overflow-hidden rounded-lg border-gray-200 dark:border-gray-700">
                        <img :src="field.value" class="aspect-square object-scale-down">
                    </div>
                </div>
            </div>
            <span v-else> - </span>
        </template>
    </PanelItem>
</template>

<script>
export default {
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
}
</script>
<script setup lang="ts">
</script>
