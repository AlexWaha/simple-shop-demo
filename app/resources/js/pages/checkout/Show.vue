<script setup lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Loader2 } from 'lucide-vue-next';

interface Product {
    id: number;
    name: string;
    slug: string;
    price: string;
    formatted_price: string;
    image: string | null;
}

interface CartItem {
    id: number;
    product: Product;
    quantity: number;
    price: string;
    subtotal: number;
    formatted_subtotal: string;
}

interface Cart {
    id: number;
    items: CartItem[];
    total: number;
    formatted_total: string;
    item_count: number;
}

interface Props {
    cart: Cart;
}

defineProps<Props>();

const processing = ref(false);

const placeOrder = () => {
    processing.value = true;
    router.post('/checkout');
};
</script>

<template>
    <Head title="Checkout" />

    <ShopLayout>
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm">
            <Link href="/cart" class="text-muted-foreground hover:text-foreground">Cart</Link>
            <span class="mx-2 text-muted-foreground">/</span>
            <span>Checkout</span>
        </nav>

        <h1 class="text-3xl font-bold mb-6">Checkout</h1>

        <div class="grid gap-8 lg:grid-cols-2">
            <!-- Order Items -->
            <Card>
                <CardHeader>
                    <CardTitle>Order Items</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div
                        v-for="item in cart.items"
                        :key="item.id"
                        class="flex items-center justify-between"
                    >
                        <div class="flex items-center gap-3">
                            <img
                                :src="item.product.image || '/no-image.jpg'"
                                :alt="item.product.name"
                                class="w-12 h-12 object-cover rounded"
                            />
                            <div>
                                <p class="font-medium">{{ item.product.name }}</p>
                                <p class="text-sm text-muted-foreground">
                                    Qty: {{ item.quantity }} x ${{ item.price }}
                                </p>
                            </div>
                        </div>
                        <p class="font-semibold">${{ item.formatted_subtotal }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Order Summary -->
            <Card>
                <CardHeader>
                    <CardTitle>Order Summary</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Subtotal</span>
                        <span>${{ cart.formatted_total }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Shipping</span>
                        <span class="text-green-600">Free</span>
                    </div>
                    <Separator />
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span>${{ cart.formatted_total }}</span>
                    </div>
                </CardContent>
                <CardFooter>
                    <Button
                        class="w-full"
                        size="lg"
                        :disabled="processing"
                        @click="placeOrder"
                    >
                        <Loader2 v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                        {{ processing ? 'Processing...' : 'Place Order' }}
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </ShopLayout>
</template>
