<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Minha Página') }}</h2>
                @if(!empty($page->slug))
                    <p class="text-xs text-gray-500 mt-1">Slug: <strong>{{ $page->slug }}</strong></p>
                @endif
            </div>
            <div class="flex gap-2 items-center">
                @if(!empty($page->slug))
                    <a href="{{ route('customer.page', ['slug' => $page->slug]) }}" class="text-sm text-blue-500 hover:text-blue-700 border border-blue-200 px-2 py-1 rounded">Ver minha página</a>
                    <a href="{{ url('/loja/'.$page->slug) }}" target="_blank" class="text-xs text-gray-500">{{ url('/loja/'.$page->slug) }}</a>
                @else
                    <a href="{{ route('home') }}" class="text-sm text-blue-500 hover:text-blue-700">Ver página pública</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                <form method="post" action="{{ route('landing.update') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4 border-b border-gray-200 pb-2">
                        <div class="flex gap-2 overflow-x-auto">
                            <button type="button" class="tab-btn px-3 py-1.5 rounded-md bg-blue-600 text-white text-sm" data-tab="menu">Menu</button>
                            <button type="button" class="tab-btn px-3 py-1.5 rounded-md bg-gray-100 text-gray-700 text-sm" data-tab="banner">Banner</button>
                            <button type="button" class="tab-btn px-3 py-1.5 rounded-md bg-gray-100 text-gray-700 text-sm" data-tab="planos">Planos</button>
                            <button type="button" class="tab-btn px-3 py-1.5 rounded-md bg-gray-100 text-gray-700 text-sm" data-tab="sobre">Sobre nós</button>
                            <button type="button" class="tab-btn px-3 py-1.5 rounded-md bg-gray-100 text-gray-700 text-sm" data-tab="footer">Footer</button>
                        </div>
                    </div>

                    <div class="tab-content" id="menu" style="display:block;">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor do menu (fundo)</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="menu_bg_color" value="{{ old('menu_bg_color', $page->menu_bg_color ?? '#ffffff') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="menu_bg_color" value="{{ old('menu_bg_color', $page->menu_bg_color ?? '#ffffff') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor do texto do menu</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="menu_text_color" value="{{ old('menu_text_color', $page->menu_text_color ?? '#111827') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="menu_text_color" value="{{ old('menu_text_color', $page->menu_text_color ?? '#111827') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tamanho do texto do menu</label>
                                <select name="menu_font_size" class="mt-1 block w-full border-gray-300 rounded-md">
                                    @foreach(['0.75rem','0.8rem','0.9rem','1rem','1.1rem','1.2rem'] as $size)
                                        <option value="{{ $size }}" {{ old('menu_font_size', $page->menu_font_size ?? '1rem') === $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Itens do menu (texto|URL por linha)</label>
                            <textarea name="menu_items" class="mt-1 block w-full border-gray-300 rounded-md" rows="3">{{ old('menu_items', collect($page->menu_items ?? [])->map(fn($item)=>($item['label'] ?? '').'|'.($item['url'] ?? '#'))->join("\n")) }}</textarea>
                        </div>
                    </div>

                    <div class="tab-content" id="banner" style="display:none;">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Banner Título</label>
                                <input type="text" name="banner_title" value="{{ old('banner_title', $page->banner_title) }}" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor de fundo da página</label>
                                <input type="text" name="background_color" value="{{ old('background_color', $page->background_color ?? '#f3f4f6') }}" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Banner Título - cor</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="banner_title_color" value="{{ old('banner_title_color', $page->banner_title_color ?? '#ffffff') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="banner_title_color" value="{{ old('banner_title_color', $page->banner_title_color ?? '#ffffff') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Banner Subtítulo - cor</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="banner_subtitle_color" value="{{ old('banner_subtitle_color', $page->banner_subtitle_color ?? '#f3f4f6') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="banner_subtitle_color" value="{{ old('banner_subtitle_color', $page->banner_subtitle_color ?? '#f3f4f6') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tamanho título do banner</label>
                                <select name="banner_title_size" class="mt-1 block w-full border-gray-300 rounded-md">
                                    @foreach(['1.5rem','1.75rem','2rem','2.25rem','2.5rem','3rem'] as $size)
                                        <option value="{{ $size }}" {{ old('banner_title_size', $page->banner_title_size ?? '2.25rem') === $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tamanho subtítulo do banner</label>
                                <select name="banner_subtitle_size" class="mt-1 block w-full border-gray-300 rounded-md">
                                    @foreach(['0.9rem','1rem','1.1rem','1.2rem','1.3rem'] as $size)
                                        <option value="{{ $size }}" {{ old('banner_subtitle_size', $page->banner_subtitle_size ?? '1.1rem') === $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Banner Subtítulo</label>
                            <textarea name="banner_subtitle" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('banner_subtitle', $page->banner_subtitle) }}</textarea>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Imagem de Background (banner)</label>
                            <input type="file" name="banner_image" class="mt-1 block w-full" />
                            @if($page->banner_image)
                                <img src="{{ $page->banner_image }}" class="mt-2 h-28 object-cover rounded" alt="Banner" />
                            @endif
                        </div>
                    </div>

                    <div class="tab-content" id="planos" style="display:none;">
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Planos (Título|Descrição|Preço por linha)</label>
                            <textarea name="plans" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('plans', collect($page->plans ?? [])->map(fn($p)=>($p['title'] ?? '').'|'.($p['description'] ?? '').'|'.($p['price'] ?? ''))->join("\n")) }}</textarea>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor título</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="plan_title_color" value="{{ old('plan_title_color', $page->plan_title_color ?? '#111827') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="plan_title_color" value="{{ old('plan_title_color', $page->plan_title_color ?? '#111827') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor texto</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="plan_text_color" value="{{ old('plan_text_color', $page->plan_text_color ?? '#374151') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="plan_text_color" value="{{ old('plan_text_color', $page->plan_text_color ?? '#374151') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor preço</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="plan_price_color" value="{{ old('plan_price_color', $page->plan_price_color ?? '#000000') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="plan_price_color" value="{{ old('plan_price_color', $page->plan_price_color ?? '#000000') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor fundo título</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="plan_title_bg_color" value="{{ old('plan_title_bg_color', $page->plan_title_bg_color ?? '#1d4ed8') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="plan_title_bg_color" value="{{ old('plan_title_bg_color', $page->plan_title_bg_color ?? '#1d4ed8') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor fundo texto</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="plan_text_bg_color" value="{{ old('plan_text_bg_color', $page->plan_text_bg_color ?? '#f3f4f6') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="plan_text_bg_color" value="{{ old('plan_text_bg_color', $page->plan_text_bg_color ?? '#f3f4f6') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor fundo preço</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="plan_price_bg_color" value="{{ old('plan_price_bg_color', $page->plan_price_bg_color ?? '#10b981') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="plan_price_bg_color" value="{{ old('plan_price_bg_color', $page->plan_price_bg_color ?? '#10b981') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tamanho título</label>
                                <select name="plan_title_size" class="mt-1 block w-full border-gray-300 rounded-md">
                                    @foreach(['0.9rem','1rem','1.1rem','1.2rem','1.3rem','1.5rem'] as $size)
                                        <option value="{{ $size }}" {{ old('plan_title_size', $page->plan_title_size ?? '1rem') === $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tamanho texto</label>
                                <select name="plan_text_size" class="mt-1 block w-full border-gray-300 rounded-md">
                                    @foreach(['0.8rem','0.9rem','1rem','1.1rem','1.2rem'] as $size)
                                        <option value="{{ $size }}" {{ old('plan_text_size', $page->plan_text_size ?? '0.9rem') === $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tamanho preço</label>
                                <select name="plan_price_size" class="mt-1 block w-full border-gray-300 rounded-md">
                                    @foreach(['0.9rem','1rem','1.1rem','1.2rem','1.3rem'] as $size)
                                        <option value="{{ $size }}" {{ old('plan_price_size', $page->plan_price_size ?? '1rem') === $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor borda card</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="plan_card_border_color" value="{{ old('plan_card_border_color', $page->plan_card_border_color ?? '#e5e7eb') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="plan_card_border_color" value="{{ old('plan_card_border_color', $page->plan_card_border_color ?? '#e5e7eb') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Raio card</label>
                                <input type="text" name="plan_card_border_radius" value="{{ old('plan_card_border_radius', $page->plan_card_border_radius ?? '0.5rem') }}" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sombra card</label>
                                <input type="text" name="plan_card_shadow" value="{{ old('plan_card_shadow', $page->plan_card_shadow ?? '0') }}" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="sobre" style="display:none;">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor fundo seção Sobre</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="section_bg_color" value="{{ old('section_bg_color', $page->section_bg_color ?? '#ffffff') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="section_bg_color" value="{{ old('section_bg_color', $page->section_bg_color ?? '#ffffff') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor texto seção Sobre</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="section_text_color" value="{{ old('section_text_color', $page->section_text_color ?? '#111827') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="section_text_color" value="{{ old('section_text_color', $page->section_text_color ?? '#111827') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Texto da seção Sobre</label>
                            <textarea name="section_after_plans" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('section_after_plans', $page->section_after_plans) }}</textarea>
                        </div>
                    </div>

                    <div class="tab-content" id="footer" style="display:none;">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Footer Email</label>
                                <input type="email" name="footer_email" value="{{ old('footer_email', $page->footer_email) }}" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Footer Telefone</label>
                                <input type="text" name="footer_phone" value="{{ old('footer_phone', $page->footer_phone) }}" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor fundo footer</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="footer_bg_color" value="{{ old('footer_bg_color', $page->footer_bg_color ?? '#111827') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="footer_bg_color" value="{{ old('footer_bg_color', $page->footer_bg_color ?? '#111827') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor texto footer</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="footer_text_color" value="{{ old('footer_text_color', $page->footer_text_color ?? '#d1d5db') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="footer_text_color" value="{{ old('footer_text_color', $page->footer_text_color ?? '#d1d5db') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor título footer</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="footer_title_color" value="{{ old('footer_title_color', $page->footer_title_color ?? '#ffffff') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="footer_title_color" value="{{ old('footer_title_color', $page->footer_title_color ?? '#ffffff') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Texto do botão footer</label>
                                <input type="text" name="footer_button_text" value="{{ old('footer_button_text', $page->footer_button_text ?? 'Fale com a gente') }}" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor botão</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="footer_button_bg_color" value="{{ old('footer_button_bg_color', $page->footer_button_bg_color ?? '#3b82f6') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="footer_button_bg_color" value="{{ old('footer_button_bg_color', $page->footer_button_bg_color ?? '#3b82f6') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cor texto botão</label>
                                <div class="mt-1 flex gap-2"><input type="color" name="footer_button_text_color" value="{{ old('footer_button_text_color', $page->footer_button_text_color ?? '#ffffff') }}" class="h-10 w-16 rounded border-gray-300" /><input type="text" name="footer_button_text_color" value="{{ old('footer_button_text_color', $page->footer_button_text_color ?? '#ffffff') }}" class="flex-1 border-gray-300 rounded-md" /></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Raio botão</label>
                                <input type="text" name="footer_button_border_radius" value="{{ old('footer_button_border_radius', $page->footer_button_border_radius ?? '0.35rem') }}" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">URL do botão footer</label>
                            <input type="text" name="footer_button_url" value="{{ old('footer_button_url', $page->footer_button_url ?? '#contato') }}" class="mt-1 block w-full border-gray-300 rounded-md" />
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }} class="h-4 w-4" />
                        <label class="text-sm text-gray-700">Publicar página (disponível em /)</label>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <button type="submit" style="background:#2563eb;color:#fff;border:1px solid #1d4ed8;" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md hover:bg-blue-700">Salvar Landing Page</button>
                        @if(!empty($page->slug))
                            <a href="{{ route('customer.page', ['slug' => $page->slug]) }}" target="_blank" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md border border-blue-300 text-blue-700 bg-white hover:bg-blue-50">Abrir preview em nova aba</a>
                        @endif
                    </div>
                </form>
                @if(!empty($page->slug))
                    <div class="mt-6 p-4 bg-white border border-gray-200 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h3 class="text-lg font-semibold">Preview ao vivo</h3>
                                <p class="text-xs text-gray-500">A visualização mostra a mesma página que será vista em <code>/loja/{{ $page->slug }}</code>.</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-700">Atualize e clique em "Salvar"</span>
                        </div>
                        <iframe id="preview-frame" src="{{ route('customer.page', ['slug' => $page->slug]) }}" class="w-full" style="min-height: 580px; border: 1px solid #d1d5db; border-radius: 0.45rem;"></iframe>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        const tabs = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');
        const setInactive = (btn) => {
            btn.classList.remove('bg-blue-600','text-white','border','border-blue-600');
            btn.classList.add('bg-gray-100','text-gray-700');
            btn.style.background = '#f3f4f6';
            btn.style.color = '#374151';
        };
        const setActive = (btn) => {
            btn.classList.remove('bg-gray-100','text-gray-700');
            btn.classList.add('bg-blue-600','text-white','border','border-blue-600');
            btn.style.background = '#1d4ed8';
            btn.style.color = '#fff';
        };
        tabs.forEach((btn, idx) => {
            if (idx === 0) setActive(btn);
            btn.addEventListener('click', () => {
                tabs.forEach(t => setInactive(t));
                contents.forEach(c => c.style.display = 'none');
                document.getElementById(btn.dataset.tab).style.display = 'block';
                setActive(btn);
            });
        });
    </script>
</x-app-layout>
