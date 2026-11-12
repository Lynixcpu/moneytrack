/**
 * MoneyTrack - Main Client-side Script
 * Handles currency formatting, dynamic category filters, modal handlers, and mobile sidebar.
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Toggle
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');

    if (sidebarToggleBtn && sidebar) {
        sidebarToggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('show');
            }
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }

    // 2. Format Rupiah Input Helper
    window.formatRupiahInput = function (input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            input.value = '';
        }
    };

    // 3. Dynamic Category Filtering for Add Transaction Modal
    function setupCategorySwitcher(typeSelectId, categorySelectId, incomeCats, expenseCats) {
        const typeSelect = document.getElementById(typeSelectId);
        const categorySelect = document.getElementById(categorySelectId);

        if (!typeSelect || !categorySelect) return;

        function updateCategories(selectedType, selectedCatId = null) {
            categorySelect.innerHTML = '<option value="">-- Pilih Kategori --</option>';
            const list = selectedType === 'income' ? incomeCats : expenseCats;

            list.forEach(function (cat) {
                const opt = document.createElement('option');
                opt.value = cat.id;
                opt.textContent = cat.name;
                if (selectedCatId && String(cat.id) === String(selectedCatId)) {
                    opt.selected = true;
                }
                categorySelect.appendChild(opt);
            });
        }

        typeSelect.addEventListener('change', function () {
            updateCategories(this.value);
        });

        // Initialize
        if (typeSelect.value) {
            updateCategories(typeSelect.value, categorySelect.getAttribute('data-selected'));
        }
    }

    window.setupCategorySwitcher = setupCategorySwitcher;

    // 4. Modal Edit Transaction Handler
    const editModalEl = document.getElementById('modalEditTransaction');
    if (editModalEl) {
        const editForm = document.getElementById('formEditTransaction');
        const editTitle = document.getElementById('edit_title');
        const editType = document.getElementById('edit_type');
        const editCategory = document.getElementById('edit_category_id');
        const editAmount = document.getElementById('edit_amount');
        const editDate = document.getElementById('edit_transaction_date');
        const editDesc = document.getElementById('edit_description');

        document.querySelectorAll('.btn-edit-transaction').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                const type = this.getAttribute('data-type');
                const categoryId = this.getAttribute('data-category-id');
                const amount = this.getAttribute('data-amount');
                const date = this.getAttribute('data-date');
                const desc = this.getAttribute('data-desc');

                if (editForm) {
                    editForm.action = `/transactions/${id}`;
                }
                if (editTitle) editTitle.value = title || '';
                if (editType) {
                    editType.value = type;
                    // Trigger custom switcher event if available
                    if (window.editCategorySwitcher) {
                        window.editCategorySwitcher(type, categoryId);
                    } else if (editCategory) {
                        editCategory.value = categoryId;
                    }
                }
                if (editAmount) {
                    editAmount.value = new Intl.NumberFormat('id-ID').format(Math.round(amount));
                }
                if (editDate) editDate.value = date || '';
                if (editDesc) editDesc.value = desc || '';
            });
        });
    }

    // 5. Delete Confirmation Modal Handler
    const deleteModalEl = document.getElementById('modalDeleteTransaction');
    if (deleteModalEl) {
        const deleteForm = document.getElementById('formDeleteTransaction');
        const deleteItemTitle = document.getElementById('deleteItemTitle');

        document.querySelectorAll('.btn-delete-transaction').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');

                if (deleteForm) {
                    deleteForm.action = `/transactions/${id}`;
                }
                if (deleteItemTitle) {
                    deleteItemTitle.textContent = `"${title}"`;
                }
            });
        });
    }

    // 6. Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });
});

