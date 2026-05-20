<?php

namespace App\Http\Controllers;

use App\Mail\GeneratedPasswordMail;
use App\Models\LandingPage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.contratar');
    }

    public function registerCustomer(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'store_name' => 'required|string|max:255',
        ]);

        $password = Str::random(10);
        $adminEmail = 'william.correa.dev@gmail.com';
        $role = $validated['email'] === $adminEmail || Str::endsWith($validated['email'], '@amlgroup.com.br') ? 'admin' : 'customer';
        $user = User::create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        $slug = Str::slug($validated['store_name']);
        if (empty($slug)) {
            $slug = 'lojinha-' . $user->id;
        }
        $existing = LandingPage::where('slug', $slug)->exists();
        if ($existing) {
            $slug .= '-' . Str::random(4);
        }

        $page = LandingPage::create([
            'user_id' => $user->id,
            'slug' => $slug,
            'name' => $validated['store_name'],
            'banner_title' => 'Boas-vindas à sua loja ' . $validated['store_name'],
            'banner_subtitle' => 'Edite sua página na área do seu painel.',
            'menu_items' => [
                ['label' => 'Home', 'url' => '#'],
                ['label' => 'Planos', 'url' => '#planos'],
                ['label' => 'Contato', 'url' => '#contato'],
            ],
            'plans' => [
                ['title' => 'Básico', 'description' => 'Comece rápido', 'price' => 'R$ 0'],
            ],
            'is_published' => true,
        ]);

        $emailBody = "Olá {$user->name},\n".
            "Sua conta foi criada.\n".
            "Email: {$user->email}\n".
            "Senha: {$password}\n".
            "Acesse: " . url('/login') . "\n".
            "Depois, abra: " . url('/minha-pagina') . "\n";

        Log::info('EMAIL PREVIEW de senha gerada', [
            'to' => $user->email,
            'subject' => 'Seu acesso à plataforma',
            'body' => $emailBody,
        ]);

        try {
            Mail::to($user->email)->send(new GeneratedPasswordMail($user, $password));
            Log::info("Senha gerada e enviada por e-mail", ['email' => $user->email, 'password' => $password]);
        } catch (\Throwable $e) {
            // Registrar no log se não há serviço de e-mail.
            Log::warning("Falha ao enviar e-mail de senha, gravando no log: ", ['email' => $user->email, 'password' => $password, 'error' => $e->getMessage()]);
        }

        Log::info("Cadastro concluído com senha gerada", ['email' => $user->email, 'password' => $password]);

        return redirect()->route('login')->with('success', "Cadastro realizado. Verifique seu e-mail para a senha e faça login.");
    }

    public function index()
    {
        $user = Auth::user();
        $pages = LandingPage::where('user_id', $user->id)->orderByDesc('updated_at')->get();

        return view('pages.index', [
            'pages' => $pages,
        ]);
    }

    public function create()
    {
        return view('pages.form', [
            'page' => new LandingPage(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:landing_pages,slug',
            'is_published' => 'nullable|boolean',
            'content_blocks' => 'nullable|string',
        ]);

        $page = LandingPage::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'is_published' => $request->has('is_published'),
            'content_blocks' => $this->normalizeContentBlocks($request),
            'menu_items' => [
                ['label' => 'Home', 'url' => '#'],
                ['label' => 'Planos', 'url' => '#planos'],
                ['label' => 'Contato', 'url' => '#contato'],
            ],
        ]);

        return redirect()->route('landing.page.edit', $page)->with('success', 'Página criada com sucesso.');
    }

    public function editPage(LandingPage $page)
    {
        $user = Auth::user();
        if ($page->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        return view('pages.form', [
            'page' => $page,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, LandingPage $page)
    {
        $user = Auth::user();
        if ($page->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:landing_pages,slug,'.$page->id,
            'is_published' => 'nullable|boolean',
            'content_blocks' => 'nullable|string',
        ]);

        $page->fill([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'is_published' => $request->has('is_published'),
            'content_blocks' => $this->normalizeContentBlocks($request, $page),
        ]);

        $page->save();

        return redirect()->route('landing.page.edit', $page)->with('success', 'Página atualizada com sucesso.');
    }

    private function normalizeContentBlocks(Request $request, LandingPage $page = null): array
    {
        $blocks = json_decode($request->input('content_blocks', '[]'), true);
        if (! is_array($blocks)) {
            $blocks = [];
        }

        $bannerUploads = $request->file('block_images', []);
        $cardUploads = $request->file('card_images', []);

        foreach ($blocks as $index => &$block) {
            if (! is_array($block) || empty($block['type'])) {
                continue;
            }

            $block['type'] = $block['type'] ?? 'html';

            if ($block['type'] === 'banner') {
                $block['images'] = $block['images'] ?? [];
                if (isset($bannerUploads[$index]) && is_array($bannerUploads[$index])) {
                    foreach ($bannerUploads[$index] as $file) {
                        if ($file && $file->isValid()) {
                            $path = $file->store('landing/blocks', 'public');
                            $block['images'][] = Storage::url($path);
                        }
                    }
                }
                $block['images'] = array_values(array_filter($block['images'] ?? []));
                $block['layout'] = in_array($block['layout'] ?? '', ['container', 'full']) ? $block['layout'] : 'container';
                $block['cta_text'] = trim($block['cta_text'] ?? '');
                $block['cta_url'] = trim($block['cta_url'] ?? '#');
                $block['title'] = trim($block['title'] ?? '');
                $block['subtitle'] = trim($block['subtitle'] ?? '');
            }

            if ($block['type'] === 'html') {
                $block['html'] = $block['html'] ?? '';
            }

            if ($block['type'] === 'cards') {
                $block['layout'] = in_array($block['layout'] ?? '', ['horizontal', 'vertical']) ? $block['layout'] : 'vertical';
                $block['columns'] = in_array((int) ($block['columns'] ?? 3), [1,2,3,4,5,6]) ? (int) $block['columns'] : 3;
                $block['cards'] = array_values(array_filter($block['cards'] ?? [], function ($card) {
                    return is_array($card) && ! empty(trim($card['title'] ?? ''));
                }));

                foreach ($block['cards'] as $cardIndex => &$card) {
                    $card['title'] = trim($card['title'] ?? '');
                    $card['description'] = trim($card['description'] ?? '');
                    $card['cta_text'] = trim($card['cta_text'] ?? 'Ver mais');
                    $card['cta_url'] = trim($card['cta_url'] ?? '#');
                    $card['image_url'] = trim($card['image_url'] ?? '');

                    if (isset($cardUploads[$index][$cardIndex]) && $cardUploads[$index][$cardIndex] && $cardUploads[$index][$cardIndex]->isValid()) {
                        $path = $cardUploads[$index][$cardIndex]->store('landing/cards', 'public');
                        $card['image_url'] = Storage::url($path);
                    }
                }
            }
        }

        return array_values($blocks);
    }

    public function showPublic()
    {
        if (! Schema::hasTable('landing_pages')) {
            return view('welcome');
        }

        if (Auth::check()) {
            $userPage = LandingPage::where('user_id', Auth::id())->where('is_published', true)->first();
            if ($userPage) {
                return view('landing', ['page' => $userPage]);
            }
        }

        $page = LandingPage::where('is_published', true)->whereNull('user_id')->first();
        if (! $page) {
            return view('welcome');
        }

        return view('landing', ['page' => $page]);
    }

    public function showCustomerPage($slug)
    {
        $page = LandingPage::where('slug', $slug)->firstOrFail();

        if (! $page->is_published) {
            abort(404);
        }

        return view('landing', ['page' => $page]);
    }
}

