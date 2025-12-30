<script setup lang="ts">
import UserMenuContent from '@/components/UserMenuContent.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Toaster } from '@/components/ui/sonner';
import { useInitials } from '@/composables/useInitials';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ClipboardList, LogIn, Package, ShoppingCart } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted } from 'vue';
import { toast } from 'vue-sonner';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const cartItemCount = computed(() => page.props.cartItemCount as number);
const { getInitials } = useInitials();

let shownFlashId: string | null = null;

const showFlashMessages = () => {
    const flash = page.props.flash as { success?: string; error?: string } | undefined;
    const flashId = JSON.stringify(flash);

    if (flashId === shownFlashId || (!flash?.success && !flash?.error)) {
        return;
    }

    shownFlashId = flashId;

    if (flash?.success) {
        toast.success(flash.success);
    }
    if (flash?.error) {
        toast.error(flash.error);
    }
};

let removeListener: (() => void) | null = null;

onMounted(() => {
    showFlashMessages();
    removeListener = router.on('finish', () => {
        shownFlashId = null;
        showFlashMessages();
    });
});

onUnmounted(() => {
    if (removeListener) {
        removeListener();
    }
});
</script>

<template>
    <div class="corporate min-h-screen bg-background text-foreground">
        <Toaster position="top-right" :duration="3000" rich-colors />

        <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
            <div class="container mx-auto flex h-16 items-center justify-between px-4">
                <Link href="/" class="flex items-center gap-2 text-xl font-bold">
                    <Package class="h-6 w-6" />
                    ToyShop
                </Link>

                <nav class="hidden md:flex items-center gap-6">
                    <Link href="/" class="flex items-center gap-2 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
                        <Package class="h-4 w-4" />
                        Products
                    </Link>
                    <Link v-if="user" href="/cart" class="flex items-center gap-2 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
                        <div class="relative">
                            <ShoppingCart class="h-4 w-4" />
                            <Badge v-if="cartItemCount > 0" class="absolute -top-2 -right-3 h-5 min-w-5 px-1">
                                {{ cartItemCount }}
                            </Badge>
                        </div>
                        <span class="ml-1">Cart</span>
                    </Link>
                    <Link v-if="user" href="/orders" class="flex items-center gap-2 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
                        <ClipboardList class="h-4 w-4" />
                        Orders
                    </Link>
                </nav>

                <div class="flex items-center gap-4">
                    <template v-if="user">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="ghost" class="flex items-center gap-2 px-2">
                                    <Avatar class="h-8 w-8">
                                        <AvatarFallback class="text-xs">{{ getInitials(user.name) }}</AvatarFallback>
                                    </Avatar>
                                    <span class="hidden sm:inline text-sm">{{ user.name }}</span>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-56">
                                <UserMenuContent :user="user" />
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </template>
                    <template v-else>
                        <Link href="/login">
                            <Button>
                                <LogIn class="h-4 w-4 mr-2" />
                                Login
                            </Button>
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main class="container mx-auto px-4 py-8">
            <slot />
        </main>

        <footer class="border-t bg-muted/50">
            <div class="container mx-auto px-4 py-6 text-center text-sm text-muted-foreground">
                ToyShop - Best toys for your kids
            </div>
        </footer>
    </div>
</template>
