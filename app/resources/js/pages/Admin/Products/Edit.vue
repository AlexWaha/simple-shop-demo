<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ImagePlus, X } from 'lucide-vue-next';
import { ref } from 'vue';

interface Product {
    id: number;
    name: string;
    slug: string;
    description: string;
    image: string | null;
    price: number;
    stock_quantity: number;
    is_active: boolean;
}

interface Props {
    product: Product;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Products', href: '/dashboard/products' },
    { title: 'Edit', href: `/dashboard/products/${props.product.id}/edit` },
];

const form = ref({
    name: props.product.name,
    description: props.product.description || '',
    price: props.product.price.toString(),
    stock_quantity: props.product.stock_quantity.toString(),
    is_active: props.product.is_active,
});

const imageFile = ref<File | null>(null);
const imagePreview = ref<string | null>(props.product.image);
const existingImage = ref<string | null>(props.product.image);

const errors = ref<Record<string, string>>({});
const processing = ref(false);

const handleImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        imageFile.value = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const removeImage = () => {
    imageFile.value = null;
    imagePreview.value = null;
};

const submit = () => {
    processing.value = true;

    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('name', form.value.name);
    formData.append('description', form.value.description);
    formData.append('price', form.value.price);
    formData.append('stock_quantity', form.value.stock_quantity);
    formData.append('is_active', form.value.is_active ? '1' : '0');

    if (imageFile.value) {
        formData.append('image', imageFile.value);
    }

    router.post(`/dashboard/products/${props.product.id}`, formData, {
        onError: (errs) => { errors.value = errs; },
        onFinish: () => { processing.value = false; },
    });
};
</script>

<template>
    <Head :title="`Edit ${product.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <h1 class="text-2xl font-bold">Edit Product</h1>

            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Product Details</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="space-y-2">
                        <Label for="name">Name</Label>
                        <Input id="name" v-model="form.name" type="text" placeholder="Product name" />
                        <p v-if="errors.name" class="text-sm text-destructive">{{ errors.name }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label for="description">Description</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="flex min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                            rows="4"
                            placeholder="Product description"
                        ></textarea>
                        <p v-if="errors.description" class="text-sm text-destructive">{{ errors.description }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label>Product Image</Label>
                        <div v-if="imagePreview" class="relative inline-block">
                            <img :src="imagePreview" alt="Preview" class="h-32 w-32 rounded-md object-cover border" />
                            <button
                                type="button"
                                @click="removeImage"
                                class="absolute -top-2 -right-2 rounded-full bg-destructive p-1 text-destructive-foreground hover:bg-destructive/90"
                            >
                                <X class="h-4 w-4" />
                            </button>
                        </div>
                        <div v-else class="flex items-center gap-4">
                            <label
                                class="flex h-32 w-32 cursor-pointer items-center justify-center rounded-md border-2 border-dashed border-muted-foreground/25 hover:border-muted-foreground/50 transition-colors"
                            >
                                <div class="text-center">
                                    <ImagePlus class="mx-auto h-8 w-8 text-muted-foreground" />
                                    <span class="mt-1 block text-xs text-muted-foreground">Upload</span>
                                </div>
                                <input type="file" accept="image/*" class="hidden" @change="handleImageChange" />
                            </label>
                        </div>
                        <p v-if="errors.image" class="text-sm text-destructive">{{ errors.image }}</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="price">Price</Label>
                            <Input id="price" v-model="form.price" type="number" step="0.01" min="0" placeholder="0.00" />
                            <p v-if="errors.price" class="text-sm text-destructive">{{ errors.price }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="stock">Stock Quantity</Label>
                            <Input id="stock" v-model="form.stock_quantity" type="number" min="0" placeholder="0" />
                            <p v-if="errors.stock_quantity" class="text-sm text-destructive">{{ errors.stock_quantity }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <Checkbox id="is_active" :checked="form.is_active" @update:checked="form.is_active = $event" />
                        <Label for="is_active" class="cursor-pointer">Active</Label>
                    </div>
                </CardContent>
                <CardFooter class="gap-2">
                    <Link href="/dashboard/products">
                        <Button variant="outline">Cancel</Button>
                    </Link>
                    <Button :disabled="processing" @click="submit">
                        {{ processing ? 'Saving...' : 'Save Changes' }}
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>
