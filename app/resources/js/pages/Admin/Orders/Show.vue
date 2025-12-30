<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

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
    status: string;
    total: number;
    formatted_total: string;
    created_at: string;
    user: {
        name: string;
        email: string;
    };
    items: OrderItem[];
}

interface Props {
    order: Order;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Orders', href: '/dashboard/orders' },
    { title: `#${props.order.order_number}`, href: `/dashboard/orders/${props.order.id}` },
];

const selectedStatus = ref(props.order.status);

const getStatusClass = (status: string) => {
    switch (status) {
        case 'completed': return 'bg-green-500 text-white';
        case 'pending': return 'bg-amber-500 text-white';
        case 'cancelled': return 'bg-red-500 text-white';
        default: return '';
    }
};

const updateStatus = () => {
    router.patch(`/dashboard/orders/${props.order.id}/status`, { status: selectedStatus.value });
};
</script>

<template>
    <Head :title="`Order #${order.order_number}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Order #{{ order.order_number }}</h1>
                <Badge :class="[getStatusClass(order.status), 'text-sm px-3 py-1']">{{ order.status }}</Badge>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Customer Info -->
                <Card>
                    <CardHeader>
                        <CardTitle>Customer Information</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-2">
                        <div class="flex gap-2">
                            <span class="text-muted-foreground">Name:</span>
                            <span class="font-medium">{{ order.user.name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="text-muted-foreground">Email:</span>
                            <span class="font-medium">{{ order.user.email }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="text-muted-foreground">Order Date:</span>
                            <span class="font-medium">{{ order.created_at }}</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Status Update -->
                <Card>
                    <CardHeader>
                        <CardTitle>Update Status</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex gap-2">
                            <select
                                v-model="selectedStatus"
                                class="flex-1 h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                            >
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <Button @click="updateStatus">Update</Button>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Order Items -->
            <Card>
                <CardHeader>
                    <CardTitle>Order Items</CardTitle>
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
                                    <td class="py-3 text-right">${{ item.price }}</td>
                                    <td class="py-3 text-right">${{ item.total }}</td>
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

            <div>
                <Link href="/dashboard/orders">
                    <Button variant="outline">Back to Orders</Button>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
