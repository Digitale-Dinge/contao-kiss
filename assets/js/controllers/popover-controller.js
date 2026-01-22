import { Controller } from '@hotwired/stimulus';

/**
 * Popover Controller (V1.5)
 * 
 * A popover that sticks to its trigger using absolute positioning.
 * Auto-flips when hitting viewport edges. Repositions on scroll/resize.
 * 
 * Usage:
 *   <div data-controller="popover">
 *     <button data-popover-target="trigger" data-action="click->popover#toggle">Open</button>
 *     <div data-popover-target="content" class="popover-content">
 *       <div data-popover-target="arrow" class="popover-arrow"></div>
 *       Content
 *     </div>
 *   </div>
 * 
 * Configuration:
 *   data-popover-placement-value="bottom"   - top|bottom|left|right (default: bottom)
 *   data-popover-alignment-value="start"    - start|center|end (default: center)
 *   data-popover-offset-value="8"           - gap in px (default: 8)
 *   data-popover-trigger-value="click"      - click|hover (default: click)
 *   data-popover-flip-value="true"          - auto-flip on edge collision (default: true)
 * 
 * Timing (hardcoded - edit delayedClose() to change):
 *   - Open delay: 0ms (instant)
 *   - Close delay: 150ms (in delayedClose method, prevents flicker when moving to content)
 */
export default class extends Controller {
    static targets = ['trigger', 'content', 'arrow'];
    static values = {
        placement: { type: String, default: 'bottom' },
        alignment: { type: String, default: 'center' },
        offset: { type: Number, default: 8 },
        trigger: { type: String, default: 'click' },
        flip: { type: Boolean, default: true },
    };

    connect() {
        this.isOpen = false;
        
        // Ensure container is positioned for absolute children
        if (getComputedStyle(this.element).position === 'static') {
            this.element.style.position = 'relative';
        }
        
        // Setup hover trigger
        if (this.triggerValue === 'hover' && this.hasTriggerTarget) {
            this._hoverOpen = () => this.open();
            this._hoverClose = () => this.delayedClose();
            this._hoverCancel = () => this.cancelClose();
            
            this.triggerTarget.addEventListener('mouseenter', this._hoverOpen);
            this.triggerTarget.addEventListener('mouseleave', this._hoverClose);
            
            if (this.hasContentTarget) {
                this.contentTarget.addEventListener('mouseenter', this._hoverCancel);
                this.contentTarget.addEventListener('mouseleave', this._hoverClose);
            }
        }
        
        // Setup ARIA
        if (this.hasTriggerTarget && this.hasContentTarget) {
            const id = this.contentTarget.id || `popover-${crypto.randomUUID().slice(0, 8)}`;
            this.contentTarget.id = id;
            this.triggerTarget.setAttribute('aria-expanded', 'false');
            this.triggerTarget.setAttribute('aria-controls', id);
        }
    }

    disconnect() {
        this.removeListeners();
        
        // Clean up hover listeners
        if (this._hoverOpen) {
            this.triggerTarget?.removeEventListener('mouseenter', this._hoverOpen);
            this.triggerTarget?.removeEventListener('mouseleave', this._hoverClose);
            this.contentTarget?.removeEventListener('mouseenter', this._hoverCancel);
            this.contentTarget?.removeEventListener('mouseleave', this._hoverClose);
        }
    }

    toggle(event) {
        event?.preventDefault();
        event?.stopPropagation();
        this.isOpen ? this.close() : this.open();
    }

    open() {
        if (this.isOpen || !this.hasContentTarget) return;
        
        this.isOpen = true;
        this.triggerTarget?.setAttribute('aria-expanded', 'true');
        this.position();
        this.contentTarget.classList.add('popover-open');
        this.addListeners();
        this.dispatch('opened');
    }

    close() {
        if (!this.isOpen) return;
        
        this.isOpen = false;
        this.triggerTarget?.setAttribute('aria-expanded', 'false');
        this.contentTarget?.classList.remove('popover-open');
        this.removeListeners();
        this.dispatch('closed');
    }

    delayedClose() {
        this._closeTimeout = setTimeout(() => this.close(), 150);
    }

    cancelClose() {
        clearTimeout(this._closeTimeout);
    }

    // ========================================
    // POSITIONING
    // ========================================
    position() {
        const content = this.contentTarget;
        const trigger = this.triggerTarget;
        if (!content || !trigger) return;

        const { offsetValue: offset, alignmentValue: alignment, flipValue: flip } = this;
        
        // Reset positioning
        Object.assign(content.style, { position: 'absolute', top: '', bottom: '', left: '', right: '' });

        // Get measurements
        const triggerRect = trigger.getBoundingClientRect();
        const { offsetWidth: tW, offsetHeight: tH, offsetLeft: tL, offsetTop: tT } = trigger;
        
        // Measure content
        const wasOpen = content.classList.contains('popover-open');
        if (!wasOpen) {
            Object.assign(content.style, { visibility: 'hidden', opacity: '0' });
            content.classList.add('popover-open');
        }
        const { offsetWidth: cW, offsetHeight: cH } = content;
        if (!wasOpen) {
            content.classList.remove('popover-open');
            Object.assign(content.style, { visibility: '', opacity: '' });
        }

        // Determine placement (with flip)
        let placement = this.placementValue;
        if (flip) {
            placement = this.getFlippedPlacement(placement, triggerRect, cW, cH, offset);
        }

        // Calculate position
        let left, top;
        const isVertical = placement === 'top' || placement === 'bottom';
        
        if (isVertical) {
            // Vertical: set top/bottom, align horizontally
            if (placement === 'top') {
                content.style.bottom = `${tH + offset}px`;
                content.style.top = 'auto';
            } else {
                top = tT + tH + offset;
                content.style.top = `${top}px`;
            }
            left = this.clampToViewport(this.getAlignedPos(alignment, tL, tW, cW), cW, triggerRect.left - tL, true);
            content.style.left = `${left}px`;
        } else {
            // Horizontal: set left/right, align vertically
            if (placement === 'left') {
                content.style.right = `${tW + offset}px`;
                content.style.left = 'auto';
            } else {
                left = tL + tW + offset;
                content.style.left = `${left}px`;
            }
            top = this.clampToViewport(this.getAlignedPos(alignment, tT, tH, cH), cH, triggerRect.top - tT, false);
            content.style.top = `${top}px`;
        }

        // Update placement class
        content.className = content.className.replace(/popover-(top|bottom|left|right)/g, '');
        content.classList.add(`popover-${placement}`);

        // Position arrow
        if (this.hasArrowTarget) {
            this.positionArrow(placement, tL, tT, tW, tH, cW, cH, left, top);
        }
    }

    getAlignedPos(alignment, triggerPos, triggerSize, contentSize) {
        if (alignment === 'start') return triggerPos;
        if (alignment === 'end') return triggerPos + triggerSize - contentSize;
        return triggerPos + (triggerSize - contentSize) / 2;
    }

    clampToViewport(pos, size, viewportOffset, isHorizontal) {
        const viewportSize = isHorizontal ? window.innerWidth : window.innerHeight;
        const padding = 8;
        const viewportPos = viewportOffset + pos;
        
        if (viewportPos + size > viewportSize - padding) {
            pos -= (viewportPos + size - viewportSize + padding);
        } else if (viewportPos < padding) {
            pos += (padding - viewportPos);
        }
        return pos;
    }

    getFlippedPlacement(placement, triggerRect, cW, cH, offset) {
        const { innerWidth: vw, innerHeight: vh } = window;
        const p = 8; // padding
        
        const space = {
            top: triggerRect.top - p,
            bottom: vh - triggerRect.bottom - p,
            left: triggerRect.left - p,
            right: vw - triggerRect.right - p,
        };
        
        const flip = {
            bottom: space.bottom < cH + offset && space.top > cH + offset ? 'top' : null,
            top: space.top < cH + offset && space.bottom > cH + offset ? 'bottom' : null,
            right: space.right < cW + offset && space.left > cW + offset ? 'left' : null,
            left: space.left < cW + offset && space.right > cW + offset ? 'right' : null,
        };
        
        return flip[placement] || placement;
    }

    positionArrow(placement, tL, tT, tW, tH, cW, cH, contentLeft, contentTop) {
        const arrow = this.arrowTarget;
        const size = 12;
        const min = 12;
        
        Object.assign(arrow.style, { left: '', right: '', top: '', bottom: '' });

        if (placement === 'top' || placement === 'bottom') {
            const center = tL + tW / 2 - (contentLeft ?? 0) - size / 2;
            arrow.style.left = `${Math.max(min, Math.min(center, cW - size - min))}px`;
        } else {
            const center = tT + tH / 2 - (contentTop ?? 0) - size / 2;
            arrow.style.top = `${Math.max(min, Math.min(center, cH - size - min))}px`;
        }
    }

    // ========================================
    // LISTENERS
    // ========================================
    addListeners() {
        this._onClickOutside = (e) => !this.element.contains(e.target) && this.close();
        this._onKeydown = (e) => e.key === 'Escape' && this.close();
        this._onReposition = () => this.position();

        document.addEventListener('click', this._onClickOutside, true);
        document.addEventListener('keydown', this._onKeydown);
        window.addEventListener('scroll', this._onReposition, true);
        window.addEventListener('resize', this._onReposition);
    }

    removeListeners() {
        if (!this._onClickOutside) return;
        
        document.removeEventListener('click', this._onClickOutside, true);
        document.removeEventListener('keydown', this._onKeydown);
        window.removeEventListener('scroll', this._onReposition, true);
        window.removeEventListener('resize', this._onReposition);
    }
}
