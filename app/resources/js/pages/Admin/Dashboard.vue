<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface Stats {
    total_products: number;
    active_products: number;
    low_stock_products: number;
    total_orders: number;
    pending_orders: number;
    completed_orders: number;
    total_users: number;
    today_orders: number;
    today_revenue: number;
    month_revenue: number;
}

interface RecentOrder {
    id: number;
    order_number: string;
    user_name: string;
    status: string;
    total: string;
    created_at: string;
}

interface LowStockProduct {
    id: number;
    name: string;
    stock_quantity: number;
}

interface DailySale {
    date: string;
    orders_count: number;
    revenue: string;
}

interface Props {
    stats: Stats;
    recentOrders: RecentOrder[];
    lowStockProducts: LowStockProduct[];
    dailySales: DailySale[];
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
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
    <Head title="Admin Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <h1 class="text-2xl font-bold">Admin Dashboard</h1>

            <!-- Stats Grid -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader class="pb-2">
                        <p class="text-sm text-muted-foreground">Total Products</p>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold">{{ stats.total_products }}</p>
                        <p class="text-sm text-muted-foreground">{{ stats.active_products }} active</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <p class="text-sm text-muted-foreground">Low Stock Alert</p>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold text-amber-500">{{ stats.low_stock_products }}</p>
                        <p class="text-sm text-muted-foreground">products need restock</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <p class="text-sm text-muted-foreground">Total Orders</p>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold">{{ stats.total_orders }}</p>
                        <p class="text-sm text-muted-foreground">{{ stats.pending_orders }} pending</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <p class="text-sm text-muted-foreground">Today's Revenue</p>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold text-green-600">${{ stats.today_revenue.toFixed(2) }}</p>
                        <p class="text-sm text-muted-foreground">{{ stats.today_orders }} orders today</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Month Revenue -->
            <Card>
                <CardHeader>
                    <CardTitle>Monthly Revenue</CardTitle>
                </CardHeader>
                <CardContent>
                    <p class="text-3xl font-bold text-green-600">${{ stats.month_revenue.toFixed(2) }}</p>
                    <p class="text-sm text-muted-foreground">Total revenue this month from completed orders</p>
                </CardContent>
            </Card>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Recent Orders -->
                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>Recent Orders</CardTitle>
                            <Link href="/dashboard/orders">
                                <Button variant="outline" size="sm">View All</Button>
                            </Link>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2 font-medium">Order</th>
                                        <th class="text-left py-2 font-medium">Customer</th>
                                        <th class="text-left py-2 font-medium">Status</th>
                                        <th class="text-right py-2 font-medium">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="order in recentOrders" :key="order.id" class="border-b">
                                        <td class="py-2 font-medium">#{{ order.order_number }}</td>
                                        <td class="py-2">{{ order.user_name }}</td>
                                        <td class="py-2">
                                            <Badge :class="getStatusClass(order.status)">{{ order.status }}</Badge>
                                        </td>
                                        <td class="py-2 text-right">${{ order.total }}</td>
                                    </tr>
                                    <tr v-if="recentOrders.length === 0">
                                        <td colspan="4" class="py-4 text-center text-muted-foreground">No orders yet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>

                <!-- Low Stock Products -->
                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>Low Stock Products</CardTitle>
                            <Link href="/dashboard/products">
                                <Button variant="outline" size="sm">View All</Button>
                            </Link>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2 font-medium">Product</th>
                                        <th class="text-right py-2 font-medium">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in lowStockProducts" :key="product.id" class="border-b">
                                        <td class="py-2 font-medium">{{ product.name }}</td>
                                        <td class="py-2 text-right">
                                            <Badge variant="destructive">{{ product.stock_quantity }}</Badge>
                                        </td>
                                    </tr>
                                    <tr v-if="lowStockProducts.length === 0">
                                        <td colspan="2" class="py-4 text-center text-muted-foreground">No low stock products</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Daily Sales Report -->
            <Card>
                <CardHeader>
                    <CardTitle>Daily Sales (Last 7 Days)</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2 font-medium">Date</th>
                                    <th class="text-center py-2 font-medium">Orders</th>
                                    <th class="text-right py-2 font-medium">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="day in dailySales" :key="day.date" class="border-b">
                                    <td class="py-2 font-medium">{{ day.date }}</td>
                                    <td class="py-2 text-center">{{ day.orders_count }}</td>
                                    <td class="py-2 text-right">${{ day.revenue }}</td>
                                </tr>
                                <tr v-if="dailySales.length === 0">
                                    <td colspan="3" class="py-4 text-center text-muted-foreground">No sales data available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
