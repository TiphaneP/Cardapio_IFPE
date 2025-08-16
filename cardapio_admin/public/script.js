document.addEventListener('DOMContentLoaded', function () {
    // 1. Busca os dados do cardápio
    fetch('../api/cardapio.php')
        .then(response => {
            console.log('Status:', response.status);
            console.log('OK?', response.ok);
            return response.json();
        })
        .then(data => {
            const cardapio = organizeData(data.cardapio);
            console.log('Dados organizados:', cardapio);
            if (data.last_updated) {
                document.getElementById('last-updated').textContent = `Última atualização: ${data.last_updated}`;
            } else {
                document.getElementById('last-updated').textContent = `Última atualização: Não disponível`;
            }
            if (window.lucide) {
                lucide.createIcons();
            }
        })


    fetch('../api/cardapio.php')
        .then(response => {
            if (!response.ok) throw new Error(`Erro HTTP! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const cardapio = organizeData(data.cardapio);
            createTabs(Object.keys(cardapio)); // Cria as abas dos dias
            renderCardapio(cardapio); // Renderiza o conteúdo
            activateCurrentDayTab(); // Ativa a aba do dia atual
        })
        .catch(error => {
            console.error('Erro:', error);
            document.getElementById('tabs-content').innerHTML = `
                <div class="alert alert-danger">
                    Não foi possível carregar o cardápio. Tente recarregar a página.
                </div>
            `;
        });

    // 2. Organiza os dados por dia e turno
    function organizeData(data) {
        return data.reduce((acc, item) => {
            if (!acc[item.dia_semana]) acc[item.dia_semana] = {};
            if (!acc[item.dia_semana][item.turno]) acc[item.dia_semana][item.turno] = [];
            acc[item.dia_semana][item.turno].push(item);
            return acc;
        }, {});
    }

    // 3. Cria as abas de navegação
    function createTabs(dias) {
        const tabsNav = document.getElementById('tabs-nav');

        dias.forEach(dia => {
            const tabButton = document.createElement('button');
            tabButton.className = 'tab-inactive flex-shrink-0 whitespace-nowrap py-4 px-4 border-b-4 font-medium text-sm sm:text-base transition-colors duration-200 if-ring-focus';
            tabButton.dataset.tab = dia.toLowerCase().substring(0, 3); // Ex: "Segunda-feira" -> "seg"
            tabButton.textContent = dia.split('-')[0]; // Ex: "Segunda-feira" -> "Segunda"
            tabButton.setAttribute('role', 'tab');

            tabButton.addEventListener('click', function () {
                activateTab(this);
            });

            tabsNav.appendChild(tabButton);
        });
    }

    // 4. Renderiza o conteúdo do cardápio
    function renderCardapio(cardapio) {
        const tabsContent = document.getElementById('tabs-content');

        for (const [dia, turnos] of Object.entries(cardapio)) {
            const diaContent = document.createElement('div');
            diaContent.className = 'tab-content hidden';
            diaContent.id = dia.toLowerCase().substring(0, 3);
            diaContent.setAttribute('role', 'tabpanel');

            let contentHTML = '';
            for (const [turno, itens] of Object.entries(turnos)) {
                contentHTML += `
                    <div class="mt-8 first:mt-0">
                        <h3 class="flex items-center text-xl font-bold text-gray-800 mb-4 pb-2 border-b">
                            <i data-lucide="${getTurnoIcon(turno)}" class="w-6 h-6 mr-3 if-green-text"></i>
                            ${turno}
                        </h3>
                        <div class="space-y-4">
                            ${itens.map(item => `
                                <div class="pl-2">
                                    <h4 class="font-bold text-base if-green-text sm:text-lg">${item.tipo_prato}</h4>
                                    <p class="text-sm text-gray-600 sm:text-base mt-1">${item.descricao.replace(/\n/g, '<br>')}</p>
                                    ${item.observacoes ? `<p class="text-xs text-gray-500 mt-1"><i>Obs: ${item.observacoes.replace(/\n/g, '<br>')}</i></p>` : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            diaContent.innerHTML = contentHTML;
            tabsContent.appendChild(diaContent);
        }

        // Inicializa os ícones (se estiver usando Lucide Icons)
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    // 5. Ativa uma aba específica
    function activateTab(tabButton) {
        // Desativa todas as abas
        document.querySelectorAll('#tabs-nav button').forEach(btn => {
            btn.classList.replace('tab-active', 'tab-inactive');
            btn.setAttribute('aria-selected', 'false');
        });

        // Esconde todos os conteúdos
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Ativa a aba clicada
        tabButton.classList.replace('tab-inactive', 'tab-active');
        tabButton.setAttribute('aria-selected', 'true');

        // Mostra o conteúdo correspondente
        const contentToShow = document.getElementById(tabButton.dataset.tab);
        contentToShow.classList.remove('hidden');

        // Atualiza ícones (se necessário)
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    // 6. Ativa a aba do dia atual
    function activateCurrentDayTab() {
        const hoje = new Date();
        const diaIndex = hoje.getDay(); // 0=Domingo, 1=Segunda, etc.
        const diasSemana = ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];
        const todayKey = diasSemana[diaIndex];

        const activeTabToday = document.querySelector(`[data-tab="${todayKey}"]`);
        if (activeTabToday) {
            setTimeout(() => activateTab(activeTabToday), 100);
        } else {
            // Se não houver cardápio para hoje, ativa a primeira aba
            const firstTab = document.querySelector('#tabs-nav button');
            if (firstTab) activateTab(firstTab);
        }
    }

    // Helper: Retorna ícone conforme o turno
    function getTurnoIcon(turno) {
        const icons = {
            'Café da Manhã': 'coffee',
            'Almoço': 'utensils',
            'Jantar': 'moon'
        };
        return icons[turno] || 'circle';
    }




});