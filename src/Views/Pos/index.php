<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StoreManager | Terminal Ventes / POS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --panel-bg: rgba(22, 30, 49, 0.65);
            --border-color: rgba(45, 212, 191, 0.12);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #2dd4bf;
            --accent-glow: rgba(45, 212, 191, 0.1);
            --success: #34d399;
            --danger: #f87171;
            --warning: #fbbf24;
            --font-family: 'Plus Jakarta Sans', sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: var(--font-family);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        .app-container {
            width: 100%;
            max-width: 100%;
            padding: 24px;
        }

        /* Top Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(8, 12, 24, 0.7);
            border: 1px solid var(--border-color);
            padding: 16px 24px;
            border-radius: 20px;
            margin-bottom: 24px;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }

        .nav-logo { font-size: 20px; font-weight: 800; display: flex; align-items: center; gap: 10px; }
        .nav-logo span { color: var(--accent); }

        /* Toast notification structure */
        .toast-box {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            background: rgba(13, 20, 38, 0.95);
            border: 1px solid var(--border-color);
            padding: 16px 24px;
            border-radius: 16px;
            color: white;
            font-size: 13px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease forwards;
        }
        .toast.success { border-left: 4px solid var(--success); }
        .toast.danger { border-left: 4px solid var(--danger); }
        .toast.warning { border-left: 4px solid var(--warning); }

        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Layout panels */
        .panel-card {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
            margin-bottom: 24px;
        }

        .panel-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 16px;
            border-left: 4px solid var(--accent);
            padding-left: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Forms & Inputs */
        .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
        .form-group label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

        .form-control {
            background: rgba(8, 12, 24, 0.7);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            color: white;
            font-family: var(--font-family);
            outline: none;
            font-size: 13px;
            transition: all 0.3s;
            width: 100%;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 12px rgba(45, 212, 191, 0.15); }

        /* Tactile Numerical Keypad */
        .keypad-container {
            background: #090e1a;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 12px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 12px;
        }

        .keypad-btn {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            padding: 10px 0;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }
        .keypad-btn:hover { background: var(--accent-glow); color: var(--accent); }
        .keypad-btn:active { transform: scale(0.95); }

        /* Submit Button */
        .btn-submit {
            background: linear-gradient(135deg, var(--accent) 0%, #0d9488 100%);
            color: #0b0f19;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-weight: 800;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
        .btn-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(45, 212, 191, 0.3); }

        /* Tables & Lists */
        .debt-table { width: 100%; border-collapse: collapse; text-align: left; }
        .debt-table th {
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }
        .debt-table td { padding: 12px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.03); font-size: 13px; }

        .btn-quick-action {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-quick-action:hover { background: var(--accent-glow); border-color: var(--accent); color: var(--accent); }
        .btn-quick-action.danger:hover { background: rgba(248, 113, 113, 0.15); border-color: var(--danger); color: var(--danger); }

        /* Grid d'articles POS */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
            max-height: 520px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .article-card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .article-card:hover {
            border-color: var(--accent);
            background: var(--accent-glow);
            transform: translateY(-2px);
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>
</head>
<body>

    <div class="toast-box" id="toast-box"></div>

    <div class="app-container">
        
        <!-- Header / Navigation -->
        <div class="navbar">
            <div class="nav-logo">
                <span>📦</span> StoreManager Pro <small style="font-size: 11px; color: var(--text-muted); margin-left: 8px;">| Point de Vente</small>
            </div>
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="text-align: right;">
                    <div style="font-size: 12px; font-weight: 800; color: var(--accent);">Session Caisse</div>
                    <div style="font-size: 10px; color: var(--text-muted);"><?= date('d/m/Y H:i') ?></div>
                </div>
            </div>
        </div>

        <!-- POS Workspace Layout -->
        <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px; align-items: start;">
            
            <!-- COLONNE GAUCHE : Recherche & Catalogue d'articles -->
            <div class="panel-card">
                <div class="panel-title">
                    <span>🛒 Catalogue Articles</span>
                    <input type="text" id="pos-search" class="form-control" placeholder="Rechercher nom ou code-barres..." style="width: 250px; padding: 6px 12px; font-size: 12px;">
                </div>

                <div class="articles-grid" id="articles-grid">
                    <?php if (empty($articles)): ?>
                        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px 0;">
                            Aucun article disponible en stock.
                        </div>
                    <?php else: ?>
                        <?php foreach ($articles as $article): ?>
                            <div class="article-card pos-item-card" 
                                 data-id="<?= $article['id'] ?>" 
                                 data-libelle="<?= htmlspecialchars($article['libelle'], ENT_QUOTES) ?>" 
                                 data-prix="<?= $article['prix_unitaire'] ?>" 
                                 data-stock="<?= $article['quantite_stock'] ?>">
                                <div>
                                    <div style="font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 4px;"><?= htmlspecialchars($article['libelle']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);">Stock: <span style="color: var(--accent); font-weight: 700;"><?= $article['quantite_stock'] ?></span></div>
                                </div>
                                <div style="margin-top: 12px; font-size: 14px; font-weight: 800; color: var(--success); text-align: right;">
                                    <?= number_format($article['prix_unitaire'], 0, ',', ' ') ?> F
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- COLONNE DROITE : Client, Panier & Saisie Tactile -->
            <div class="panel-card">
                <div class="panel-title">
                    <span>🧾 Transaction en cours</span>
                </div>

                <!-- Sélection du Client -->
                <div class="form-group">
                    <label for="pos-client-select">Client (Limites & Encours Crédit)</label>
                    <select id="pos-client-select" class="form-control">
                        <option value="">-- Sélectionner un client --</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['id'] ?>" 
                                    data-solde="<?= $client['solde_compte'] ?>" 
                                    data-limite="<?= $client['limite_credit'] ?? 'null' ?>">
                                <?= htmlspecialchars($client['nom'] . ' ' . $client['prenom']) ?> 
                                (Solde: <?= number_format($client['solde_compte'], 0, ',', ' ') ?> F | Max: <?= $client['limite_credit'] !== null ? number_format($client['limite_credit'], 0, ',', ' ') . ' F' : 'Illimité' ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Informations Crédit / Solde du Client Sélectionné -->
                <div id="client-credit-info" style="display: none; background: rgba(8, 12, 24, 0.5); border: 1px solid var(--border-color); border-radius: 12px; padding: 10px 14px; margin-bottom: 16px; font-size: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span style="color: var(--text-muted);">Solde actuel :</span>
                        <span id="info-solde" style="font-weight: 700;">0 F</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Nouveau solde estimé :</span>
                        <span id="info-nouveau-solde" style="font-weight: 800; color: var(--accent);">0 F</span>
                    </div>
                </div>

                <!-- Table Panier -->
                <div style="max-height: 240px; overflow-y: auto; margin-bottom: 16px;">
                    <table class="debt-table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th style="width: 70px;">Qté</th>
                                <th style="text-align: right;">Total</th>
                                <th style="width: 30px;"></th>
                            </tr>
                        </thead>
                        <tbody id="pos-cart-tbody">
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px 0;">
                                    Panier vide
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pavé Numérique Tactile (pour ajuster facilement la quantité sélectionnée) -->
                <div style="margin-bottom: 16px;">
                    <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Saisie Rapide Quantité</div>
                    <div class="keypad-container">
                        <button type="button" class="keypad-btn" onclick="pressKeypad('1')">1</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('2')">2</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('3')">3</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('4')">4</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('5')">5</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('6')">6</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('7')">7</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('8')">8</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('9')">9</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('C')" style="color: var(--danger);">C</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('0')">0</button>
                        <button type="button" class="keypad-btn" onclick="pressKeypad('BACK')" style="color: var(--warning);">⌫</button>
                    </div>
                </div>

                <!-- Total & Action -->
                <div style="border-top: 1px solid var(--border-color); padding-top: 16px; margin-top: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <span style="font-size: 14px; font-weight: 700; color: var(--text-muted);">MONTANT TOTAL</span>
                        <span id="pos-cart-total" style="font-size: 24px; font-weight: 800; color: var(--success);">0 F</span>
                    </div>

                    <button id="btn-validate-vente" class="btn-submit" disabled onclick="validerTransactionVente()">
                        Valider la Vente (SQL Transaction)
                    </button>
                </div>

            </div>

        </div>
    </div>

    <!-- JavaScript Logique du POS -->
    <script>
        let panier = [];
        let selectedItemIndex = null;

        document.addEventListener('DOMContentLoaded', () => {
            // 1. Recherche d'articles dynamique
            const searchInput = document.getElementById('pos-search');
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                document.querySelectorAll('.pos-item-card').forEach(card => {
                    const libelle = card.getAttribute('data-libelle').toLowerCase();
                    card.style.display = libelle.includes(query) ? 'flex' : 'none';
                });
            });

            // 2. Clic sur un article pour l'ajouter au panier
            document.querySelectorAll('.pos-item-card').forEach(card => {
                card.addEventListener('click', () => {
                    const id = parseInt(card.getAttribute('data-id'));
                    const libelle = card.getAttribute('data-libelle');
                    const prix = parseFloat(card.getAttribute('data-prix'));
                    const stock = parseInt(card.getAttribute('data-stock'));

                    const existingIndex = panier.findIndex(item => item.article_id === id);

                    if (existingIndex !== -1) {
                        if (panier[existingIndex].quantite + 1 > stock) {
                            showToast(`Stock insuffisant ! Disponible: ${stock}`, 'warning');
                            return;
                        }
                        panier[existingIndex].quantite++;
                        selectedItemIndex = existingIndex;
                    } else {
                        panier.push({
                            article_id: id,
                            libelle: libelle,
                            prix_unitaire: prix,
                            quantite: 1,
                            stock: stock
                        });
                        selectedItemIndex = panier.length - 1;
                    }

                    renderCart();
                });
            });

            // 3. Changement de client -> maj des indicateurs de crédit
            document.getElementById('pos-client-select').addEventListener('change', updateCreditInfo);
        });

        // 4. Pavé Numérique Tactile
        function pressKeypad(key) {
            if (selectedItemIndex === null || !panier[selectedItemIndex]) {
                showToast("Sélectionnez une ligne dans le panier pour modifier sa quantité.", "warning");
                return;
            }

            let currentQtyStr = panier[selectedItemIndex].quantite.toString();
            const maxStock = panier[selectedItemIndex].stock;

            if (key === 'C') {
                panier[selectedItemIndex].quantite = 1;
            } else if (key === 'BACK') {
                currentQtyStr = currentQtyStr.slice(0, -1);
                panier[selectedItemIndex].quantite = currentQtyStr === '' ? 1 : parseInt(currentQtyStr);
            } else {
                let newQtyStr = currentQtyStr === '1' ? key : currentQtyStr + key;
                let newQty = parseInt(newQtyStr);

                if (newQty > maxStock) {
                    showToast(`Stock maximum atteint (${maxStock})`, 'warning');
                    newQty = maxStock;
                }
                panier[selectedItemIndex].quantite = newQty > 0 ? newQty : 1;
            }

            renderCart();
        }

        // 5. Rendu dynamique de la table panier
        function renderCart() {
            const tbody = document.getElementById('pos-cart-tbody');
            const totalDisplay = document.getElementById('pos-cart-total');
            const btnValidate = document.getElementById('btn-validate-vente');
            const clientSelect = document.getElementById('pos-client-select');

            if (panier.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px 0;">Panier vide</td></tr>`;
                totalDisplay.textContent = '0 F';
                btnValidate.disabled = true;
                selectedItemIndex = null;
                updateCreditInfo();
                return;
            }

            tbody.innerHTML = '';
            let total = 0;

            panier.forEach((item, index) => {
                const lineTotal = item.quantite * item.prix_unitaire;
                total += lineTotal;

                const isSelected = index === selectedItemIndex;
                const tr = document.createElement('tr');
                tr.style.background = isSelected ? 'rgba(45, 212, 191, 0.1)' : 'transparent';
                tr.style.cursor = 'pointer';

                tr.innerHTML = `
                    <td onclick="selectCartRow(${index})">
                        <div style="font-weight: 700; color: #fff;">${escapeHtml(item.libelle)}</div>
                        <small style="color: var(--text-muted);">${item.prix_unitaire.toLocaleString('fr-FR')} F/u</small>
                    </td>
                    <td onclick="selectCartRow(${index})">
                        <span style="font-weight: 800; color: var(--accent);">${item.quantite}</span>
                    </td>
                    <td style="text-align: right; font-weight: 700; color: var(--success);" onclick="selectCartRow(${index})">
                        ${lineTotal.toLocaleString('fr-FR')} F
                    </td>
                    <td style="text-align: right;">
                        <button class="btn-quick-action danger" onclick="removeCartItem(${index}, event)">✕</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            totalDisplay.textContent = `${total.toLocaleString('fr-FR')} F`;
            btnValidate.disabled = !clientSelect.value || panier.length === 0;

            updateCreditInfo();
        }

        function selectCartRow(index) {
            selectedItemIndex = index;
            renderCart();
        }

        function removeCartItem(index, event) {
            event.stopPropagation();
            panier.splice(index, 1);
            selectedItemIndex = panier.length > 0 ? 0 : null;
            renderCart();
        }

        function updateCreditInfo() {
            const select = document.getElementById('pos-client-select');
            const infoBox = document.getElementById('client-credit-info');
            const btnValidate = document.getElementById('btn-validate-vente');

            if (!select.value) {
                infoBox.style.display = 'none';
                btnValidate.disabled = true;
                return;
            }

            const option = select.options[select.selectedIndex];
            const solde = parseFloat(option.getAttribute('data-solde')) || 0;
            const limiteRaw = option.getAttribute('data-limite');
            const limite = limiteRaw === 'null' ? null : parseFloat(limiteRaw);

            let totalPanier = panier.reduce((acc, item) => acc + (item.quantite * item.prix_unitaire), 0);
            let nouveauSolde = solde + totalPanier;

            document.getElementById('info-solde').textContent = `${solde.toLocaleString('fr-FR')} F`;
            
            const nouveauSoldeElem = document.getElementById('info-nouveau-solde');
            nouveauSoldeElem.textContent = `${nouveauSolde.toLocaleString('fr-FR')} F`;

            if (limite !== null && nouveauSolde > limite) {
                nouveauSoldeElem.style.color = 'var(--danger)';
                showToast(`Alerte: La vente dépasse la limite de crédit (${limite.toLocaleString('fr-FR')} F)`, 'warning');
            } else {
                nouveauSoldeElem.style.color = 'var(--accent)';
            }

            infoBox.style.display = 'block';
            btnValidate.disabled = panier.length === 0;
        }

        // 6. Soumission Transactionnelle au serveur (AJAX/Fetch)
        async function validerTransactionVente() {
            const clientSelect = document.getElementById('pos-client-select');
            const btnValidate = document.getElementById('btn-validate-vente');

            if (!clientSelect.value || panier.length === 0) return;

            btnValidate.disabled = true;
            btnValidate.textContent = 'Traitement Transactionnel SQL...';

            const payload = {
                client_id: parseInt(clientSelect.value),
                items: panier.map(item => ({
                    article_id: item.article_id,
                    quantite: item.quantite,
                    prix_unitaire: item.prix_unitaire
                }))
            };

            try {
                const response = await fetch('/pos/valider-vente', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    showToast(`Vente #${result.vente_id} enregistrée avec succès !`, 'success');
                    panier = [];
                    selectedItemIndex = null;
                    renderCart();
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showToast(`Erreur : ${result.message}`, 'danger');
                }
            } catch (error) {
                showToast("Erreur lors de la communication avec le serveur.", 'danger');
            } finally {
                btnValidate.disabled = false;
                btnValidate.textContent = 'Valider la Vente (SQL Transaction)';
            }
        }

        // Helper Toast & Escape
        function showToast(message, type = 'success') {
            const box = document.getElementById('toast-box');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            box.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }

        function escapeHtml(text) {
            return text.replace(/[&<>"']/g, function(m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
            });
        }
    </script>
</body>
</html>