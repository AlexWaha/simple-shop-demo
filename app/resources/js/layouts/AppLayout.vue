<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import ShopLayout from '@/layouts/ShopLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const isAdmin = computed(() => page.props.auth?.user?.is_admin ?? false);
</script>

<template>
    <AppSidebarLayout v-if="isAdmin" :breadcrumbs="breadcrumbs">
        <slot />
    </AppSidebarLayout>
    <ShopLayout v-else>
        <slot />
    </ShopLayout>
</template>
