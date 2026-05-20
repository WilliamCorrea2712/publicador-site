<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->name }}</title>
    <style>
        body { margin: 0; font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background: {{ $page->background_color ?? '#f3f4f6' }}; }
        .container { width: min(1100px, 95%); margin: 0 auto; }
        a { color: #1d4ed8; text-decoration: none; }
    </style>
</head>
<body>
    <div style="background: {{ $page->background_color ?? '#f3f4f6' }}; min-height: 100vh;">
        <header style="background: {{ $page->menu_bg_color ?? '#ffffff' }}; border-bottom:1px solid #e5e7eb;">
            <div class="container" style="display:flex; align-items:center; justify-content:space-between; padding:1rem 0;">
                <div><strong style="color: {{ $page->menu_text_color ?? '#111827' }}; font-size: {{ $page->menu_font_size ?? '1rem' }};">{{ $page->name }}</strong></div>
                <nav style="display:flex; gap:1rem;">
                    @foreach($page->menu_items ?? [] as $item)
                        <a href="{{ $item['url'] ?? '#' }}" style="color: {{ $page->menu_text_color ?? '#111827' }}; font-size: {{ $page->menu_font_size ?? '1rem' }};">{{ $item['label'] ?? 'Menu' }}</a>
                    @endforeach
                </nav>
            </div>
        </header>

        @if(!empty($page->content_blocks) && is_array($page->content_blocks) && count($page->content_blocks))
            @foreach($page->content_blocks as $block)
                @if($block['type'] === 'banner')
                    <section style="padding:4rem 0; text-align:center; background: linear-gradient(180deg, rgba(0,0,0,0.2), rgba(0,0,0,0.0)), #111827; @if(!empty($block['images'][0])) background-image:url('{{ $block['images'][0] }}'); background-size:cover; background-position:center; @endif">
                        <div class="container" style="width: min(1100px, 95%); margin: 0 auto;">
                            <div style="max-width: {{ $block['layout'] === 'full' ? '100%' : '760px' }}; margin: 0 auto;">
                                <h1 style="font-size:2.5rem; margin-bottom:1rem; color:#fff;">{{ $block['title'] ?? 'Título do Banner' }}</h1>
                                <p style="font-size:1.1rem; max-width:760px; margin:0 auto 1.5rem; color:#f3f4f6;">{{ $block['subtitle'] ?? '' }}</p>
                                @if(!empty($block['cta_text']))
                                    <a href="{{ $block['cta_url'] ?? '#' }}" style="display:inline-flex; align-items:center; justify-content:center; gap:.5rem; background:#2563eb; color:#fff; padding:.85rem 1.4rem; border-radius:.5rem; text-decoration:none; font-weight:600;">{{ $block['cta_text'] }}</a>
                                @endif
                            </div>
                        </div>
                    </section>
                    @if(!empty($block['images']) && count($block['images']) > 1)
                        <section class="container" style="padding:2rem 0;">
                            <div style="display:grid; gap:1rem; grid-template-columns:repeat(auto-fit, minmax(220px,1fr));">
                                @foreach($block['images'] as $image)
                                    <div style="overflow:hidden; border-radius:.75rem; background:#fff; border:1px solid #e5e7eb;">
                                        <img src="{{ $image }}" alt="Banner image" style="width:100%; height:220px; object-fit:cover; display:block;" />
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @elseif($block['type'] === 'html')
                    <section class="container" style="padding:2rem 0;">
                        <div style="background:#ffffff; border:1px solid #e5e7eb; border-radius:0.75rem; padding:1.5rem; color:#111827;">
                            {!! $block['html'] ?? '' !!}
                        </div>
                    </section>
                @elseif($block['type'] === 'cards')
                    <section class="container" style="padding:2rem 0;">
                        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem; margin-bottom:1.5rem;">
                            <h2 style="font-size:1.8rem; margin:0;">{{ $block['title'] ?? 'Nossos cards' }}</h2>
                            <span style="color:#6b7280;">{{ $block['layout'] === 'horizontal' ? 'Horizontal' : 'Vertical' }}</span>
                        </div>
                        <div style="display:grid; gap:1rem; grid-template-columns:repeat({{ $block['layout'] === 'horizontal' ? 1 : ($block['columns'] ?? 3) }}, minmax(0,1fr));">
                            @foreach($block['cards'] ?? [] as $card)
                                <div style="background:#fff; border:1px solid #e5e7eb; border-radius:0.9rem; overflow:hidden; display:flex; flex-direction:{{ $block['layout'] === 'horizontal' ? 'row' : 'column' }}; gap:0;">
                                    @if(!empty($card['image_url']))
                                        <div style="flex:0 0 {{ $block['layout'] === 'horizontal' ? '240px' : 'auto' }}; min-height:180px; overflow:hidden;">
                                            <img src="{{ $card['image_url'] }}" alt="{{ $card['title'] ?? 'Card' }}" style="width:100%; height:100%; object-fit:cover; display:block;" />
                                        </div>
                                    @endif
                                    <div style="padding:1.25rem; display:flex; flex-direction:column; justify-content:space-between; gap:.75rem;">
                                        <div>
                                            <h3 style="font-size:1.1rem; margin:0 0 .5rem; color:#111827;">{{ $card['title'] ?? '' }}</h3>
                                            <p style="margin:0; color:#4b5563;">{{ $card['description'] ?? '' }}</p>
                                        </div>
                                        <a href="{{ $card['cta_url'] ?? '#' }}" style="display:inline-flex; align-items:center; justify-content:center; padding:.65rem 1rem; background:#2563eb; color:#fff; border-radius:.55rem; text-decoration:none; font-weight:600;">{{ $card['cta_text'] ?? 'Saiba mais' }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        @else
            <section style="padding:4rem 0; text-align:center; background: linear-gradient(180deg, rgba(0,0,0,0.2), rgba(0,0,0,0.0)), #111827; @if($page->banner_image) background-image:url('{{ $page->banner_image }}'); background-size:cover; background-position:center; @endif">
                <div class="container">
                    <h1 style="font-size:{{ $page->banner_title_size ?? '2.25rem' }}; margin-bottom:1rem; color: {{ $page->banner_title_color ?? '#ffffff' }};">{{ $page->banner_title ?? 'Landing Page' }}</h1>
                    <p style="font-size:{{ $page->banner_subtitle_size ?? '1.1rem' }}; max-width:760px; margin:0 auto; color: {{ $page->banner_subtitle_color ?? '#f3f4f6' }};">{{ $page->banner_subtitle ?? 'Cadastre e gere sua landing page pela área interna.' }}</p>
                </div>
            </section>

            <section id="planos" class="container" style="padding:3rem 0;">
                <h2 style="font-size:1.75rem; margin-bottom:1rem;">Planos</h2>
                <div style="display:grid; gap:1rem; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));">
                    @foreach($page->plans ?? [] as $plan)
                        <div style="background:#fff; border:1px solid {{ $page->plan_card_border_color ?? '#e5e7eb' }}; border-radius: {{ $page->plan_card_border_radius ?? '0.5rem' }}; box-shadow: {{ $page->plan_card_shadow ?? '0' }}; overflow:hidden; text-align:center;">
                            <div style="background:{{ $page->plan_title_bg_color ?? '#1d4ed8' }}; color:{{ $page->plan_title_color ?? '#111827' }}; padding:0.75rem; font-weight:700; font-size: {{ $page->plan_title_size ?? '1rem' }};">{{ $plan['title'] ?? 'Plano' }}</div>
                            <div style="background:{{ $page->plan_text_bg_color ?? '#f3f4f6' }}; color:{{ $page->plan_text_color ?? '#374151' }}; padding:1rem; min-height:96px; display:flex; align-items:center; justify-content:center; text-align:center; flex-direction:column; gap:0.3rem; font-size: {{ $page->plan_text_size ?? '0.9rem' }};">
                                <div>{{ $plan['description'] ?? '' }}</div>
                            </div>
                            <div style="background:{{ $page->plan_price_bg_color ?? '#10b981' }}; color:{{ $page->plan_price_color ?? '#000000' }}; padding:0.75rem; font-weight:700; font-size: {{ $page->plan_price_size ?? '1rem' }};">{{ $plan['price'] ?? '' }}</div>
                            <div style="padding:0.75rem; text-align:center;"><a href="{{ route('contratar') }}" style="display:inline-block; margin-top:.4rem; background:#1d4ed8; color:#fff; text-decoration:none; border-radius:.35rem; padding:.4rem .75rem;">Contratar</a></div>
                        </div>
                    @endforeach
                </div>
            </section>

            @if(!empty($page->section_after_plans))
                <section class="container" style="padding:2rem 0; background: {{ $page->section_bg_color ?? '#ffffff' }}; color: {{ $page->section_text_color ?? '#111827' }};">
                    <div style="background:{{ $page->section_bg_color ?? '#ffffff' }}; color: {{ $page->section_text_color ?? '#111827' }}; border:1px solid #e5e7eb; border-radius:0.5rem; padding:1rem;">
                        {!! nl2br(e($page->section_after_plans)) !!}
                    </div>
                </section>
            @endif
        @endif

        <footer id="contato" style="background: {{ $page->footer_bg_color ?? '#111827' }}; color: {{ $page->footer_text_color ?? '#d1d5db' }}; padding:2rem 0;">
            <div class="container" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                <div>
                    <h3 style="color: {{ $page->footer_title_color ?? '#ffffff' }}; margin:0 0 0.25rem;">Contato</h3>
                    <p style="margin:0;">Email: {{ $page->footer_email ?? 'contato@exemplo.com' }}</p>
                    <p style="margin:0;">Telefone: {{ $page->footer_phone ?? '+55 11 99999-9999' }}</p>
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <a href="{{ $page->footer_button_url ?? '#contato' }}" style="background: {{ $page->footer_button_bg_color ?? '#3b82f6' }}; color: {{ $page->footer_button_text_color ?? '#ffffff' }}; border-radius: {{ $page->footer_button_border_radius ?? '0.35rem' }}; padding: 0.5rem 0.9rem; text-decoration:none; font-weight:600;">{{ $page->footer_button_text ?? 'Fale com a gente' }}</a>
                </div>
                <div style="opacity:.8;">Gerado em tempo real pela página de configuração.</div>
            </div>
        </footer>
    </div>
</body>
</html>