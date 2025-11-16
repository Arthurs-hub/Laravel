
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

const baseMetaTag = (typeof document !== 'undefined' && typeof document.querySelector === 'function')
    ? document.querySelector('meta[name="base-url"]')
    : null;

export const baseUrl = baseMetaTag?.getAttribute?.('content') ?? (typeof window !== 'undefined' ? window.location.origin : '');

if (typeof window !== 'undefined' && typeof window.Alpine?.start === 'function') {
    window.Alpine.start();
}
