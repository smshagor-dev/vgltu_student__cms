<?php

namespace App\Support;

use App\Models\HeaderMenu;
use App\Models\HeroFlag;
use App\Models\HeroSection;
use App\Models\StudyDestination;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class PublicSiteData
{
    public static function shell(): array
    {
        if (! self::hasCmsTables()) {
            return self::fallbackShell();
        }

        return Cache::remember('public_site.shell', 300, function () {
            $settings = WebsiteSetting::query()->first();
            $menus = HeaderMenu::query()
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->with('children')
                ->orderBy('sort_order')
                ->get()
                ->reject(fn (HeaderMenu $menu) => in_array(strtolower(trim($menu->title)), ['countries', 'alumni'], true))
                ->values();

            $normalizedSettings = self::normalizeSettings($settings);
            $normalizedMenus = $menus->isNotEmpty()
                ? $menus->map(fn (HeaderMenu $menu) => self::mapMenu($menu))->all()
                : self::fallbackShell()['menus'];

            $normalizedMenus = self::ensureAboutUniversityMenu($normalizedMenus, $normalizedSettings);
            $normalizedMenus = self::ensureCoursesMenu($normalizedMenus, $normalizedSettings);
            $normalizedMenus = self::ensureContactMenu($normalizedMenus);

            return [
                'settings' => $normalizedSettings,
                'menus' => $normalizedMenus,
            ];
        });
    }

    public static function homepage(): array
    {
        if (! self::hasCmsTables()) {
            return self::fallbackHomepage();
        }

        return Cache::remember('public_site.homepage', 300, function () {
            $hero = HeroSection::query()->with(['flags' => fn ($query) => $query->where('is_active', true)])
                ->where('is_active', true)
                ->latest('id')
                ->first();

            $destinations = StudyDestination::query()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get();

            return [
                'hero' => $hero ? self::mapHero($hero) : self::fallbackHomepage()['hero'],
                'destinations' => $destinations->isNotEmpty()
                    ? $destinations->map(fn (StudyDestination $destination) => self::mapDestination($destination))->all()
                    : self::fallbackHomepage()['destinations'],
            ];
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('public_site.shell');
        Cache::forget('public_site.homepage');
    }

    private static function hasCmsTables(): bool
    {
        return Schema::hasTable('website_settings')
            && Schema::hasTable('header_menus')
            && Schema::hasTable('hero_sections')
            && Schema::hasTable('hero_flags')
            && Schema::hasTable('study_destinations');
    }

    private static function normalizeSettings(?WebsiteSetting $settings): array
    {
        $fallback = self::fallbackShell()['settings'];

        if (! $settings) {
            return $fallback;
        }

        return [
            'site_name' => $settings->site_name ?: $fallback['site_name'],
            'logo_url' => PublicAsset::url($settings->logo_path, $fallback['logo_url']),
            'favicon_url' => PublicAsset::url($settings->favicon_path, $fallback['favicon_url']),
            'contact_button_text' => $settings->contact_button_text ?: $fallback['contact_button_text'],
            'contact_button_link' => $settings->contact_button_link ?: $fallback['contact_button_link'],
            'default_language' => $settings->default_language ?: $fallback['default_language'],
            'available_languages' => array_values(array_filter($settings->available_languages ?: $fallback['available_languages'])),
            'topbar_location' => $settings->topbar_location ?: $fallback['topbar_location'],
            'class_routine_text' => $settings->class_routine_text ?: $fallback['class_routine_text'],
            'class_routine_link' => $settings->class_routine_link ?: $fallback['class_routine_link'],
            'university_profile_text' => $settings->university_profile_text ?: $fallback['university_profile_text'],
            'university_profile_link' => $settings->university_profile_link ?: $fallback['university_profile_link'],
            'about_university_menu_text' => $settings->about_university_menu_text ?: $fallback['about_university_menu_text'],
            'about_university_title' => $settings->about_university_title ?: $fallback['about_university_title'],
            'about_university_content' => $settings->about_university_content,
            'about_university_header_url' => PublicAsset::url($settings->about_university_header_path, $fallback['about_university_header_url']),
            'courses_menu_text' => $settings->courses_menu_text ?: $fallback['courses_menu_text'],
            'courses_title' => $settings->courses_title ?: $fallback['courses_title'],
            'courses_content' => $settings->courses_content,
            'courses_header_url' => PublicAsset::url($settings->courses_header_path, $fallback['courses_header_url']),
            'search_placeholder' => $settings->search_placeholder ?: $fallback['search_placeholder'],
            'footer_social_links' => collect($settings->footer_social_links ?: $fallback['footer_social_links'])
                ->map(function (array $item) {
                    return [
                        'label' => $item['label'] ?? 'Social',
                        'url' => self::normalizeMenuUrl($item['url'] ?? '#'),
                        'icon_url' => PublicAsset::url($item['icon_path'] ?? null, $item['icon_url'] ?? null),
                        'icon_class' => $item['icon_class'] ?? null,
                    ];
                })
                ->filter(fn (array $item) => filled($item['url']) && (filled($item['icon_url']) || filled($item['icon_class'])))
                ->values()
                ->all(),
        ];
    }

    private static function mapMenu(HeaderMenu $menu): array
    {
        return [
            'id' => $menu->id,
            'title' => $menu->title,
            'url' => self::normalizeMenuUrl($menu->url),
            'target' => $menu->open_in_new_tab ? '_blank' : '_self',
            'children' => $menu->children->map(fn (HeaderMenu $child) => [
                'id' => $child->id,
                'title' => $child->title,
                'url' => self::normalizeMenuUrl($child->url),
                'target' => $child->open_in_new_tab ? '_blank' : '_self',
            ])->all(),
        ];
    }

    private static function normalizeMenuUrl(?string $url): string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return '#';
        }

        $lowerUrl = strtolower($url);

        foreach (['http://', 'https://', 'mailto:', 'tel:', '#', '//'] as $prefix) {
            if (str_starts_with($lowerUrl, $prefix)) {
                return $url;
            }
        }

        return url('/' . ltrim($url, '/'));
    }

    private static function mapHero(HeroSection $hero): array
    {
        $fallback = self::fallbackHomepage()['hero'];

        return [
            'badge_text' => $hero->badge_text ?: $fallback['badge_text'],
            'title' => $hero->title ?: $fallback['title'],
            'subtitle' => $hero->subtitle ?: $fallback['subtitle'],
            'background_image_url' => PublicAsset::url($hero->background_image_path, $fallback['background_image_url']),
            'cta_text' => $hero->cta_text ?: $fallback['cta_text'],
            'cta_link' => $hero->cta_link ?: $fallback['cta_link'],
            'overlay_start_color' => $hero->overlay_start_color ?: $fallback['overlay_start_color'],
            'overlay_end_color' => $hero->overlay_end_color ?: $fallback['overlay_end_color'],
            'overlay_opacity' => $hero->overlay_opacity ?: $fallback['overlay_opacity'],
            'flags' => $hero->flags->map(fn (HeroFlag $flag) => [
                'label' => $flag->label,
                'image_url' => PublicAsset::url($flag->image_path),
                'position_top' => $flag->position_top,
                'position_left' => $flag->position_left,
            ])->values()->all(),
        ];
    }

    private static function mapDestination(StudyDestination $destination): array
    {
        return [
            'name' => $destination->name,
            'slug' => $destination->slug,
            'flag_image_url' => PublicAsset::url($destination->flag_image_path),
            'url' => route('study-destinations.show', $destination->slug),
        ];
    }

    private static function fallbackShell(): array
    {
        return [
            'settings' => [
                'site_name' => 'Global Study Gateway',
                'logo_url' => 'https://vgltu.ru/templates/default/images/logo_en.png',
                'favicon_url' => 'https://vgltu.ru/templates/default/images/logo_en.png',
                'contact_button_text' => 'Login',
                'contact_button_link' => '/login',
                'default_language' => 'EN',
                'available_languages' => ['EN', 'BN', 'RU'],
                'topbar_location' => 'Voronezh, Russian Federation',
                'class_routine_text' => 'Class Routine',
                'class_routine_link' => '/class_routine',
                'university_profile_text' => 'University Profile',
                'university_profile_link' => '/university-student-profile',
                'about_university_menu_text' => 'Universities',
                'about_university_title' => 'Universities',
                'about_university_content' => null,
                'about_university_header_url' => asset('28020.png'),
                'courses_menu_text' => 'Courses',
                'courses_title' => 'Courses',
                'courses_content' => null,
                'courses_header_url' => asset('28020.png'),
                'search_placeholder' => 'Search universities or countries',
                'footer_social_links' => [
                    ['label' => 'Facebook', 'url' => '#', 'icon_class' => 'fab fa-facebook-f', 'icon_url' => null],
                    ['label' => 'WhatsApp', 'url' => 'https://wa.me/79954949836', 'icon_class' => 'fab fa-whatsapp', 'icon_url' => null],
                    ['label' => 'Instagram', 'url' => '#', 'icon_class' => 'fab fa-instagram', 'icon_url' => null],
                ],
            ],
            'menus' => [
                ['title' => 'Home', 'url' => '/', 'target' => '_self', 'children' => []],
                ['title' => 'Universities', 'url' => '#', 'target' => '_self', 'children' => []],
                ['title' => 'Courses', 'url' => '/courses', 'target' => '_self', 'children' => []],
                ['title' => 'About', 'url' => '#about', 'target' => '_self', 'children' => []],
                ['title' => 'Contact Us', 'url' => '/contact-us', 'target' => '_self', 'children' => []],
            ],
        ];
    }

    private static function ensureContactMenu(array $menus): array
    {
        $hasContactMenu = false;

        $menus = array_map(function (array $menu) use (&$hasContactMenu) {
            if (strtolower(trim((string) ($menu['title'] ?? ''))) === 'contact us') {
                $hasContactMenu = true;
                $menu['url'] = url('/contact-us');
                $menu['target'] = '_self';
            }

            return $menu;
        }, $menus);

        if ($hasContactMenu) {
            return $menus;
        }

        $menus[] = [
            'title' => 'Contact Us',
            'url' => url('/contact-us'),
            'target' => '_self',
            'children' => [],
        ];

        return $menus;
    }

    private static function ensureAboutUniversityMenu(array $menus, array $settings): array
    {
        $hasAboutUniversityMenu = false;

        $menus = array_map(function (array $menu) use (&$hasAboutUniversityMenu, $settings) {
            if (in_array(strtolower(trim((string) ($menu['title'] ?? ''))), ['about university', 'universities', 'university'], true)) {
                $hasAboutUniversityMenu = true;
                $menu['title'] = $settings['about_university_menu_text'];
                $menu['url'] = url('/about-university');
                $menu['target'] = '_self';
            }

            return $menu;
        }, $menus);

        if ($hasAboutUniversityMenu) {
            return $menus;
        }

        array_splice($menus, min(1, count($menus)), 0, [[
            'title' => $settings['about_university_menu_text'],
            'url' => url('/about-university'),
            'target' => '_self',
            'children' => [],
        ]]);

        return $menus;
    }

    private static function ensureCoursesMenu(array $menus, array $settings): array
    {
        $hasCoursesMenu = false;

        $menus = array_map(function (array $menu) use (&$hasCoursesMenu, $settings) {
            if (strtolower(trim((string) ($menu['title'] ?? ''))) === 'courses') {
                $hasCoursesMenu = true;
                $menu['title'] = $settings['courses_menu_text'];
                $menu['url'] = url('/courses');
                $menu['target'] = '_self';
            }

            return $menu;
        }, $menus);

        if ($hasCoursesMenu) {
            return $menus;
        }

        array_splice($menus, min(2, count($menus)), 0, [[
            'title' => $settings['courses_menu_text'],
            'url' => url('/courses'),
            'target' => '_self',
            'children' => [],
        ]]);

        return $menus;
    }

    private static function fallbackHomepage(): array
    {
        return [
            'hero' => [
                'badge_text' => 'Trusted Study Abroad Guidance',
                'title' => 'Your future starts with the right international education pathway.',
                'subtitle' => 'Explore university options, compare countries, and take the next step with a student-focused advisory experience.',
                'background_image_url' => asset('28020.png'),
                'cta_text' => 'Start Your Journey',
                'cta_link' => route('register'),
                'overlay_start_color' => '#1b1033',
                'overlay_end_color' => '#d14b84',
                'overlay_opacity' => 0.68,
                'flags' => [],
            ],
            'destinations' => [
                ['name' => 'Russia', 'slug' => 'russia', 'flag_image_url' => null, 'url' => '#'],
                ['name' => 'Canada', 'slug' => 'canada', 'flag_image_url' => null, 'url' => '#'],
                ['name' => 'Germany', 'slug' => 'germany', 'flag_image_url' => null, 'url' => '#'],
                ['name' => 'Australia', 'slug' => 'australia', 'flag_image_url' => null, 'url' => '#'],
            ],
        ];
    }
}
