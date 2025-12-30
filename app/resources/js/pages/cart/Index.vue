<script setup lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { Head, Link, router } from '@inertiajs/vue3';
import { Trash2, ShoppingBag, Minus, Plus } from 'lucide-vue-next';

interface Product {
    id: number;
    name: string;
    slug: string;
    price: string;
    formatted_price: string;
    stock_quantity: number;
    in_stock: boolean;
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
    cart: Cart | null;
}

defineProps<Props>();

const updateQuantity = (itemId: number, quantity: number) => {
    if (quantity < 1) return;
    router.patch(`/cart/${itemId}`, { quantity }, { preserveScroll: true });
};

const removeItem = (itemId: number) => {
    router.delete(`/cart/${itemId}`, { preserveScroll: true });
};
</script>

<template>
    <Head title="Shopping Cart" />

    <ShopLayout>
        <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

        <!-- Empty Cart -->
        <div v-if="!cart || !cart.items || cart.items.length === 0" class="text-center py-16">
            <ShoppingBag class="h-16 w-16 mx-auto text-muted-foreground mb-4" />
            <h2 class="text-xl font-semibold mb-2">Your cart is empty</h2>
            <p class="text-muted-foreground mb-6">Looks like you haven't added anything to your cart yet.</p>
            <Link href="/">
                <Button size="lg">Continue Shopping</Button>
            </Link>
        </div>

        <!-- Cart with Items -->
        <div v-else class="grid gap-8 lg:grid-cols-3">
            <!-- Cart Items Table -->
            <div class="lg:col-span-2">
                <Card>
                    <CardContent class="pt-6">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 font-medium">Product</th>
                                    <th class="text-center py-3 font-medium w-32">Quantity</th>
                                    <th class="text-right py-3 font-medium w-24">Price</th>
                                    <th class="text-right py-3 font-medium w-24">Total</th>
                                    <th class="w-12"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in cart.items" :key="item.id" class="border-b">
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <Link :href="`/products/${item.product.slug}`" class="shrink-0">
                                                <img
                                                    :src="item.product.image || '/no-image.jpg'"
                                                    :alt="item.product.name"
                                                    class="w-16 h-16 object-cover rounded"
                                                />
                                            </Link>
                                            <Link :href="`/products/${item.product.slug}`" class="font-medium hover:underline">
                                                {{ item.product.name }}
                                            </Link>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center justify-center gap-1">
                                            <Button
                                                variant="outline"
                                                size="icon"
                                                class="h-8 w-8"
                                                :disabled="item.quantity <= 1"
                                                @click="updateQuantity(item.id, item.quantity - 1)"
                                            >
                                                <Minus class="h-4 w-4" />
                                            </Button>
                                            <Input
                                                type="text"
                                                :model-value="item.quantity"
                                                class="h-8 w-16 text-center"
                                                @blur="(e: FocusEvent) => {
                                                    const val = parseInt((e.target as HTMLInputElement).value);
                                                    if (val > 0 && val !== item.quantity) updateQuantity(item.id, val);
                                                }"
                                                @keyup.enter="(e: KeyboardEvent) => {
                                                    const val = parseInt((e.target as HTMLInputElement).value);
                                                    if (val > 0 && val !== item.quantity) updateQuantity(item.id, val);
                                                }"
                                            />
                                            <Button
                                                variant="outline"
                                                size="icon"
                                                class="h-8 w-8"
                                                @click="updateQuantity(item.id, item.quantity + 1)"
                                            >
                                                <Plus class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right">
                                        ${{ item.price }}
                                    </td>
                                    <td class="py-4 text-right font-semibold">
                                        ${{ item.formatted_subtotal }}
                                    </td>
                                    <td class="py-4 text-center">
                                        <Button variant="ghost" size="icon" @click="removeItem(item.id)">
                                            <Trash2 class="h-4 w-4 text-destructive" />
                                        </Button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </CardContent>
                </Card>
            </div>

            <!-- Order Summary -->
            <div>
                <Card>
                    <CardHeader>
                        <CardTitle>Order Summary</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Items ({{ cart.item_count }})</span>
                                <span>${{ cart.formatted_total }}</span>
                            </div>
                            <Separator />
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span>${{ cart.formatted_total }}</span>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Link href="/checkout" class="w-full">
                            <Button class="w-full" size="lg">Proceed to Checkout</Button>
                        </Link>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </ShopLayout>
</template>
