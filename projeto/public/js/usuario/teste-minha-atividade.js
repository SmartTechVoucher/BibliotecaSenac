// Dashboard functionality with PHP Backend Integration
class Dashboard {
    constructor() {
        this.apiBaseUrl = '/api'; // Simulated PHP API base URL
        this.userData = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadUserData();
        this.setupAnimations();
    }

    // ===========================================
    // API SIMULATION METHODS (PHP Backend)
    // ===========================================

    // Simulate PHP API call with realistic response structure
    async apiCall(endpoint, method = 'GET', data = null) {
        // Simulate loading delay
        await new Promise(resolve => setTimeout(resolve, Math.random() * 1000 + 500));

        // Simulate different endpoints
        switch(endpoint) {
            case '/user/profile':
                return {
                    success: true,
                    data: {
                        id: 1,
                        name: 'Marlon',
                        email: 'ana.silva@email.com',
                        account_type: 'Premium',
                        member_since: '2023-01-15',
                        avatar_url: null
                    },
                    message: 'Perfil carregado com sucesso'
                };

            case '/user/stats':
                return {
                    success: true,
                    data: {
                        books_read: 127,
                        reading_time_hours: 15,
                        genres_explored: 8,
                        monthly_goal_progress: 95
                    },
                    message: 'Estatísticas carregadas'
                };

            case '/notifications':
                return {
                    success: true,
                    data: [
                        {
                            id: 1,
                            type: 'loan_reminder',
                            title: 'Prazo de devolução próximo',
                            message: 'O livro "1984" deve ser devolvido em 2 dias',
                            created_at: '2024-01-20 14:30:00',
                            read: false,
                            priority: 'high'
                        },
                        {
                            id: 2,
                            type: 'recommendation',
                            title: 'Nova recomendação disponível',
                            message: 'Baseado no seu histórico, recomendamos "Dune"',
                            created_at: '2024-01-19 09:15:00',
                            read: false,
                            priority: 'medium'
                        },
                        {
                            id: 3,
                            type: 'library_news',
                            title: 'Novos livros adicionados',
                            message: '50 novos títulos foram adicionados à coleção',
                            created_at: '2024-01-18 16:45:00',
                            read: true,
                            priority: 'low'
                        }
                    ],
                    message: 'Notificações carregadas'
                };

            case '/loans/active':
                return {
                    success: true,
                    data: [
                        {
                            id: 1,
                            book_id: 101,
                            book_title: '1984',
                            book_author: 'George Orwell',
                            book_isbn: '978-0-452-28423-4',
                            loan_date: '2024-01-10',
                            due_date: '2024-01-24',
                            renewal_count: 0,
                            max_renewals: 2,
                            status: 'active',
                            days_remaining: 2
                        },
                        {
                            id: 2,
                            book_id: 102,
                            book_title: 'O Nome do Vento',
                            book_author: 'Patrick Rothfuss',
                            book_isbn: '978-85-7542-947-8',
                            loan_date: '2024-01-05',
                            due_date: '2024-01-19',
                            renewal_count: 1,
                            max_renewals: 2,
                            status: 'overdue',
                            days_remaining: -3
                        }
                    ],
                    message: 'Empréstimos ativos carregados'
                };

            case '/favorites':
                return {
                    success: true,
                    data: [
                        {
                            id: 201,
                            title: 'O Senhor dos Anéis',
                            author: 'J.R.R. Tolkien',
                            isbn: '978-85-336-2052-2',
                            genre: 'Fantasia',
                            available: true,
                            cover_url: null,
                            added_to_favorites: '2023-12-15'
                        },
                        {
                            id: 202,
                            title: 'Cem Anos de Solidão',
                            author: 'Gabriel García Márquez',
                            isbn: '978-85-359-0277-3',
                            genre: 'Realismo Mágico',
                            available: false,
                            cover_url: null,
                            added_to_favorites: '2023-11-28'
                        },
                        {
                            id: 203,
                            title: 'Sapiens',
                            author: 'Yuval Noah Harari',
                            isbn: '978-85-359-2841-4',
                            genre: 'História',
                            available: true,
                            cover_url: null,
                            added_to_favorites: '2023-10-10'
                        }
                    ],
                    message: 'Favoritos carregados'
                };

            case '/recommendations':
                return {
                    success: true,
                    data: [
                        {
                            id: 301,
                            title: 'Dune',
                            author: 'Frank Herbert',
                            isbn: '978-0-441-17271-9',
                            genre: 'Ficção Científica',
                            recommendation_reason: 'Baseado na sua leitura de ficção científica',
                            match_percentage: 95,
                            available: true,
                            rating: 4.8
                        },
                        {
                            id: 302,
                            title: 'Foundation',
                            author: 'Isaac Asimov',
                            isbn: '978-0-553-29335-0',
                            genre: 'Ficção Científica',
                            recommendation_reason: 'Fãs de épicos espaciais adoram',
                            match_percentage: 88,
                            available: true,
                            rating: 4.6
                        },
                        {
                            id: 303,
                            title: 'A Guerra dos Tronos',
                            author: 'George R.R. Martin',
                            isbn: '978-85-7542-832-7',
                            genre: 'Fantasia Épica',
                            recommendation_reason: 'Pela sua preferência por fantasia',
                            match_percentage: 92,
                            available: false,
                            rating: 4.7
                        }
                    ],
                    message: 'Recomendações carregadas'
                };

            default:
                throw new Error(`Endpoint ${endpoint} não encontrado`);
        }
    }

    // Load user data and initialize dashboard
    async loadUserData() {
        try {
            this.showLoading(true);
            
            const [profileResponse, statsResponse] = await Promise.all([
                this.apiCall('/user/profile'),
                this.apiCall('/user/stats')
            ]);

            if (profileResponse.success && statsResponse.success) {
                this.userData = profileResponse.data;
                this.updateWelcomeCard(profileResponse.data);
                this.updateStats(statsResponse.data);
            }

            this.showLoading(false);
        } catch (error) {
            console.error('Erro ao carregar dados do usuário:', error);
            this.showToast('Erro ao carregar dados do perfil', 'error');
            this.showLoading(false);
        }
    }

    // Update welcome card with user data
    updateWelcomeCard(userData) {
        const welcomeTitle = document.querySelector('.welcome-title');
        const welcomeStatus = document.querySelector('.welcome-status');
        
        if (welcomeTitle) {
            welcomeTitle.textContent = `Bem-vindo de volta, ${userData.name}!`;
        }
        
        if (welcomeStatus) {
            welcomeStatus.textContent = `Conta ${userData.account_type}`;
        }
    }

    // Update stats with real data
    updateStats(statsData) {
        const statCards = document.querySelectorAll('.stat-card');
        const stats = [
            { value: statsData.books_read, suffix: '' },
            { value: statsData.reading_time_hours, suffix: 'h' },
            { value: statsData.genres_explored, suffix: '' },
            { value: statsData.monthly_goal_progress, suffix: '%' }
        ];

        statCards.forEach((card, index) => {
            const statNumber = card.querySelector('.stat-number');
            if (statNumber && stats[index]) {
                const { value, suffix } = stats[index];
                this.animateStatValue(statNumber, 0, value, suffix);
            }
        });
    }

    // Animate stat values
    animateStatValue(element, start, end, suffix = '') {
        const duration = 2000;
        const stepTime = 50;
        const steps = duration / stepTime;
        const increment = (end - start) / steps;
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= end) {
                current = end;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current) + suffix;
        }, stepTime);
    }

    // Show/hide loading spinner
    showLoading(show) {
        let spinner = document.querySelector('.loading-spinner');
        
        if (show && !spinner) {
            spinner = document.createElement('div');
            spinner.className = 'loading-spinner';
            spinner.innerHTML = `
                <div class="spinner"></div>
                <p>Carregando dados...</p>
            `;
            document.body.appendChild(spinner);
        } else if (!show && spinner) {
            spinner.remove();
        }
    }

    setupEventListeners() {
        // Dashboard card clicks
        const dashboardCards = document.querySelectorAll('.dashboard-card');
        dashboardCards.forEach(card => {
            card.addEventListener('click', async (e) => {
                const modalName = card.getAttribute('data-modal');
                await this.openModal(modalName);
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

    async openModal(modalName) {
        const modal = document.getElementById(`${modalName}-modal`);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Load modal content from API
            await this.loadModalContent(modalName);
            
            // Focus trap
            this.trapFocus(modal);
        }
    }

    // Load content for different modals
    async loadModalContent(modalName) {
        const modalBody = document.querySelector(`#${modalName}-modal .modal-body`);
        if (!modalBody) return;

        try {
            modalBody.innerHTML = '<div class="modal-loading">Carregando...</div>';

            let response;
            switch(modalName) {
                case 'notifications':
                    response = await this.apiCall('/notifications');
                    this.renderNotifications(modalBody, response.data);
                    break;
                    
                case 'loans':
                    response = await this.apiCall('/loans/active');
                    this.renderLoans(modalBody, response.data);
                    break;
                    
                case 'favorites':
                    response = await this.apiCall('/favorites');
                    this.renderFavorites(modalBody, response.data);
                    break;
                    
                case 'recommendations':
                    response = await this.apiCall('/recommendations');
                    this.renderRecommendations(modalBody, response.data);
                    break;
            }
        } catch (error) {
            modalBody.innerHTML = `<div class="error-message">Erro ao carregar dados: ${error.message}</div>`;
        }
    }

    // Render notifications
    renderNotifications(container, notifications) {
        container.innerHTML = `
            <div class="notifications-list">
                ${notifications.map(notification => `
                    <div class="notification-item ${notification.read ? 'read' : ''}" data-id="${notification.id}">
                        <div class="notification-icon ${notification.priority}">
                            ${this.getNotificationIcon(notification.type)}
                        </div>
                        <div class="notification-content">
                            <h4>${notification.title}</h4>
                            <p>${notification.message}</p>
                            <small>${this.formatDateTime(notification.created_at)}</small>
                        </div>
                        <div class="notification-actions">
                            <button class="notification-action btn secondary" onclick="dashboard.toggleNotificationRead(${notification.id})">
                                ${notification.read ? 'Marcar como não lida' : 'Marcar como lida'}
                            </button>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    // Render loans
    renderLoans(container, loans) {
        container.innerHTML = `
            <div class="loans-table-container">
                <table class="loans-table">
                    <thead>
                        <tr>
                            <th>Livro</th>
                            <th>Autor</th>
                            <th>Emprestado em</th>
                            <th>Vence em</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${loans.map(loan => `
                            <tr data-loan-id="${loan.id}" class="${loan.status === 'overdue' ? 'overdue' : ''}">
                                <td>
                                    <strong>${loan.book_title}</strong>
                                    <br><small>ISBN: ${loan.book_isbn}</small>
                                </td>
                                <td>${loan.book_author}</td>
                                <td>${this.formatDate(loan.loan_date)}</td>
                                <td>
                                    ${this.formatDate(loan.due_date)}
                                    <br><small class="${loan.days_remaining < 0 ? 'overdue-text' : 'due-text'}">
                                        ${Math.abs(loan.days_remaining)} dias ${loan.days_remaining < 0 ? 'em atraso' : 'restantes'}
                                    </small>
                                </td>
                                <td>
                                    <span class="status-badge ${loan.status}">
                                        ${loan.status === 'active' ? 'Ativo' : 'Atrasado'}
                                    </span>
                                </td>
                                <td>
                                    <div class="loan-actions">
                                        ${loan.renewal_count < loan.max_renewals ? 
                                            `<button class="btn secondary" onclick="dashboard.renewLoan(${loan.id})">Renovar</button>` : 
                                            '<small>Limite de renovações atingido</small>'
                                        }
                                        <button class="btn primary" onclick="dashboard.returnLoan(${loan.id})">Devolver</button>
                                    </div>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    // Render favorites
    renderFavorites(container, favorites) {
        container.innerHTML = `
            <div class="favorites-grid">
                ${favorites.map(book => `
                    <div class="favorite-book">
                        <div class="book-cover">📖</div>
                        <div class="book-info">
                            <h3>${book.title}</h3>
                            <p class="book-author">${book.author}</p>
                            <p class="book-genre">${book.genre}</p>
                            <small>Adicionado em ${this.formatDate(book.added_to_favorites)}</small>
                        </div>
                        <div class="book-actions">
                            ${book.available ? 
                                `<button class="btn primary" onclick="dashboard.borrowBook(${book.id})">Emprestar</button>` :
                                `<button class="btn secondary" onclick="dashboard.reserveBook(${book.id})">Reservar</button>`
                            }
                            <button class="btn outline" onclick="dashboard.removeFavorite(${book.id})">Remover dos Favoritos</button>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    // Render recommendations
    renderRecommendations(container, recommendations) {
        container.innerHTML = `
            <div class="recommendations-grid">
                ${recommendations.map(book => `
                    <div class="recommendation-book">
                        <div class="book-cover">📚</div>
                        <div class="book-info">
                            <h3>${book.title}</h3>
                            <p class="book-author">${book.author}</p>
                            <p class="book-genre">${book.genre}</p>
                            <div class="recommendation-meta">
                                <span class="match-percentage">${book.match_percentage}% compatível</span>
                                <span class="rating">⭐ ${book.rating}</span>
                            </div>
                            <p class="recommendation-reason">${book.recommendation_reason}</p>
                        </div>
                        <div class="book-actions">
                            ${book.available ? 
                                `<button class="btn primary" onclick="dashboard.borrowBook(${book.id})">Emprestar</button>` :
                                `<button class="btn secondary" onclick="dashboard.notifyWhenAvailable(${book.id})">Notificar quando disponível</button>`
                            }
                            <button class="btn outline" onclick="dashboard.addToFavorites(${book.id})">Adicionar aos Favoritos</button>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    // ===========================================
    // BOOK ACTION METHODS (API Calls)
    // ===========================================

    async borrowBook(bookId) {
        try {
            this.showActionLoading(event.target, 'Emprestando...');
            
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            // In real scenario: await this.apiCall('/loans/create', 'POST', { book_id: bookId });
            
            this.showToast('Livro emprestado com sucesso!', 'success');
            event.target.textContent = 'Emprestado!';
            
            setTimeout(() => {
                event.target.textContent = 'Emprestar';
                event.target.disabled = false;
            }, 2000);
            
        } catch (error) {
            this.showToast('Erro ao emprestar livro', 'error');
            event.target.disabled = false;
        }
    }

    async renewLoan(loanId) {
        try {
            this.showActionLoading(event.target, 'Renovando...');
            
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            // In real scenario: await this.apiCall(`/loans/${loanId}/renew`, 'PUT');
            
            this.showToast('Empréstimo renovado com sucesso!', 'success');
            event.target.textContent = 'Renovado!';
            
            // Update the due date in the table
            const row = event.target.closest('tr');
            if (row) {
                const dueDateCell = row.querySelector('td:nth-child(4)');
                const newDate = new Date();
                newDate.setDate(newDate.getDate() + 14);
                dueDateCell.innerHTML = `
                    ${this.formatDate(newDate.toISOString().split('T')[0])}
                    <br><small class="due-text">14 dias restantes</small>
                `;
            }
            
        } catch (error) {
            this.showToast('Erro ao renovar empréstimo', 'error');
            event.target.disabled = false;
        }
    }

    async returnLoan(loanId) {
        try {
            this.showActionLoading(event.target, 'Devolvendo...');
            
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            // In real scenario: await this.apiCall(`/loans/${loanId}/return`, 'PUT');
            
            this.showToast('Livro devolvido com sucesso!', 'success');
            
            // Remove row from table
            const row = event.target.closest('tr');
            if (row) {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => row.remove(), 300);
            }
            
        } catch (error) {
            this.showToast('Erro ao devolver livro', 'error');
            event.target.disabled = false;
        }
    }

    async toggleNotificationRead(notificationId) {
        try {
            // In real scenario: await this.apiCall(`/notifications/${notificationId}/toggle-read`, 'PUT');
            
            const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.toggle('read');
                const button = notificationItem.querySelector('.notification-action');
                const isRead = notificationItem.classList.contains('read');
                button.textContent = isRead ? 'Marcar como não lida' : 'Marcar como lida';
            }
            
        } catch (error) {
            this.showToast('Erro ao atualizar notificação', 'error');
        }
    }

    // ===========================================
    // UTILITY METHODS
    // ===========================================

    showActionLoading(button, text) {
        button.disabled = true;
        button.textContent = text;
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
    }

    formatDateTime(dateTimeString) {
        const date = new Date(dateTimeString);
        return date.toLocaleString('pt-BR');
    }

    getNotificationIcon(type) {
        const icons = {
            'loan_reminder': '⏰',
            'recommendation': '📚',
            'library_news': '📢',
            'system': '⚙️'
        };
        return icons[type] || '📄';
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
    // Make dashboard globally accessible for onclick handlers
    window.dashboard = new Dashboard();
});

// Add CSS animations and additional styles
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
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
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

    .loading-spinner {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 1rem;
    }

    .modal-loading {
        text-align: center;
        padding: 2rem;
        color: var(--muted-foreground);
    }

    .error-message {
        text-align: center;
        padding: 2rem;
        color: var(--destructive);
        background: var(--destructive-foreground);
        border-radius: var(--border-radius);
        margin: 1rem 0;
    }

    .overdue {
        background-color: rgba(239, 68, 68, 0.1);
    }

    .overdue-text {
        color: var(--destructive);
        font-weight: 600;
    }

    .due-text {
        color: var(--muted-foreground);
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-badge.active {
        background-color: rgba(34, 197, 94, 0.1);
        color: #059669;
    }

    .status-badge.overdue {
        background-color: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border-radius: var(--border-radius);
        background: var(--card);
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .notification-item.read {
        opacity: 0.7;
        background: var(--muted);
    }

    .notification-icon {
        font-size: 1.5rem;
        margin-right: 1rem;
    }

    .notification-icon.high {
        filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.4));
    }

    .loans-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .loans-table th,
    .loans-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .loans-table th {
        font-weight: 600;
        color: var(--foreground);
    }

    .loan-actions {
        display: flex;
        gap: 0.5rem;
        flex-direction: column;
    }

    .match-percentage {
        background: var(--primary);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .rating {
        margin-left: 0.5rem;
        font-size: 0.875rem;
    }

    .recommendation-meta {
        margin: 0.5rem 0;
    }

    .recommendation-reason {
        font-style: italic;
        color: var(--muted-foreground);
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
`;
document.head.appendChild(style);