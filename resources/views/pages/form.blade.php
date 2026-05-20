<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $mode === 'create' ? 'Criar Página' : 'Editar Página' }}</h2>
                <p class="text-sm text-gray-500 mt-1">Monte sua página com componentes de banner, HTML aberto e cards responsivos.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('landing.edit') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 text-white rounded-md hover:bg-slate-900" style="background-color:#0f172a;color:#ffffff;">Minhas Páginas</a>
                @if($mode === 'edit' && $page->slug)
                    <a href="{{ route('customer.page', ['slug' => $page->slug]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" style="background-color:#2563eb;color:#ffffff;">Ver Página</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded text-red-700">
                        <p class="font-semibold">Atenção:</p>
                        <ul class="list-disc pl-5 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                <div class="mb-6 rounded-lg border border-dashed border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
                    <strong>Passo 1:</strong> preencha nome e slug da página.
                    <br>
                    <strong>Passo 2:</strong> use os botões acima para adicionar blocos de <strong>Banner</strong>, <strong>Cards</strong> ou <strong>HTML aberto</strong>.
                    <br>
                    <strong>Passo 3:</strong> salve a página e acesse /loja/<em>{{ $page->slug ?? 'seu-slug' }}</em> para visualizar.
                </div>

                <form method="post" action="{{ $mode === 'create' ? route('landing.store') : route('landing.update', $page) }}" enctype="multipart/form-data">
                    @csrf
                    @if($mode === 'edit')
                        @method('PUT')
                    @endif

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome da página</label>
                            <input type="text" name="name" value="{{ old('name', $page->name) }}" class="mt-1 block w-full border-gray-300 rounded-md" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug / URL</label>
                            <div class="mt-1 flex gap-2">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">/loja/</span>
                                <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" class="flex-1 block w-full border-gray-300 rounded-r-md" required />
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Use apenas letras, números e traços.</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }} class="h-4 w-4 border-gray-300 rounded" />
                            Publicar página agora
                        </label>
                    </div>

                    <input type="hidden" name="content_blocks" id="content_blocks" value="{{ old('content_blocks', json_encode($page->content_blocks ?? [])) }}" />

                    @if($mode === 'edit')
                        <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Adicionar blocos</h3>
                                    <p class="text-sm text-gray-600">Use os controles abaixo para montar sua página com banner, cards ou HTML livre.</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" onclick="addBlock('banner')" class="inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700" style="background-color:#0284c7;color:#ffffff;border:none;font-weight:600;">Adicionar Banner</button>
                                    <button type="button" onclick="addBlock('cards')" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700" style="background-color:#4f46e5;color:#ffffff;border:none;font-weight:600;">Adicionar Cards</button>
                                    <button type="button" onclick="addBlock('html')" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700" style="background-color:#059669;color:#ffffff;border:none;font-weight:600;">Adicionar HTML</button>
                                </div>
                            </div>
                        </div>

                        <div id="blocks-container" class="mt-6 space-y-4">
                            <div id="blocks-empty" class="rounded-xl border border-dashed border-gray-300 bg-white p-6 text-center text-gray-500">
                                Nenhum bloco adicionado ainda. Clique em um dos botões acima para começar.
                            </div>
                        </div>
                    @else
                        <div class="mt-6 rounded-xl border border-dashed border-gray-300 bg-white p-6 text-center text-gray-500">
                            <p class="text-lg font-semibold text-gray-900">Primeiro crie sua página</p>
                            <p class="mt-2">Salve o nome e o slug da página para começar. Depois você poderá adicionar blocos de banner, cards ou HTML.</p>
                        </div>
                    @endif

                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700" style="background-color:#2563eb;color:#ffffff;border:none;font-weight:600;font-size:1rem;">Salvar Página</button>
                    </div>
                </form>

                @if($mode === 'edit' && $page->slug)
                    <div class="mt-8 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900">Preview</h3>
                                <p class="text-sm text-gray-600 mt-1">Visualize a página em tempo real com as configurações atuais já salvas.</p>
                            </div>
                            <a href="{{ route('customer.page', ['slug' => $page->slug]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Abrir em nova aba</a>
                        </div>
                        <div class="mt-4 rounded-lg overflow-hidden border border-gray-200" style="min-height: 900px;">
                            <iframe src="{{ route('customer.page', ['slug' => $page->slug]) }}" frameborder="0" class="w-full" style="height:900px;"></iframe>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        const blocksInput = document.getElementById('content_blocks');
        const blocksContainer = document.getElementById('blocks-container');
        let blocks = [];

        try {
            const current = JSON.parse(blocksInput.value || '[]');
            blocks = Array.isArray(current) ? current : [];
        } catch (error) {
            blocks = [];
        }

        const defaultBlock = (type) => {
            if (type === 'banner') {
                return {
                    type: 'banner',
                    layout: 'container',
                    title: 'Bem-vindo à página',
                    subtitle: 'Escreva o subtítulo do seu banner aqui.',
                    cta_text: 'Saiba mais',
                    cta_url: '#',
                    images: [],
                };
            }
            if (type === 'cards') {
                return {
                    type: 'cards',
                    layout: 'vertical',
                    columns: 3,
                    cards: [
                        { title: 'Título do card', description: 'Descrição breve do card.', cta_text: 'Saiba mais', cta_url: '#', image_url: '' },
                        { title: 'Título do card', description: 'Descrição breve do card.', cta_text: 'Saiba mais', cta_url: '#', image_url: '' },
                        { title: 'Título do card', description: 'Descrição breve do card.', cta_text: 'Saiba mais', cta_url: '#', image_url: '' },
                    ],
                };
            }
            return {
                type: 'html',
                html: '<p>Insira seu HTML livre aqui.</p>',
            };
        };

        const saveBlocks = () => {
            blocksInput.value = JSON.stringify(blocks);
        };

        const addBlock = (type) => {
            blocks.push(defaultBlock(type));
            renderBlocks();
        };

        const removeBlock = (index) => {
            blocks.splice(index, 1);
            renderBlocks();
        };

        const updateBlockField = (index, key, value) => {
            if (! blocks[index]) return;
            blocks[index][key] = value;
            saveBlocks();
        };

        const updateCardField = (blockIndex, cardIndex, key, value) => {
            if (! blocks[blockIndex] || ! blocks[blockIndex].cards?.[cardIndex]) return;
            blocks[blockIndex].cards[cardIndex][key] = value;
            saveBlocks();
        };

        const addCard = (blockIndex) => {
            if (! blocks[blockIndex] || ! Array.isArray(blocks[blockIndex].cards)) return;
            blocks[blockIndex].cards.push({ title: '', description: '', cta_text: 'Saiba mais', cta_url: '#', image_url: '' });
            renderBlocks();
        };

        const removeCard = (blockIndex, cardIndex) => {
            if (! blocks[blockIndex] || ! Array.isArray(blocks[blockIndex].cards)) return;
            blocks[blockIndex].cards.splice(cardIndex, 1);
            renderBlocks();
        };

        const moveBannerImage = (blockIndex, imageIndex, delta) => {
            const block = blocks[blockIndex];
            if (! block || ! Array.isArray(block.images)) return;
            const nextIndex = imageIndex + delta;
            if (nextIndex < 0 || nextIndex >= block.images.length) return;
            const [moved] = block.images.splice(imageIndex, 1);
            block.images.splice(nextIndex, 0, moved);
            renderBlocks();
        };

        const removeBannerImage = (blockIndex, imageIndex) => {
            const block = blocks[blockIndex];
            if (! block || ! Array.isArray(block.images)) return;
            block.images.splice(imageIndex, 1);
            renderBlocks();
        };

        const renderBlocks = () => {
            if (!blocksContainer) {
                return;
            }

            if (! blocks.length) {
                blocksContainer.innerHTML = '<div id="blocks-empty" class="rounded-xl border border-dashed border-gray-300 bg-white p-6 text-center text-gray-500">Nenhum bloco adicionado ainda. Clique em um dos botões acima para começar.</div>';
                saveBlocks();
                return;
            }

            blocksContainer.innerHTML = blocks.map((block, index) => {
                if (block.type === 'banner') {
                    return `
                        <section class="rounded-xl border border-gray-200 p-4 bg-slate-50">
                            <div class="flex items-center justify-between gap-4 mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold">Banner</h3>
                                    <p class="text-sm text-gray-500">Configuração de banner com layout, textos e imagens.</p>
                                </div>
                                <button type="button" onclick="removeBlock(${index})" class="text-red-600 hover:text-red-800">Remover bloco</button>
                            </div>
                            <div class="grid gap-4 lg:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Titulo do banner</label>
                                    <input type="text" value="${escapeHtml(block.title)}" onchange="updateBlockField(${index}, 'title', this.value)" class="mt-1 block w-full border-gray-300 rounded-md" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Subtítulo do banner</label>
                                    <input type="text" value="${escapeHtml(block.subtitle)}" onchange="updateBlockField(${index}, 'subtitle', this.value)" class="mt-1 block w-full border-gray-300 rounded-md" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Texto do botão CTA</label>
                                    <input type="text" value="${escapeHtml(block.cta_text)}" onchange="updateBlockField(${index}, 'cta_text', this.value)" class="mt-1 block w-full border-gray-300 rounded-md" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">URL do botão CTA</label>
                                    <input type="text" value="${escapeHtml(block.cta_url)}" onchange="updateBlockField(${index}, 'cta_url', this.value)" class="mt-1 block w-full border-gray-300 rounded-md" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Largura do banner</label>
                                    <select onchange="updateBlockField(${index}, 'layout', this.value)" class="mt-1 block w-full border-gray-300 rounded-md">
                                        <option value="container" ${block.layout === 'container' ? 'selected' : ''}>Container</option>
                                        <option value="full" ${block.layout === 'full' ? 'selected' : ''}>Full width</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Imagens do banner</label>
                                    <input type="file" name="block_images[${index}][]" multiple class="mt-1 block w-full" />
                                    <p class="text-xs text-gray-500 mt-1">Envie imagens para o banner. A ordem de seleção define a ordem inicial.</p>
                                </div>
                            </div>
                            ${Array.isArray(block.images) && block.images.length ? `
                                <div class="mt-4 rounded border border-gray-200 bg-white p-3">
                                    <div class="mb-2 text-sm font-semibold text-gray-700">Imagens existentes</div>
                                    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                        ${block.images.map((image, imageIndex) => `
                                            <div class="rounded-lg border border-gray-200 overflow-hidden bg-white">
                                                <img src="${escapeHtml(image)}" alt="Banner image" class="h-32 w-full object-cover" />
                                                <div class="p-2 text-xs text-gray-600 space-x-1">
                                                    <button type="button" onclick="moveBannerImage(${index}, ${imageIndex}, -1)" class="px-2 py-1 rounded bg-gray-100 hover:bg-gray-200">↑</button>
                                                    <button type="button" onclick="moveBannerImage(${index}, ${imageIndex}, 1)" class="px-2 py-1 rounded bg-gray-100 hover:bg-gray-200">↓</button>
                                                    <button type="button" onclick="removeBannerImage(${index}, ${imageIndex})" class="px-2 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200">Remover</button>
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                        </section>
                    `;
                }

                if (block.type === 'html') {
                    return `
                        <section class="rounded-xl border border-gray-200 p-4 bg-slate-50">
                            <div class="flex items-center justify-between gap-4 mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold">HTML aberto</h3>
                                    <p class="text-sm text-gray-500">Insira HTML personalizado para a página.</p>
                                </div>
                                <button type="button" onclick="removeBlock(${index})" class="text-red-600 hover:text-red-800">Remover bloco</button>
                            </div>
                            <label class="block text-sm font-medium text-gray-700">HTML</label>
                            <textarea onchange="updateBlockField(${index}, 'html', this.value)" class="mt-1 block w-full border-gray-300 rounded-md min-h-[180px]">${escapeHtml(block.html)}</textarea>
                        </section>
                    `;
                }

                if (block.type === 'cards') {
                    return `
                        <section class="rounded-xl border border-gray-200 p-4 bg-slate-50">
                            <div class="flex items-center justify-between gap-4 mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold">Cards</h3>
                                    <p class="text-sm text-gray-500">Componente de cards responsivo com opção horizontal ou vertical.</p>
                                </div>
                                <button type="button" onclick="removeBlock(${index})" class="text-red-600 hover:text-red-800">Remover bloco</button>
                            </div>
                            <div class="grid gap-4 lg:grid-cols-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Layout</label>
                                    <select onchange="updateBlockField(${index}, 'layout', this.value)" class="mt-1 block w-full border-gray-300 rounded-md">
                                        <option value="vertical" ${block.layout === 'vertical' ? 'selected' : ''}>Vertical (vários por linha)</option>
                                        <option value="horizontal" ${block.layout === 'horizontal' ? 'selected' : ''}>Horizontal (um por linha)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Colunas por linha</label>
                                    <select onchange="updateBlockField(${index}, 'columns', this.value)" class="mt-1 block w-full border-gray-300 rounded-md">
                                        ${[1,2,3,4,5,6].map(num => `<option value="${num}" ${block.columns == num ? 'selected' : ''}>${num}</option>`).join('')}
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="addCard(${index})" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Adicionar card</button>
                                </div>
                            </div>

                            ${Array.isArray(block.cards) ? block.cards.map((card, cardIndex) => `
                                <div class="mt-4 rounded-xl border border-gray-200 bg-white p-4">
                                    <div class="flex items-center justify-between gap-4 mb-3">
                                        <span class="font-semibold">Card ${cardIndex + 1}</span>
                                        <button type="button" onclick="removeCard(${index}, ${cardIndex})" class="text-sm text-red-600 hover:text-red-800">Remover</button>
                                    </div>
                                    <div class="grid gap-4 lg:grid-cols-2">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Título</label>
                                            <input type="text" value="${escapeHtml(card.title)}" onchange="updateCardField(${index}, ${cardIndex}, 'title', this.value)" class="mt-1 block w-full border-gray-300 rounded-md" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Descrição</label>
                                            <input type="text" value="${escapeHtml(card.description)}" onchange="updateCardField(${index}, ${cardIndex}, 'description', this.value)" class="mt-1 block w-full border-gray-300 rounded-md" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Texto do botão</label>
                                            <input type="text" value="${escapeHtml(card.cta_text)}" onchange="updateCardField(${index}, ${cardIndex}, 'cta_text', this.value)" class="mt-1 block w-full border-gray-300 rounded-md" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">URL do botão</label>
                                            <input type="text" value="${escapeHtml(card.cta_url)}" onchange="updateCardField(${index}, ${cardIndex}, 'cta_url', this.value)" class="mt-1 block w-full border-gray-300 rounded-md" />
                                        </div>
                                        <div class="lg:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Imagem do card (URL ou upload)</label>
                                            <input type="text" value="${escapeHtml(card.image_url)}" onchange="updateCardField(${index}, ${cardIndex}, 'image_url', this.value)" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="https://..." />
                                            <input type="file" name="card_images[${index}][${cardIndex}]" class="mt-2 block w-full" />
                                        </div>
                                    </div>
                                </div>
                            `).join('') : ''}
                        </section>
                    `;
                }

                return '';
            }).join('');

            saveBlocks();
        };

        const escapeHtml = (unsafe) => {
            if (unsafe === undefined || unsafe === null) return '';
            return String(unsafe)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        renderBlocks();
    </script>
</x-app-layout>
