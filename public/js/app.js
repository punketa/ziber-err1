/**
 * IkasKude - JavaScript Funtzio Orokorrak
 * Rúbricaren baldintzak betetzeko:
 * - OF:4: JavaScript funtzioak modu optimoan (DOM manipulazioa, gertaera entzuleak)
 * - ESJ:3: Usabilitatea, interaktibitatea eta errore/baieztapen kudeaketa bezeroan
 */

document.addEventListener('DOMContentLoaded', function () {
    initTableLiveFilter();
    initAlertAutoDismiss();
    initActionConfirmations();
});

/**
 * 1. Taulen denbora errealeko bilaketa / iragazkia (Live Table Filter).
 * Erabiltzaileak idatzi ahala taulako errenkadak iragazten ditu orria berriz kargatu gabe.
 */
function initTableLiveFilter() {
    const searchInputs = document.querySelectorAll('[data-table-filter]');

    searchInputs.forEach(function (input) {
        const targetSelector = input.getAttribute('data-table-filter');
        const targetTable = document.querySelector(targetSelector);

        if (!targetTable) return;

        const tbody = targetTable.querySelector('tbody');
        if (!tbody) return;

        input.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows = tbody.querySelectorAll('tr:not(.no-filter-match)');
            let visibleCount = 0;

            rows.forEach(function (row) {
                // Errenkadako testu osoa bilatu (botoien testua alde batera utzita ahal bada)
                const text = row.innerText.toLowerCase();
                const matches = text.includes(query);

                if (matches) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Emaitzarik ez badago mezu lagungarri bat erakutsi
            let noMatchRow = tbody.querySelector('.no-filter-match');
            if (visibleCount === 0 && query !== '') {
                if (!noMatchRow) {
                    noMatchRow = document.createElement('tr');
                    noMatchRow.className = 'no-filter-match text-center text-muted py-4';
                    const colSpan = targetTable.querySelectorAll('thead th').length || 6;
                    noMatchRow.innerHTML = `<td colspan="${colSpan}" class="py-4">
                        <i class="bi bi-search me-2"></i>Ez da aurkitu "<strong>${escapeHtml(query)}</strong>" bilaketarekin bat datorrenik.
                    </td>`;
                    tbody.appendChild(noMatchRow);
                } else {
                    noMatchRow.style.display = '';
                    noMatchRow.querySelector('strong').textContent = query;
                }
            } else if (noMatchRow) {
                noMatchRow.style.display = 'none';
            }
        });
    });
}

/**
 * 2. Jakinarazpen mezuen (alert) auto-desagerpena (Auto-dismiss).
 * Erabiltzailearen esperientzia (UX) hobetzeko, arrakasta mezuak 4.5 segundora automatikoki ezkutatzen dira.
 */
function initAlertAutoDismiss() {
    const successAlerts = document.querySelectorAll('.alert-success, .alert-info');

    successAlerts.forEach(function (alert) {
        // Erabiltzaileak sagua gainean badauka, ez kendu
        let timeoutId = setTimeout(function () {
            dismissAlert(alert);
        }, 4500);

        alert.addEventListener('mouseenter', function () {
            clearTimeout(timeoutId);
        });

        alert.addEventListener('mouseleave', function () {
            timeoutId = setTimeout(function () {
                dismissAlert(alert);
            }, 2500);
        });
    });
}

/**
 * Alerta bat leunki ezkutatzen duen funtzio laguntzailea
 */
function dismissAlert(alertElement) {
    if (!alertElement || !alertElement.parentNode) return;

    alertElement.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
    alertElement.style.opacity = '0';
    alertElement.style.transform = 'translateY(-8px)';

    setTimeout(function () {
        if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertElement);
            bsAlert.close();
        } else {
            alertElement.remove();
        }
    }, 400);
}

/**
 * 3. Ekintza suntsitzaileen eta eragiketa garrantzitsuen baieztapenak (SweetAlert2 bidez).
 * Ezusteko klikak saihesteko SweetAlert2 elkarrizketa-koadro interaktiboak kudeatzen ditu.
 */
function initActionConfirmations() {
    // Retrokompatibilitatea: inline onclick="return confirm('...')" dutenak automatikoki data-confirm bihurtu
    document.querySelectorAll('[onclick*="confirm("]').forEach(function (el) {
        const onclickStr = el.getAttribute('onclick');
        const match = onclickStr.match(/confirm\(\s*(?:'|")(.+?)(?:'|")\s*\)/);
        if (match && match[1]) {
            el.setAttribute('data-confirm', match[1].replace(/\\'/g, "'").replace(/\\"/g, '"'));
            el.removeAttribute('onclick');
        }
    });

    // Gertaera entzule delegatua data-confirm atributua duten elementuentzat
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-confirm]');
        if (!trigger) return;

        // Botoia SweetAlert bidez dagoeneko berretsi bada, utzi bidaltzen
        if (trigger.dataset.confirmed === 'true') {
            delete trigger.dataset.confirmed;
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        const message = trigger.getAttribute('data-confirm') || 'Ziur zaude ekintza hau burutu nahi duzula?';
        const form = trigger.closest('form');
        const isDanger = trigger.classList.contains('btn-danger') || 
                         trigger.classList.contains('btn-outline-danger') ||
                         trigger.classList.contains('text-danger');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Ziur zaude?',
                text: message,
                icon: isDanger ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: isDanger ? '#dc3545' : '#563F98',
                cancelButtonColor: '#6c757d',
                confirmButtonText: isDanger ? 'Bai, ezabatu' : 'Bai, aurrera',
                cancelButtonText: 'Utzi',
                reverseButtons: true,
                focusCancel: isDanger
            }).then(function (result) {
                if (result.isConfirmed) {
                    if (form) {
                        trigger.dataset.confirmed = 'true';
                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit(trigger.type === 'submit' ? trigger : undefined);
                        } else {
                            form.submit();
                        }
                    } else if (trigger.tagName === 'A' && trigger.href) {
                        window.location.href = trigger.href;
                    }
                }
            });
        } else {
            // SweetAlert liburutegirik ez balego, nabigatzailearen confirm() estandarra erabili
            if (confirm(message)) {
                if (form) {
                    trigger.dataset.confirmed = 'true';
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit(trigger.type === 'submit' ? trigger : undefined);
                    } else {
                        form.submit();
                    }
                } else if (trigger.tagName === 'A' && trigger.href) {
                    window.location.href = trigger.href;
                }
            }
        }
    });
}

/**
 * 4. Nabigatzailearen alert() lehenetsiaren ordezkoa SweetAlert2 bidez
 */
window.alert = function (message) {
    if (typeof Swal !== 'undefined') {
        return Swal.fire({
            title: 'Oharra',
            text: message,
            icon: 'info',
            confirmButtonColor: '#563F98',
            confirmButtonText: 'Ados'
        });
    }
    console.warn('Alert:', message);
};

/**
 * Jakinarazpen azkarretarako laguntzaile globalak
 */
window.notifySuccess = function (message, title = 'Bikain!') {
    if (typeof Swal !== 'undefined') {
        return Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            confirmButtonColor: '#563F98',
            timer: 4000,
            timerProgressBar: true
        });
    }
    alert(message);
};

window.notifyError = function (message, title = 'Errorea!') {
    if (typeof Swal !== 'undefined') {
        return Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonColor: '#dc3545'
        });
    }
    alert(message);
};

/**
 * XSS prebentziorako testu-garbiketa bezeroan
 */
function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

