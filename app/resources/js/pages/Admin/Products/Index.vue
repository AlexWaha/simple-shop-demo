<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

interface Product {
    id: number;
    name: string;
    slug: string;
    price: number;
    formatted_price: string;
    stock_quantity: number;
    is_active: boolean;
    is_low_stock: boolean;
}

interface Props {
    products: {
        data: Product[];
        links: any;
        meta: any;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Products', href: '/dashboard/products' },
];

const deleteProduct = (id: number) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(`/dashboard/products/${id}`);
    }
};
</script>

<template>
    <Head title="Product Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Product Management</h1>
                <Link href="/dashboard/products/create">
                    <Button>Add Product</Button>
                </Link>
            </div>

            <Card>
                <CardContent class="pt-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 font-medium">Name</th>
                                    <th class="text-center py-3 font-medium">Stock</th>
                                    <th class="text-right py-3 font-medium">Price</th>
                                    <th class="text-center py-3 font-medium">Status</th>
                                    <th class="text-right py-3 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products.data" :key="product.id" class="border-b">
                                    <td class="py-3 font-medium">{{ product.name }}</td>
                                    <td class="py-3 text-center">
                                        <Badge :variant="product.is_low_stock ? 'destructive' : 'outline'">
                                            {{ product.stock_quantity }}
                                        </Badge>
                                    </td>
                                    <td class="py-3 text-right">${{ product.formatted_price }}</td>
                                    <td class="py-3 text-center">
                                        <Badge :variant="product.is_active ? 'default' : 'secondary'">
                                            {{ product.is_active ? 'Active' : 'Inactive' }}
                                        </Badge>
                                    </td>
                                    <td class="py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <Link :href="`/dashboard/products/${product.id}/edit`">
                                                <Button variant="outline" size="sm">Edit</Button>
                                            </Link>
                                            <Button variant="destructive" size="sm" @click="deleteProduct(product.id)">
                                                Delete
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="products.data.length === 0">
                                    <td colspan="5" class="py-8 text-center text-muted-foreground">No products found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
