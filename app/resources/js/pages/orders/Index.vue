<script setup lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Head, Link } from '@inertiajs/vue3';
import { ClipboardList, Package } from 'lucide-vue-next';

interface OrderItem {
    id: number;
    product_name: string;
    quantity: number;
    price: string;
    total: string;
}

interface Order {
    id: number;
    order_number: string;
    status: 'pending' | 'completed' | 'cancelled';
    total: string;
    formatted_total: string;
    item_count: number;
    items: OrderItem[];
    created_at: string;
    formatted_date: string;
}

interface Props {
    orders: {
        data: Order[];
        links: any;
        meta: any;
    };
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
    <Head title="My Orders" />

    <ShopLayout>
        <h1 class="text-3xl font-bold mb-6">My Orders</h1>

        <!-- Empty State -->
        <div v-if="!orders.data || orders.data.length === 0" class="text-center py-16">
            <ClipboardList class="h-16 w-16 mx-auto text-muted-foreground mb-4" />
            <h2 class="text-xl font-semibold mb-2">No orders yet</h2>
            <p class="text-muted-foreground mb-6">You haven't placed any orders yet.</p>
            <Link href="/">
                <Button size="lg">Start Shopping</Button>
            </Link>
        </div>

        <!-- Orders List -->
        <div v-else class="space-y-4">
            <Card v-for="order in orders.data" :key="order.order_number">
                <CardHeader class="pb-3">
                    <div class="flex items-center justify-between">
                        <CardTitle class="text-lg">
                            Order #{{ order.order_number }}
                        </CardTitle>
                        <Badge :class="getStatusClass(order.status)">
                            {{ getStatusLabel(order.status) }}
                        </Badge>
                    </div>
                    <p class="text-sm text-muted-foreground">{{ order.formatted_date }}</p>
                </CardHeader>
                <CardContent>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <Package class="h-4 w-4" />
                            <span>{{ order.item_count }} item(s)</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-semibold">${{ order.formatted_total }}</span>
                            <Link :href="`/orders/${order.order_number}`">
                                <Button variant="outline" size="sm">View Details</Button>
                            </Link>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </ShopLayout>
</template>
