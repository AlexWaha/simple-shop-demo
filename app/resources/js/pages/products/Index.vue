<script setup lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Product {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    price: string;
    formatted_price: string;
    stock_quantity: number;
    in_stock: boolean;
    image: string | null;
}

interface Props {
    products: {
        data: Product[];
        links: any;
        meta: any;
    };
}

defineProps<Props>();

const page = usePage();
const user = computed(() => page.props.auth?.user);

const addToCart = (productId: number) => {
    router.post('/cart', {
        product_id: productId,
        quantity: 1,
    });
};

const getStockStatus = (product: Product) => {
    if (!product.in_stock) return { label: 'Out of Stock', class: 'bg-red-500 text-white hover:bg-red-600' };
    if (product.stock_quantity <= 5) return { label: 'Low Stock', class: 'bg-amber-500 text-white hover:bg-amber-600' };
    return { label: 'In Stock', class: 'bg-green-500 text-white hover:bg-green-600' };
};
</script>

<template>
    <Head title="Products" />

    <ShopLayout>
        <h1 class="text-3xl font-bold mb-6">Our Products</h1>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <Card v-for="product in products.data" :key="product.id" class="overflow-hidden">
                <Link :href="`/products/${product.slug}`">
                    <img
                        :src="product.image || '/no-image.jpg'"
                        :alt="product.name"
                        class="aspect-square w-full object-cover"
                    />
                </Link>
                <CardHeader class="p-4">
                    <Link :href="`/products/${product.slug}`">
                        <CardTitle class="line-clamp-1 text-lg">{{ product.name }}</CardTitle>
                    </Link>
                    <p class="text-sm text-muted-foreground line-clamp-2">{{ product.description }}</p>
                </CardHeader>
                <CardContent class="p-4 pt-0">
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-bold">${{ product.formatted_price }}</span>
                        <Badge :class="getStockStatus(product).class">
                            {{ getStockStatus(product).label }}
                        </Badge>
                    </div>
                </CardContent>
                <CardFooter class="p-4 pt-0 gap-2">
                    <Link :href="`/products/${product.slug}`" class="flex-1">
                        <Button variant="outline" class="w-full">View</Button>
                    </Link>
                    <Button
                        v-if="user && product.in_stock"
                        @click="addToCart(product.id)"
                        class="flex-1"
                    >
                        Add to Cart
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </ShopLayout>
</template>
