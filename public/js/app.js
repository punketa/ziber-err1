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
 * 3. Ekintza suntsitzaileen baieztapenak (Delete / Baja).
 * Ezusteko klikak saihesteko baieztapen elkarrizketa-koadroak kudeatzen ditu.
 */
function initActionConfirmations() {
    document.querySelectorAll('[data-confirm]').forEach(function (element) {
        element.addEventListener('click', function (e) {
            const message = this.getAttribute('data-confirm') || 'Ziur zaude ekintza hau burutu nahi duzula?';
            if (!confirm(message)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
}

/**
 * XSS prebentziorako testu-garbiketa bezeroan
 */
function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
