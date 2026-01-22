import { Controller } from '@hotwired/stimulus';

/**
 * Breadcrumb Controller (V1.0)
 * 
 * Simple threshold-based collapsing. When collapsed, ALL items marked with
 * data-breadcrumb-target="item" are hidden and shown in a dropdown.
 * 
 * Collapse triggers:
 * 1. Marked item count > maxItems (configurable via data attribute)
 * 2. CSS variable --breadcrumb-force-collapse: 1 (responsive, set via CSS)
 * 
 * HTML Structure:
 *   - Items WITHOUT data-breadcrumb-target: Always visible (Home, Current Page, etc.)
 *   - Items WITH data-breadcrumb-target="item": Collapsible (hidden when collapsed)
 * 
 * Usage:
 *   <nav class="breadcrumb" data-controller="breadcrumb" aria-label="Breadcrumb">
 *     <ol data-breadcrumb-target="list">
 *       <li><a href="/">Home</a></li>                              <!-- Always visible -->
 *       <li class="breadcrumb-separator">...</li>
 *       <li data-breadcrumb-target="item"><a href="/a">A</a></li>  <!-- Collapsible -->
 *       <li class="breadcrumb-separator">...</li>
 *       <li data-breadcrumb-target="item"><a href="/b">B</a></li>  <!-- Collapsible -->
 *       <li class="breadcrumb-separator">...</li>
 *       <li aria-current="page"><a href="/c">Current</a></li>      <!-- Always visible -->
 *     </ol>
 *   </nav>
 * 
 * Configuration:
 *   data-breadcrumb-max-items-value="5" - Collapse when MORE than N marked items
 */
export default class extends Controller {
    static targets = ['list', 'item'];
    static values = {
        maxItems: { type: Number, default: 5 }
    };

    connect() {
        this.isCollapsed = false;
        this.ellipsisElement = null;
        this.ellipsisSeparator = null;
        
        // Check once on page load (no resize listener - refresh to update)
        this.update();
    }

    disconnect() {
        this.removeEllipsis();
    }

    update() {
        if (!this.hasListTarget) return;
        
        const items = this.itemTargets;
        if (items.length === 0) return;
        
        const shouldCollapse = this.shouldCollapse(items.length);
        
        if (shouldCollapse && !this.isCollapsed) {
            this.collapse();
        } else if (!shouldCollapse && this.isCollapsed) {
            this.expand();
        }
    }

    shouldCollapse(itemCount) {
        // Check CSS variable for responsive force-collapse
        const cssForce = getComputedStyle(this.element)
            .getPropertyValue('--breadcrumb-force-collapse').trim();
        
        if (cssForce === '1') return true;
        
        // Check item count threshold
        return itemCount > this.maxItemsValue;
    }

    collapse() {
        const items = this.itemTargets;
        if (items.length === 0) return;
        
        this.isCollapsed = true;
        
        // Hide ALL marked items and their preceding separators
        items.forEach(item => {
            item.style.display = 'none';
            const prev = item.previousElementSibling;
            if (prev?.classList.contains('breadcrumb-separator') &&
                !prev.classList.contains('breadcrumb-ellipsis-separator')) {
                prev.style.display = 'none';
            }
        });
        
        this.createEllipsis();
        this.populateDropdown();
    }

    expand() {
        this.isCollapsed = false;
        
        // Show all items and their preceding separators
        this.itemTargets.forEach(item => {
            item.style.display = '';
            const prev = item.previousElementSibling;
            if (prev?.classList.contains('breadcrumb-separator') &&
                !prev.classList.contains('breadcrumb-ellipsis-separator')) {
                prev.style.display = '';
            }
        });
        
        this.removeEllipsis();
    }

    createEllipsis() {
        if (this.ellipsisElement) return;
        
        // Create separator
        this.ellipsisSeparator = document.createElement('li');
        this.ellipsisSeparator.className = 'breadcrumb-separator breadcrumb-ellipsis-separator';
        this.ellipsisSeparator.setAttribute('aria-hidden', 'true');
        this.ellipsisSeparator.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>`;
        
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
                <nav class="dropdown-menu" role="menu"></nav>
            </div>
        `;
        
        this.dropdownMenu = this.ellipsisElement.querySelector('.dropdown-menu');
        
        // Insert after the first non-separator item (typically Home)
        let insertAfter = this.listTarget.querySelector('li:not(.breadcrumb-separator)');
        
        // Skip past separator if present
        const next = insertAfter?.nextElementSibling;
        if (next?.classList.contains('breadcrumb-separator') &&
            !next.classList.contains('breadcrumb-ellipsis-separator')) {
            insertAfter = next;
        }
        
        if (insertAfter?.nextSibling) {
            this.listTarget.insertBefore(this.ellipsisSeparator, insertAfter.nextSibling);
            this.listTarget.insertBefore(this.ellipsisElement, this.ellipsisSeparator.nextSibling);
        } else {
            this.listTarget.appendChild(this.ellipsisSeparator);
            this.listTarget.appendChild(this.ellipsisElement);
        }
    }

    removeEllipsis() {
        this.ellipsisElement?.remove();
        this.ellipsisSeparator?.remove();
        this.ellipsisElement = null;
        this.ellipsisSeparator = null;
        this.dropdownMenu = null;
    }

    populateDropdown() {
        if (!this.dropdownMenu) return;
        this.dropdownMenu.innerHTML = '';
        
        // Clone links from all hidden items
        this.itemTargets.forEach(item => {
            const link = item.querySelector('a');
            if (link) {
                const clone = link.cloneNode(true);
                clone.className = 'dropdown-item';
                clone.setAttribute('role', 'menuitem');
                this.dropdownMenu.appendChild(clone);
            }
        });
    }
}
