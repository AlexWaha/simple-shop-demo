<script setup lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Head, Link } from '@inertiajs/vue3';

interface OrderItem {
    id: number;
    product_name: string;
    quantity: number;
    unit_price: string;
    subtotal: string;
    formatted_subtotal: string;
}

interface Order {
    id: number;
    order_number: string;
    status: 'pending' | 'completed' | 'cancelled';
    total_amount: string;
    formatted_total: string;
    items: OrderItem[];
    created_at: string;
    formatted_date: string;
}

interface Props {
    order: Order;
}

defineProps<Props>();

const getStatusClass = (status: Order['status']) => {
    switch (status) {
        case 'completed':
            return 'bg-green-500 text-white hover:bg-green-600';
        case 'pending':
            return 'bg-amber-500 text-white hover:bg-amber-600';
        case 'cancelled':
            return 'bg-red-500 text-white hover:bg-red-600';
        default:
            return '';
    }
};

const getStatusLabel = (status: Order['status']) => {
    switch (status) {
        case 'completed':
            return 'Completed';
        case 'pending':
            return 'Pending';
        case 'cancelled':
            return 'Cancelled';
        default:
            return status;
    }
};
</script>

<template>
    <Head :title="`Order #${order.order_number}`" />

    <ShopLayout>
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm">
            <Link href="/orders" class="text-muted-foreground hover:text-foreground">Orders</Link>
            <span class="mx-2 text-muted-foreground">/</span>
            <span>#{{ order.order_number }}</span>
        </nav>

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Order #{{ order.order_number }}</h1>
            <Badge :class="[getStatusClass(order.status), 'text-sm']">
                {{ getStatusLabel(order.status) }}
            </Badge>
        </div>

        <Card class="mb-6">
            <CardHeader>
                <CardTitle>Order Details</CardTitle>
                <p class="text-sm text-muted-foreground">
                    Placed on {{ order.formatted_date }}
                </p>
            </CardHeader>
            <CardContent>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 font-medium">Product</th>
                                <th class="text-center py-3 font-medium">Quantity</th>
                                <th class="text-right py-3 font-medium">Price</th>
                                <th class="text-right py-3 font-medium">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in order.items" :key="item.id" class="border-b">
                                <td class="py-3 font-medium">{{ item.product_name }}</td>
                                <td class="py-3 text-center">{{ item.quantity }}</td>
                                <td class="py-3 text-right">${{ item.unit_price }}</td>
                                <td class="py-3 text-right">${{ item.formatted_subtotal }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Separator class="my-4" />

                <div class="flex justify-end">
                    <div class="text-right">
                        <p class="text-sm text-muted-foreground">Order Total</p>
                        <p class="text-2xl font-bold">${{ order.formatted_total }}</p>
                    </div>
                </div>
            </CardContent>
        </Card>

        <div class="flex gap-4">
            <Link href="/orders">
                <Button variant="outline">Back to Orders</Button>
            </Link>
            <Link href="/">
                <Button>Continue Shopping</Button>
            </Link>
        </div>
    </ShopLayout>
</template>
