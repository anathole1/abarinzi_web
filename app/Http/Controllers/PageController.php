<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\AboutContent;
use App\Models\CoreObjectiveItem;
use App\Models\VisionItem;
use Illuminate\Http\Request; // Not strictly needed for these methods yet

class PageController extends Controller
{
    public function welcome()
    {
        $heroSlides = HeroSlide::where('is_active', true)
                                ->orderBy('order')
                                ->orderBy('created_at')
                                ->get();

        $aboutContent = AboutContent::getContent(); // Uses your static helper

        // For Contact Us details:
        // Consider moving these contact details into AboutContent model fields
        // or a dedicated SiteSetting model for better management via admin panel.
        $contactDetails = [
            'title' => $aboutContent->contact_section_title ?? 'Contact Us', // Assuming you add these to AboutContent
            'subtitle' => $aboutContent->contact_section_subtitle ?? 'Have questions or want to get involved with the Abarinzi family? We\'d love to hear from you.',
            'location_line1' => $aboutContent->contact_location_line1 ?? 'Abarinzi Family',
            'location_line2' => $aboutContent->contact_location_line2 ?? 'Kigali, Rwanda',
            'phone' => $aboutContent->contact_phone ?? '+250 787 503 042',
            'phone_hours' => $aboutContent->contact_phone_hours ?? 'Mon-Fri, 9am-5pm CAT',
            'email' => $aboutContent->contact_email ?? 'info@abarinzi.org',
            'email_response_time' => $aboutContent->contact_email_response_time ?? "We'll respond within 2 business days",
            'social_links' => $aboutContent->social_links ?? [ // Example, assuming social_links is a JSON field in AboutContent
                ['platform' => 'Facebook', 'url' => 'https://www.facebook.com', 'icon_svg_path' => 'M24...'],
                ['platform' => 'Twitter', 'url' => 'https://www.x.com', 'icon_svg_path' => 'M23...'],
            ],
        ];

        return view('welcome', [
            'heroSlides' => $heroSlides,
            'aboutContent' => $aboutContent,
            'contactDetails' => $contactDetails,
        ]);
    }

    public function aboutOurWorkAndVision()
    {
        $aboutContent = AboutContent::getContent();
        $coreObjectives = CoreObjectiveItem::orderBy('order')->get();
        $visionItems = VisionItem::orderBy('order')->get();

        return view('about-our-work', compact('aboutContent', 'coreObjectives', 'visionItems'));
    }
}