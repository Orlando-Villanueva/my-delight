import './bootstrap';

// Note: Flowbite CSS is imported via app.css, JS is loaded via CDN in layout
// Re-initialize Flowbite after HTMX content swaps
document.body.addEventListener('htmx:afterSwap', function() {
    // Flowbite CDN provides window.initFlowbite()
    if (typeof window.initFlowbite === 'function') {
        window.initFlowbite();
    }
});
