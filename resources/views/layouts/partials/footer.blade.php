<!-- Footer -->
<footer class="fixed bottom-0 right-0 left-0 lg:left-56 xl:left-60 border-t border-neutral-200/50 dark:border-dark-border/50 bg-white/80 dark:bg-dark-surface/80 backdrop-blur-sm h-[52px] z-10 transition-all duration-300"
    :class="$store.app.sidebarOpen ? 'lg:left-56 xl:left-60' : 'left-0'">
    <div class="px-3 lg:px-5 h-full flex items-center justify-between text-xs text-neutral-500">
        <p>&copy; {{ date('Y') }} <span class="font-medium text-primary-600">Mari Partner</span>. All rights reserved.</p>
        <p class="flex items-center gap-1">
            <span>v1.0.0</span>
            <span class="text-neutral-300 dark:text-neutral-600">•</span>
            <span>Gudang Produksi</span>
        </p>
    </div>
</footer>
