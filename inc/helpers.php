<?php
/**
 * DKM Helper Functions
 *
 * @package DKM
 */

defined('ABSPATH') || exit;

function dkm_get_quality_tier($product_or_id = null): string|false {
    if ($product_or_id instanceof WC_Product) {
        $product_id = $product_or_id->get_id();
    } elseif (is_numeric($product_or_id)) {
        $product_id = (int) $product_or_id;
    } else {
        $product_id = get_the_ID();
    }

    if (!$product_id) {
        return false;
    }

    $tiers = ['superclone', 'bp-swiss-made', 'aaa'];

    foreach ($tiers as $tier) {
        if (has_term($tier, 'product_cat', $product_id)) {
            return $tier;
        }
    }

    return false;
}

function dkm_get_tier_label(string $tier): string {
    $labels = [
        'superclone'    => 'SUPERCLONE',
        'bp-swiss-made' => 'BP SWISS MADE',
        'aaa'           => 'AAA+',
    ];

    return $labels[$tier] ?? '';
}

function dkm_get_tier_color(string $tier): string {
    $colors = [
        'superclone'    => '#c9a96e',
        'bp-swiss-made' => '#94a3b8',
        'aaa'           => '#a78bfa',
    ];

    return $colors[$tier] ?? '#c9a96e';
}

function dkm_format_price(float $price): string {
    return '€' . number_format($price, 2, ',', '.');
}

function dkm_get_whatsapp_link(string $product_name = ''): string {
    $number = '31647974408';

    if ($product_name) {
        $text = sprintf('Hoi! Ik heb interesse in de %s. Is deze beschikbaar?', $product_name);
    } else {
        $text = 'Hoi! Ik heb een vraag over jullie horloges.';
    }

    return 'https://wa.me/' . $number . '?text=' . rawurlencode($text);
}

function dkm_get_announcement_messages(): array {
    return [
        'Vandaag besteld = morgen in huis',
        'Persoonlijk contact via WhatsApp',
        'Beste kwaliteit voor een lage prijs',
    ];
}

function dkm_get_trust_badges(): array {
    return [
        [
            'icon'        => 'shield',
            'title'       => 'Kwaliteitsgarantie',
            'description' => 'Onze horloges worden met de grootste zorg vervaardigd en gecontroleerd.',
        ],
        [
            'icon'        => 'star',
            'title'       => 'Exclusieve modellen',
            'description' => 'Unieke modellen die nergens anders van deze kwaliteit te vinden zijn.',
        ],
        [
            'icon'        => 'truck',
            'title'       => 'Snel en veilig bestellen',
            'description' => 'Eenvoudig proces met de veiligste betaalmethode en snelle verzending binnen 1-2 dagen.',
        ],
    ];
}

function dkm_svg_icon(string $name): string {
    $icons = [
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',

        'cart' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>',

        'menu' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',

        'close' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',

        'whatsapp' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>',

        'arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',

        'check' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',

        'star' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',

        'truck' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',

        'shield' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>',

        'clock' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',

        'chevron-down' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>',

        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',

        'heart' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>',

        'filter' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>',

        'snapchat' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.206.793c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.299 4.847l-.003.06c-.012.18-.022.345-.03.51.075.045.203.09.401.09.3-.016.659-.12.959-.289.054-.03.108-.046.162-.046.168 0 .337.09.456.24.09.12.144.3.12.465-.03.24-.21.42-.39.525-.21.12-.48.195-.72.24-.27.06-.48.105-.63.165-.046.016-.09.03-.135.06-.21.135-.165.39-.119.57l.015.06c.27.78.636 1.5 1.095 2.13.3.405.66.75 1.065 1.05.21.15.465.3.72.39.165.06.33.165.42.36.105.21.075.465-.045.645-.195.3-.525.465-.855.555-.39.105-.84.166-1.29.195a.806.806 0 00-.18.03c-.12.045-.18.165-.21.284l-.003.015a.586.586 0 01-.21.345.597.597 0 01-.39.135c-.12 0-.27-.015-.42-.045-.225-.045-.48-.105-.72-.105-.09 0-.195.009-.3.03-.195.045-.39.135-.585.225-.375.195-.795.405-1.365.465-1.005-.06-1.425-.27-1.8-.465-.195-.09-.39-.18-.585-.225a1.235 1.235 0 00-.3-.03c-.24 0-.495.06-.72.105-.15.03-.3.045-.42.045a.597.597 0 01-.39-.135.586.586 0 01-.21-.345l-.003-.015c-.03-.12-.09-.24-.21-.285a.806.806 0 00-.18-.03c-.45-.03-.9-.09-1.29-.195-.33-.09-.66-.255-.855-.555-.12-.18-.15-.435-.045-.645.09-.195.255-.3.42-.36.255-.09.51-.24.72-.39.405-.3.765-.645 1.065-1.05.459-.63.825-1.35 1.095-2.13l.015-.06c.045-.18.09-.435-.12-.57a.676.676 0 00-.135-.06c-.15-.06-.36-.105-.63-.165-.24-.045-.51-.12-.72-.24-.18-.105-.36-.285-.39-.525-.024-.165.03-.345.12-.465.12-.15.288-.24.456-.24.054 0 .108.015.162.046.3.165.66.273.959.289.198 0 .326-.045.401-.09-.007-.165-.018-.33-.03-.51l-.003-.06c-.105-1.628-.23-3.654.299-4.848C7.86 1.07 11.216.793 12.206.793z"/></svg>',

        'email' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
    ];

    return $icons[$name] ?? '';
}
