<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface Order {
    id: number;
    order_number: string;
    user_name: string;
    user_email: string;
    status: string;
    total: number;
    formatted_total: string;
    items_count: number;
    created_at: string;
}

interface Props {
    orders: {
        data: Order[];
        links: any;
        meta: any;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Orders', href: '/dashboard/orders' },
];

const getStatusClass = (status: string) => {
    switch (status) {
        case 'completed': return 'bg-green-500 text-white';
        case 'pending': return 'bg-amber-500 text-white';
        case 'cancelled': return 'bg-red-500 text-white';
        default: return '';
    }
};
</script>

<template>
    <Head title="Order Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <h1 class="text-2xl font-bold">Order Management</h1>

            <Card>
                <CardContent class="pt-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 font-medium">Order</th>
                                    <th class="text-left py-3 font-medium">Customer</th>
                                    <th class="text-center py-3 font-medium">Items</th>
                                    <th class="text-center py-3 font-medium">Status</th>
                                    <th class="text-right py-3 font-medium">Total</th>
                                    <th class="text-left py-3 font-medium">Date</th>
                                    <th class="text-right py-3 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="order in orders.data" :key="order.id" class="border-b">
                                    <td class="py-3 font-medium">#{{ order.order_number }}</td>
                                    <td class="py-3">
                                        <div>{{ order.user_name }}</div>
                                        <div class="text-sm text-muted-foreground">{{ order.user_email }}</div>
                                    </td>
                                    <td class="py-3 text-center">{{ order.items_count }}</td>
                                    <td class="py-3 text-center">
                                        <Badge :class="getStatusClass(order.status)">{{ order.status }}</Badge>
                                    </td>
                                    <td class="py-3 text-right">${{ order.formatted_total }}</td>
                                    <td class="py-3 text-muted-foreground">{{ order.created_at }}</td>
                                    <td class="py-3 text-right">
                                        <Link :href="`/dashboard/orders/${order.id}`">
                                            <Button variant="outline" size="sm">View</Button>
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="orders.data.length === 0">
                                    <td colspan="7" class="py-8 text-center text-muted-foreground">No orders found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
