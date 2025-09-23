// Dashboard functionality
class Dashboard {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupAnimations();
    }

    setupEventListeners() {
        // Dashboard card clicks
        const dashboardCards = document.querySelectorAll('.dashboard-card');
        dashboardCards.forEach(card => {
            card.addEventListener('click', (e) => {
                const modalName = card.getAttribute('data-modal');
                this.openModal(modalName);
            });
        });

        // Modal close buttons
        const closeButtons = document.querySelectorAll('.modal-close');
        closeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const modal = button.closest('.modal');
                this.closeModal(modal);
            });
        });

        // Modal overlay clicks
        const modalOverlays = document.querySelectorAll('.modal-overlay');
        modalOverlays.forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                const modal = overlay.closest('.modal');
                this.closeModal(modal);
            });
        });

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });

        // Notification action buttons
        const notificationActions = document.querySelectorAll('.notification-action');
        notificationActions.forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleNotificationRead(button);
            });
        });

        // Book action buttons
        const bookActions = document.querySelectorAll('.btn');
        bookActions.forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                this.handleBookAction(button);
            });
        });
    }

    setupAnimations() {
        // Stagger animation for dashboard cards
        const cards = document.querySelectorAll('.dashboard-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate-fade-in');
        });

        // Add hover effects
        this.addHoverEffects();
    }

    addHoverEffects() {
        // Card hover effects
        const cards = document.querySelectorAll('.dashboard-card, .stat-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-2px)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    }

    openModal(modalName) {
        const modal = document.getElementById(`${modalName}-modal`);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Focus trap
            this.trapFocus(modal);
        }
    }

    closeModal(modal) {
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    closeAllModals() {
        const activeModals = document.querySelectorAll('.modal.active');
        activeModals.forEach(modal => {
            this.closeModal(modal);
        });
    }

    trapFocus(modal) {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length === 0) return;
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        // Focus first element
        firstElement.focus();

        modal.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        });
    }

    toggleNotificationRead(button) {
        const notificationItem = button.closest('.notification-item');
        const isRead = notificationItem.classList.contains('read');
        
        if (isRead) {
            notificationItem.classList.remove('read');
            button.textContent = 'Marcar como lida';
        } else {
            notificationItem.classList.add('read');
            button.textContent = 'Marcar como não lida';
        }

        // Add animation
        notificationItem.style.transition = 'all 0.3s ease';
        notificationItem.style.transform = 'scale(0.98)';
        setTimeout(() => {
            notificationItem.style.transform = 'scale(1)';
        }, 150);
    }

    handleBookAction(button) {
        const buttonText = button.textContent.trim();
        
        // Simple feedback for button actions
        const originalText = buttonText;
        button.disabled = true;
        
        switch(buttonText) {
            case 'Emprestar':
                button.textContent = 'Processando...';
                setTimeout(() => {
                    button.textContent = 'Emprestado!';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    }, 2000);
                }, 1000);
                break;
                
            case 'Renovar':
                button.textContent = 'Renovando...';
                setTimeout(() => {
                    button.textContent = 'Renovado!';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    }, 2000);
                }, 1000);
                break;
                
            case 'Devolver':
                button.textContent = 'Devolvendo...';
                setTimeout(() => {
                    button.textContent = 'Devolvido!';
                    // Remove the row from loans table
                    const row = button.closest('tr');
                    if (row) {
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-20px)';
                        setTimeout(() => {
                            row.remove();
                        }, 300);
                    }
                }, 1000);
                break;
                
            case 'Reservar':
                button.textContent = 'Reservando...';
                setTimeout(() => {
                    button.textContent = 'Reservado!';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    }, 2000);
                }, 1000);
                break;
                
            case 'Notificar':
            case 'Notificar quando disponível':
                button.textContent = 'Configurando...';
                setTimeout(() => {
                    button.textContent = 'Notificação ativa!';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    }, 2000);
                }, 1000);
                break;
                
            default:
                button.disabled = false;
        }
    }

    // Utility methods
    showToast(message, type = 'info') {
        // Simple toast notification
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--card);
            color: var(--foreground);
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-card);
            z-index: 1000;
            animation: slideInRight 0.3s ease-out;
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Stats animation
    animateStats() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const finalValue = target.textContent;
                    this.countUp(target, finalValue);
                    observer.unobserve(target);
                }
            });
        });

        statNumbers.forEach(stat => observer.observe(stat));
    }

    countUp(element, finalValue) {
        const isPercentage = finalValue.includes('%');
        const isTime = finalValue.includes('h');
        const numericValue = parseInt(finalValue.replace(/[^\d]/g, ''));
        
        let currentValue = 0;
        const increment = Math.ceil(numericValue / 50);
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= numericValue) {
                currentValue = numericValue;
                clearInterval(timer);
            }
            
            let displayValue = currentValue.toString();
            if (isPercentage) displayValue += '%';
            if (isTime) displayValue += 'h';
            
            element.textContent = displayValue;
        }, 30);
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const dashboard = new Dashboard();
    
    // Animate stats when page loads
    setTimeout(() => {
        dashboard.animateStats();
    }, 500);
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .toast {
        border-left: 4px solid var(--primary);
    }
    
    .toast-success {
        border-left-color: #22c55e;
    }
    
    .toast-error {
        border-left-color: var(--destructive);
    }
    
    .toast-warning {
        border-left-color: var(--accent);
    }
`;
document.head.appendChild(style);