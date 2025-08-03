<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Landing page
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . config('app.url') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '<changefreq>weekly</changefreq>';
        $sitemap .= '<priority>1.0</priority>';
        $sitemap .= '</url>';

        // Legal pages
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . config('app.url') . '/privacy-policy</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '<changefreq>monthly</changefreq>';
        $sitemap .= '<priority>0.3</priority>';
        $sitemap .= '</url>';

        $sitemap .= '<url>';
        $sitemap .= '<loc>' . config('app.url') . '/terms-of-service</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '<changefreq>monthly</changefreq>';
        $sitemap .= '<priority>0.3</priority>';
        $sitemap .= '</url>';

        $sitemap .= '</urlset>';

        return response($sitemap, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /dashboard\n";
        $robots .= "Disallow: /logs\n";
        $robots .= "Disallow: /profile\n";
        $robots .= "Disallow: /login\n";
        $robots .= "Disallow: /register\n";
        $robots .= "Disallow: /forgot-password\n";
        $robots .= "Disallow: /reset-password\n\n";
        $robots .= "Sitemap: " . config('app.url') . "/sitemap.xml\n";

        return response($robots, 200, ['Content-Type' => 'text/plain']);
    }
}
