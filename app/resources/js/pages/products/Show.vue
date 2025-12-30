<script setup lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

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
    product: Product;
}

const props = defineProps<Props>();
const page = usePage();
const user = computed(() => page.props.auth?.user);

const quantity = ref(1);

const addToCart = () => {
    router.post('/cart', {
        product_id: props.product.id,
        quantity: quantity.value,
    });
};

const getStockStatus = (product: Product) => {
    if (!product.in_stock) return { label: 'Out of Stock', class: 'bg-red-500 text-white hover:bg-red-600' };
    if (product.stock_quantity <= 5) return { label: 'Low Stock', class: 'bg-amber-500 text-white hover:bg-amber-600' };
    return { label: 'In Stock', class: 'bg-green-500 text-white hover:bg-green-600' };
};
</script>

<template>
    <Head :title="product.name" />

    <ShopLayout>
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm">
            <Link href="/" class="text-muted-foreground hover:text-foreground">Products</Link>
            <span class="mx-2 text-muted-foreground">/</span>
            <span>{{ product.name }}</span>
        </nav>

        <div class="grid gap-8 md:grid-cols-2">
            <!-- Product Image -->
            <div class="overflow-hidden rounded-lg border">
                <img
                    :src="product.image || '/no-image.jpg'"
                    :alt="product.name"
                    class="aspect-square w-full object-cover"
                />
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl font-bold">{{ product.name }}</h1>
                    <p class="mt-2 text-3xl font-bold text-primary">
                        ${{ product.formatted_price }}
                    </p>
                </div>

                <p class="text-muted-foreground">{{ product.description }}</p>

                <Badge :class="[getStockStatus(product).class, 'text-sm']">
                    {{ getStockStatus(product).label }}
                </Badge>

                <div v-if="product.in_stock" class="space-y-4">
                    <template v-if="user">
                        <div class="flex items-end gap-4">
                            <div class="space-y-2">
                                <Label for="quantity">Quantity</Label>
                                <Input
                                    id="quantity"
                                    type="number"
                                    v-model.number="quantity"
                                    :min="1"
                                    :max="product.stock_quantity"
                                    class="w-24"
                                />
                            </div>
                            <Button size="lg" @click="addToCart">
                                Add to Cart
                            </Button>
                        </div>
                    </template>
                    <template v-else>
                        <Link href="/login">
                            <Button size="lg">Login to Buy</Button>
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </ShopLayout>
</template>
