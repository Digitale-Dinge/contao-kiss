import { Controller } from '@hotwired/stimulus';

/**
 * Breadcrumb Controller (V1.1)
 * 
 * Simple threshold-based collapsing. When collapsed, ALL items marked with
 * data-breadcrumb-target="item" are hidden and shown in a dropdown.
 * 
 * Collapse triggers (checked once on page load):
 * 1. Viewport width < breakpoint (default 640px)
 * 2. Marked item count > maxItems (default 4)
 * 
 * HTML Structure:
 *   - Items WITHOUT data-breadcrumb-target: Always visible (Home, Current Page, etc.)
 *   - Items WITH data-breadcrumb-target="item": Collapsible (hidden when collapsed)
 * 
 * Usage:
 *   <nav class="breadcrumb" data-controller="breadcrumb" aria-label="Breadcrumb">
 *     <ol data-breadcrumb-target="list">
 *       <li><a href="/">Home</a></li>
 *       <li class="breadcrumb-separator">...</li>
 *       <li data-breadcrumb-target="item"><a href="/a">A</a></li>
 *       <li class="breadcrumb-separator">...</li>
 *       <li aria-current="page"><a href="/c">Current</a></li>
 *     </ol>
 *   </nav>
 * 
 * Configuration:
 *   data-breadcrumb-max-items-value="4"    - Collapse when MORE than N items (default: 4)
 *   data-breadcrumb-breakpoint-value="640" - Force collapse below this width (default: 640)
 */
export default class extends Controller {
    static targets = ['list', 'item'];
    static values = {
        maxItems: { type: Number, default: 4 },
        breakpoint: { type: Number, default: 640 }
    };

    connect() {
        // Early exit if no list or no collapsible items
        if (!this.hasListTarget || this.itemTargets.length === 0) return;
        
        // Check once on page load - collapse if below breakpoint OR too many items
        const shouldCollapse = 
            window.innerWidth < this.breakpointValue || 
            this.itemTargets.length > this.maxItemsValue;
        
        if (shouldCollapse) {
            this.collapse();
        }
    }

    disconnect() {
        this.ellipsisElement?.remove();
        this.ellipsisSeparator?.remove();
    }

    collapse() {
        const items = this.itemTargets;
        const dropdownLinks = [];
        
        // Hide items and collect links for dropdown (single loop)
        items.forEach(item => {
            item.style.display = 'none';
            
            // Hide preceding separator
            const prev = item.previousElementSibling;
            if (prev?.classList.contains('breadcrumb-separator')) {
                prev.style.display = 'none';
            }
            
            // Collect link for dropdown
            const link = item.querySelector('a');
            if (link) dropdownLinks.push(link);
        });
        
        // Create and insert ellipsis
        this.createEllipsis(dropdownLinks);
    }

    createEllipsis(links) {
        // Create separator
        this.ellipsisSeparator = document.createElement('li');
        this.ellipsisSeparator.className = 'breadcrumb-separator breadcrumb-ellipsis-separator';
        this.ellipsisSeparator.setAttribute('aria-hidden', 'true');
        this.ellipsisSeparator.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>`;
        
        // Build dropdown menu HTML
        const menuItems = links.map(link => {
            const clone = link.cloneNode(true);
            clone.className = 'dropdown-item';
            clone.setAttribute('role', 'menuitem');
            return clone.outerHTML;
        }).join('');
        
        // Create ellipsis with popover
        this.ellipsisElement = document.createElement('li');
        this.ellipsisElement.className = 'breadcrumb-ellipsis';
        this.ellipsisElement.setAttribute('data-controller', 'popover');
        this.ellipsisElement.setAttribute('data-popover-alignment-value', 'start');
        this.ellipsisElement.innerHTML = `
            <button type="button" class="breadcrumb-ellipsis-btn icon-ellipsis-dots"
                    data-popover-target="trigger"
                    data-action="click->popover#toggle"
                    aria-label="Show hidden breadcrumbs">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/>
                </svg>
            </button>
            <div data-popover-target="content" class="popover-content popover-dropdown breadcrumb-dropdown">
                <nav class="dropdown-menu" role="menu">${menuItems}</nav>
            </div>
        `;
        
        // Insert after first visible item (Home) and its separator
        const firstItem = this.listTarget.querySelector('li:not(.breadcrumb-separator)');
        const insertAfter = firstItem?.nextElementSibling?.classList.contains('breadcrumb-separator')
            ? firstItem.nextElementSibling
            : firstItem;
        
        if (insertAfter?.nextSibling) {
            this.listTarget.insertBefore(this.ellipsisSeparator, insertAfter.nextSibling);
            this.listTarget.insertBefore(this.ellipsisElement, this.ellipsisSeparator.nextSibling);
        } else {
            this.listTarget.appendChild(this.ellipsisSeparator);
            this.listTarget.appendChild(this.ellipsisElement);
        }
    }
}