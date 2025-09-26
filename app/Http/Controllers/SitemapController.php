<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Event;

class SitemapController extends Controller
{
    public function generateSitemap()
    {
        $sitemap = Sitemap::create();

        $now = now();

        // Static/public pages
        $sitemap->add(Url::create('/')
            ->setLastModificationDate($now)
            ->setPriority(1.0));

        $sitemap->add(Url::create('/dashboard')
            ->setLastModificationDate($now)
            ->setPriority(0.9));

        $sitemap->add(Url::create('/events')
            ->setLastModificationDate($now)
            ->setPriority(0.8));

        // Auth pages (noindex typically) are not included

        // Dynamic: Events
        Event::query()
            ->orderByDesc('updated_at')
            ->chunk(500, function ($events) use ($sitemap) {
                foreach ($events as $event) {
                    $sitemap->add(
                        Url::create('/events/' . $event->id)
                            ->setLastModificationDate($event->updated_at ?? $event->created_at)
                            ->setPriority(0.7)
                    );
                }
            });

        // Write sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        return response()->json(['message' => 'Sitemap generated successfully!']);
    }
}
