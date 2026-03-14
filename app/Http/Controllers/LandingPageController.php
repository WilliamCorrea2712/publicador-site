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

    public function edit()
    {
        $user = Auth::user();
        $page = LandingPage::firstWhere('user_id', $user->id);
        if (! $page) {
            $page = LandingPage::create([
                'user_id' => $user->id,
                'slug' => Str::slug($user->name) ?: 'loja-' . $user->id,
                'name' => $user->name . "'s loja",
                'menu_items' => [
                    ['label' => 'Home', 'url' => '#'],
                    ['label' => 'Planos', 'url' => '#planos'],
                    ['label' => 'Contato', 'url' => '#contato'],
                ],
                'plans' => [
                    ['title' => 'Básico', 'description' => 'Até 1 página', 'price' => 'R$ 99'],
                ],
                'is_published' => true,
            ]);
        }

        return view('minha-pagina', ['page' => $page]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $page = LandingPage::firstOrNew(['user_id' => $user->id]);

        if (! $page->slug) {
            $page->slug = Str::slug($user->name) ?: 'loja-' . $user->id;
        }

        Log::info('landing.update called', ['user_id' => optional($user)->id, 'page_id' => optional($page)->id, 'slug' => $page->slug]);

        $validated = $request->validate([
            'banner_title' => 'nullable|string|max:255',
            'banner_subtitle' => 'nullable|string',
            'banner_image' => 'nullable|image|max:2048',
            'background_color' => 'nullable|string|max:7',
            'banner_title_color' => 'nullable|string|max:7',
            'banner_subtitle_color' => 'nullable|string|max:7',
            'banner_title_size' => 'nullable|string|max:10',
            'banner_subtitle_size' => 'nullable|string|max:10',
            'menu_bg_color' => 'nullable|string|max:7',
            'menu_text_color' => 'nullable|string|max:7',
            'menu_font_size' => 'nullable|string|max:10',
            'plan_title_color' => 'nullable|string|max:7',
            'plan_title_bg_color' => 'nullable|string|max:7',
            'plan_text_color' => 'nullable|string|max:7',
            'plan_text_bg_color' => 'nullable|string|max:7',
            'plan_price_color' => 'nullable|string|max:7',
            'plan_price_bg_color' => 'nullable|string|max:7',
            'plan_title_size' => 'nullable|string|max:10',
            'plan_text_size' => 'nullable|string|max:10',
            'plan_price_size' => 'nullable|string|max:10',
            'plan_card_border_color' => 'nullable|string|max:7',
            'plan_card_border_radius' => 'nullable|string|max:10',
            'plan_card_shadow' => 'nullable|string|max:20',
            'section_bg_color' => 'nullable|string|max:7',
            'section_text_color' => 'nullable|string|max:7',
            'footer_title_color' => 'nullable|string|max:7',
            'footer_text_color' => 'nullable|string|max:7',
            'footer_button_text' => 'nullable|string|max:255',
            'footer_button_text_color' => 'nullable|string|max:7',
            'footer_button_bg_color' => 'nullable|string|max:7',
            'footer_button_border_radius' => 'nullable|string|max:10',
            'footer_button_url' => 'nullable|string|max:255',
            'footer_bg_color' => 'nullable|string|max:7',
            'section_after_plans' => 'nullable|string',
            'menu_items' => 'nullable|string',
            'footer_email' => 'nullable|email|max:255',
            'footer_phone' => 'nullable|string|max:255',
            'plans' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('landing', 'public');
            $validated['banner_image'] = Storage::url($path);
        }

        $menuItems = [];
        if (! empty($validated['menu_items'])) {
            $lines = preg_split('/\r?\n/', $validated['menu_items']);
            foreach ($lines as $line) {
                $parts = array_map('trim', explode('|', $line));
                if (count($parts) >= 2) {
                    $menuItems[] = ['label' => $parts[0], 'url' => $parts[1]];
                }
            }
        }

        $plans = [];
        if (! empty($validated['plans'])) {
            $lines = preg_split('/\r?\n/', $validated['plans']);
            foreach ($lines as $line) {
                $parts = array_map('trim', explode('|', $line));
                if (count($parts) >= 3) {
                    $plans[] = ['title' => $parts[0], 'description' => $parts[1], 'price' => $parts[2]];
                }
            }
        }

        $page->fill([
            'banner_title' => $validated['banner_title'] ?? $page->banner_title,
            'banner_subtitle' => $validated['banner_subtitle'] ?? $page->banner_subtitle,
            'background_color' => $validated['background_color'] ?? ($page->background_color ?? '#f3f4f6'),
            'banner_title_color' => $validated['banner_title_color'] ?? ($page->banner_title_color ?? '#ffffff'),
            'banner_subtitle_color' => $validated['banner_subtitle_color'] ?? ($page->banner_subtitle_color ?? '#f3f4f6'),
            'banner_title_size' => $validated['banner_title_size'] ?? ($page->banner_title_size ?? '2.25rem'),
            'banner_subtitle_size' => $validated['banner_subtitle_size'] ?? ($page->banner_subtitle_size ?? '1.1rem'),
            'menu_bg_color' => $validated['menu_bg_color'] ?? ($page->menu_bg_color ?? '#ffffff'),
            'menu_text_color' => $validated['menu_text_color'] ?? ($page->menu_text_color ?? '#111827'),
            'menu_font_size' => $validated['menu_font_size'] ?? ($page->menu_font_size ?? '1rem'),
            'plan_title_color' => $validated['plan_title_color'] ?? ($page->plan_title_color ?? '#111827'),
            'plan_title_bg_color' => $validated['plan_title_bg_color'] ?? ($page->plan_title_bg_color ?? '#1d4ed8'),
            'plan_text_color' => $validated['plan_text_color'] ?? ($page->plan_text_color ?? '#374151'),
            'plan_text_bg_color' => $validated['plan_text_bg_color'] ?? ($page->plan_text_bg_color ?? '#f3f4f6'),
            'plan_price_color' => $validated['plan_price_color'] ?? ($page->plan_price_color ?? '#000000'),
            'plan_price_bg_color' => $validated['plan_price_bg_color'] ?? ($page->plan_price_bg_color ?? '#10b981'),
            'plan_title_size' => $validated['plan_title_size'] ?? ($page->plan_title_size ?? '1rem'),
            'plan_text_size' => $validated['plan_text_size'] ?? ($page->plan_text_size ?? '0.9rem'),
            'plan_price_size' => $validated['plan_price_size'] ?? ($page->plan_price_size ?? '1rem'),
            'plan_card_border_color' => $validated['plan_card_border_color'] ?? ($page->plan_card_border_color ?? '#e5e7eb'),
            'plan_card_border_radius' => $validated['plan_card_border_radius'] ?? ($page->plan_card_border_radius ?? '0.5rem'),
            'plan_card_shadow' => $validated['plan_card_shadow'] ?? ($page->plan_card_shadow ?? '0'),
            'section_bg_color' => $validated['section_bg_color'] ?? ($page->section_bg_color ?? '#ffffff'),
            'section_text_color' => $validated['section_text_color'] ?? ($page->section_text_color ?? '#111827'),
            'footer_title_color' => $validated['footer_title_color'] ?? ($page->footer_title_color ?? '#ffffff'),
            'footer_text_color' => $validated['footer_text_color'] ?? ($page->footer_text_color ?? '#d1d5db'),
            'footer_button_text' => $validated['footer_button_text'] ?? ($page->footer_button_text ?? 'Fale com a gente'),
            'footer_button_text_color' => $validated['footer_button_text_color'] ?? ($page->footer_button_text_color ?? '#ffffff'),
            'footer_button_bg_color' => $validated['footer_button_bg_color'] ?? ($page->footer_button_bg_color ?? '#3b82f6'),
            'footer_button_border_radius' => $validated['footer_button_border_radius'] ?? ($page->footer_button_border_radius ?? '0.35rem'),
            'footer_button_url' => $validated['footer_button_url'] ?? ($page->footer_button_url ?? '#contato'),
            'footer_bg_color' => $validated['footer_bg_color'] ?? ($page->footer_bg_color ?? '#111827'),
            'section_after_plans' => $validated['section_after_plans'] ?? $page->section_after_plans,
            'footer_email' => $validated['footer_email'] ?? $page->footer_email,
            'footer_phone' => $validated['footer_phone'] ?? $page->footer_phone,
            'is_published' => $request->has('is_published'),
        ]);

        if ($request->hasFile('banner_image')) {
            $page->banner_image = $validated['banner_image'];
        }

        if (! empty($menuItems)) {
            $page->menu_items = $menuItems;
        }

        if (! empty($plans)) {
            $page->plans = $plans;
        }

        $page->save();
        Log::info('landing.update saved', ['page_id' => $page->id, 'user_id' => $page->user_id, 'slug' => $page->slug, 'background_color' => $page->background_color, 'banner_title_size' => $page->banner_title_size, 'menu_bg_color' => $page->menu_bg_color]);

        return redirect()->route('landing.edit')->with('success', 'Landing page atualizada com sucesso.');
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

