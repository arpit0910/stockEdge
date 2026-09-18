<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminResources
{
    public static function navigation(): array
    {
        return [
            'Publishing' => ['reports' => 'Research reports', 'articles' => 'Editorial library', 'testimonials' => 'Client testimonials', 'pages' => 'Website pages', 'sections' => 'Homepage builder', 'media' => 'Media library'],
            'Market & products' => ['stocks' => 'Company coverage', 'taxonomies' => 'Collections & sectors', 'plans' => 'Membership plans'],
            'Relationships' => ['members' => 'Members & access', 'leads' => 'Enquiries & signups', 'requests' => 'Plan requests'],
        ];
    }

    public static function definition(string $resource): array
    {
        $definitions = [
            'testimonials' => ['table' => 'testimonials', 'singular' => 'Testimonial', 'title' => 'Client testimonials', 'description' => 'Manage the investor testimonials displayed on the homepage.', 'columns' => ['name' => 'Client name', 'role' => 'Role', 'rating' => 'Rating', 'position' => 'Order', 'published' => 'Status'], 'search' => ['name', 'role', 'quote'], 'status' => 'published', 'fields' => [
                'name' => self::field('Client name', 'text', 'required|string|max:120', 'Client'),
                'role' => self::field('Client role / occupation', 'text', 'required|string|max:120', 'Client', [], 'e.g., Retired Investor, Financial Adviser, Full-Time Trader'),
                'quote' => self::field('Testimonial quote', 'textarea', 'required|string|max:1000', 'Content'),
                'rating' => self::field('Rating (Stars)', 'select', ['required', Rule::in(['1', '2', '3', '4', '5'])], 'Rating', ['5' => '5 Stars ★★★★★', '4' => '4 Stars ★★★★', '3' => '3 Stars ★★★', '2' => '2 Stars ★★', '1' => '1 Star ★']),
                'avatar_initials' => self::field('Avatar initials', 'text', 'nullable|string|max:4', 'Client', [], 'e.g., MD, SK, JT'),
                'position' => self::field('Display order', 'integer', 'required|integer|min:0|max:9999', 'Display', [], 'Lower numbers appear first.'),
                'published' => self::field('Published on homepage', 'checkbox', 'boolean', 'Publication'),
            ]],
            'reports' => ['table' => 'reports', 'singular' => 'Research report', 'title' => 'Research reports', 'description' => 'Manage the investment case, company association and access level for every report.', 'columns' => ['title' => 'Report', 'category' => 'Collection', 'rating' => 'Rating', 'premium' => 'Access', 'published' => 'Status'], 'search' => ['title', 'slug', 'category'], 'status' => 'published', 'fields' => [
                'title' => self::field('Report title', 'text', 'required|string|max:200', 'Content'),
                'slug' => self::field('URL slug', 'slug', 'required|alpha_dash|max:200', 'Content'),
                'stock_id' => self::field('Company', 'select', 'required|exists:stocks,id', 'Classification', self::stocks()),
                'category' => self::field('Research collection', 'select', ['required', Rule::in(config('stockedge.categories'))], 'Classification', self::options(config('stockedge.categories'))),
                'rating' => self::field('Research rating', 'select', ['required', Rule::in(['Buy', 'Hold', 'Sell'])], 'Classification', self::options(['Buy', 'Hold', 'Sell'])),
                'summary' => self::field('Executive summary', 'textarea', 'required|string|max:2000', 'Content'),
                'body' => self::field('Report body', 'editor', 'required|string|max:50000', 'Content', [], 'Write a heading, then a paragraph on the next line. Separate sections with a blank line. Plain text is safely rendered; HTML is not executed.'),
                'seo_title' => self::field('SEO title', 'text', 'nullable|string|max:70', 'Search visibility', [], 'Optional. The report title is used when this is empty.'),
                'meta_description' => self::field('Meta description', 'textarea', 'nullable|string|max:170', 'Search visibility', [], 'Optional. The executive summary is used when this is empty.'),
                'premium' => self::field('Member-only report', 'checkbox', 'boolean', 'Publication'),
                'published' => self::field('Published on website', 'checkbox', 'boolean', 'Publication'),
            ]],
            'articles' => ['table' => 'articles', 'singular' => 'Article', 'title' => 'Editorial library', 'description' => 'Create considered market stories and manage their imagery and publication.', 'columns' => ['title' => 'Story', 'topic' => 'Topic', 'published' => 'Status'], 'search' => ['title', 'slug', 'topic'], 'status' => 'published', 'fields' => [
                'title' => self::field('Article title', 'text', 'required|string|max:200', 'Content'),
                'slug' => self::field('URL slug', 'slug', 'required|alpha_dash|max:200', 'Content'),
                'topic' => self::field('Editorial topic', 'select', ['required', Rule::in(config('stockedge.topics'))], 'Classification', self::options(config('stockedge.topics'))),
                'summary' => self::field('Standfirst / summary', 'textarea', 'required|string|max:2000', 'Content'),
                'body' => self::field('Article body', 'editor', 'required|string|max:50000', 'Content', [], 'Use a heading and paragraph, with a blank line between sections.'),
                'seo_title' => self::field('SEO title', 'text', 'nullable|string|max:70', 'Search visibility', [], 'Optional. The article title is used when this is empty.'),
                'meta_description' => self::field('Meta description', 'textarea', 'nullable|string|max:170', 'Search visibility', [], 'Optional. The article summary is used when this is empty.'),
                'image_path' => self::field('Cover image', 'media', ['nullable', Rule::in(self::mediaPaths())], 'Presentation', self::mediaOptions(), 'Upload images in the media library first. Leave empty to use the existing editorial image.'),
                'image_alt' => self::field('Image description', 'text', 'nullable|string|max:200', 'Presentation'),
                'published' => self::field('Published on website', 'checkbox', 'boolean', 'Publication'),
            ]],
            'stocks' => ['table' => 'stocks', 'singular' => 'Company', 'title' => 'Company coverage', 'description' => 'Maintain the company universe that powers research, portfolios and watchlists.', 'columns' => ['symbol' => 'Symbol', 'name' => 'Company', 'sector' => 'Sector', 'price' => 'Demo price', 'change' => 'Change %'], 'search' => ['symbol', 'name', 'sector'], 'fields' => [
                'symbol' => self::field('ASX symbol', 'text', 'required|alpha_dash|max:12', 'Company', [], 'Keep symbols consistent with company disclosures.'),
                'name' => self::field('Company name', 'text', 'required|string|max:200', 'Company'),
                'sector' => self::field('Sector', 'select', ['required', Rule::in(config('stockedge.sectors'))], 'Company', self::options(config('stockedge.sectors'))),
                'cap' => self::field('Market capitalisation group', 'select', ['required', Rule::in(['Blue Chip', 'Mid Cap', 'Small Cap'])], 'Company', self::options(['Blue Chip', 'Mid Cap', 'Small Cap'])),
                'description' => self::field('Company overview', 'editor', 'required|string|max:10000', 'Company'),
                'seo_title' => self::field('SEO title', 'text', 'nullable|string|max:70', 'Search visibility', [], 'Optional. The company name and ASX symbol are used when this is empty.'),
                'meta_description' => self::field('Meta description', 'textarea', 'nullable|string|max:170', 'Search visibility', [], 'Optional. The company overview is used when this is empty.'),
                'price' => self::field('Snapshot price (AUD)', 'number', 'required|numeric|min:0|max:99999999', 'Snapshot', [], 'Manual demonstration price. This does not connect a live market feed.'),
                'change' => self::field('Snapshot change (%)', 'number', 'required|numeric|min:-100|max:99999', 'Snapshot'),
                'yield' => self::field('Dividend yield (%)', 'number', 'required|numeric|min:0|max:100', 'Snapshot'),
            ]],
            'taxonomies' => ['table' => 'taxonomies', 'singular' => 'Classification', 'title' => 'Collections & sectors', 'description' => 'Add research collections, company sectors and editorial topics. Renames update linked content.', 'columns' => ['name' => 'Name', 'kind' => 'Used for', 'position' => 'Order'], 'search' => ['name', 'kind'], 'fields' => [
                'name' => self::field('Name', 'text', 'required|string|max:100', 'Classification'),
                'kind' => self::field('Classification type', 'select', ['required', Rule::in(['category', 'sector', 'topic'])], 'Classification', ['category' => 'Research collection', 'sector' => 'Company sector', 'topic' => 'Editorial topic']),
                'description' => self::field('Description', 'textarea', 'nullable|string|max:1000', 'Classification'),
                'position' => self::field('Display order', 'integer', 'required|integer|min:0|max:9999', 'Display', [], 'Lower numbers appear first.'),
            ]],
            'pages' => ['table' => 'site_pages', 'singular' => 'Page', 'title' => 'Website pages', 'description' => 'Own your website copy, create new pages and choose what appears in the footer.', 'columns' => ['title' => 'Page', 'slug' => 'Address', 'published' => 'Status', 'show_in_footer' => 'Footer link'], 'search' => ['title', 'slug'], 'status' => 'published', 'fields' => [
                'title' => self::field('Page title', 'text', 'required|string|max:200', 'Content'),
                'slug' => self::field('Page address', 'slug', 'required|alpha_dash|max:100', 'Content', [], 'For example: investment-process. Existing system page addresses are fixed.'),
                'eyebrow' => self::field('Section label', 'text', 'nullable|string|max:120', 'Content'),
                'summary' => self::field('Introduction', 'textarea', 'required|string|max:2000', 'Content'),
                'body' => self::field('Page body', 'editor', 'required|string|max:50000', 'Content', [], 'Heading then paragraph, separated by blank lines. Existing calculator and contact form functionality is retained.'),
                'meta_description' => self::field('Search engine description', 'textarea', 'nullable|string|max:170', 'Discovery', [], 'Keep this concise and useful in search results. The page introduction is used when empty.'),
                'show_in_footer' => self::field('Show a footer link', 'checkbox', 'boolean', 'Discovery'),
                'position' => self::field('Footer display order', 'integer', 'required|integer|min:0|max:9999', 'Discovery'),
                'published' => self::field('Published on website', 'checkbox', 'boolean', 'Publication'),
            ]],
            'sections' => ['table' => 'site_sections', 'singular' => 'Homepage section', 'title' => 'Homepage builder', 'description' => 'Compose the homepage with supported layouts. Edit the message, change the order or add a new section.', 'columns' => ['name' => 'Section', 'layout' => 'Layout', 'position' => 'Order', 'published' => 'Visibility'], 'search' => ['name', 'title', 'layout'], 'status' => 'published', 'fields' => [
                'name' => self::field('Internal section name', 'text', 'required|string|max:150', 'Layout', [], 'Only shown to administrators.'),
                'layout' => self::field('Section layout', 'select', ['required', Rule::in(['hero', 'research', 'market', 'collections', 'tools', 'editorial', 'pricing', 'testimonials', 'callout', 'text'])], 'Layout', ['hero' => 'Opening story', 'research' => 'Research report grid', 'market' => 'Company snapshot table', 'collections' => 'Research collection directory', 'tools' => 'Investor tools', 'editorial' => 'Editorial story grid', 'pricing' => 'Membership plan grid', 'testimonials' => 'Client testimonial grid', 'callout' => 'Call to action', 'text' => 'Text with optional image']),
                'position' => self::field('Page order', 'integer', 'required|integer|min:0|max:9999', 'Layout', [], 'Lower numbers appear first. You can also reorder sections from the list.'),
                'item_limit' => self::field('Maximum records to show', 'integer', 'required|integer|min:1|max:12', 'Layout', [], 'Applies to research, market, collection and editorial layouts.'),
                'eyebrow' => self::field('Section label', 'text', 'nullable|string|max:120', 'Content'),
                'title' => self::field('Headline', 'text', 'required|string|max:200', 'Content'),
                'description' => self::field('Description', 'textarea', 'nullable|string|max:5000', 'Content'),
                'button_label' => self::field('Button text', 'text', 'nullable|string|max:80', 'Action'),
                'button_url' => self::field('Button destination', 'url', 'nullable|regex:~^/[a-zA-Z0-9/_?=&%#.-]*$~D|max:250', 'Action', [], 'Use a local path such as /research or /contact.'),
                'image_path' => self::field('Image', 'media', ['nullable', Rule::in(self::mediaPaths())], 'Presentation', self::mediaOptions(), 'Used by the text layout.'),
                'image_alt' => self::field('Image description', 'text', 'nullable|string|max:200', 'Presentation'),
                'published' => self::field('Visible on homepage', 'checkbox', 'boolean', 'Publication'),
            ]],
            'plans' => ['table' => 'plans', 'singular' => 'Membership plan', 'title' => 'Membership plans', 'description' => 'Manage the public plan catalogue and pricing copy. Plan requests do not collect payments.', 'columns' => ['name' => 'Plan', 'monthly_price' => 'Monthly AUD', 'yearly_price' => 'Yearly AUD', 'featured' => 'Featured', 'published' => 'Visibility'], 'search' => ['name', 'headline'], 'status' => 'published', 'fields' => [
                'name' => self::field('Plan name', 'text', 'required|string|max:100', 'Plan'),
                'headline' => self::field('Short headline', 'text', 'required|string|max:150', 'Plan'),
                'description' => self::field('Description', 'textarea', 'required|string|max:2000', 'Plan'),
                'features' => self::field('Included features', 'editor', 'required|string|max:5000', 'Plan', [], 'One feature per line. This is the public plan description; paid entitlements are not connected.'),
                'monthly_price' => self::field('Monthly price (AUD)', 'number', 'required|numeric|min:0|max:999999', 'Pricing'),
                'yearly_price' => self::field('Annual price (AUD)', 'number', 'required|numeric|min:0|max:999999', 'Pricing'),
                'is_trial' => self::field('Free trial plan', 'checkbox', 'boolean', 'Pricing', [], 'Free trial plans must have zero prices. The trial lasts seven days.'),
                'featured' => self::field('Highlight this plan', 'checkbox', 'boolean', 'Display'),
                'position' => self::field('Display order', 'integer', 'required|integer|min:0|max:9999', 'Display'),
                'published' => self::field('Visible on pricing page', 'checkbox', 'boolean', 'Publication'),
            ]],
            'members' => ['table' => 'users', 'singular' => 'Member', 'title' => 'Members & access', 'description' => 'Manage accounts, trial dates and administrator access. Passwords are never displayed.', 'columns' => ['name' => 'Member', 'email' => 'Email', 'is_admin' => 'Role', 'is_active' => 'Account', 'trial_ends_at' => 'Trial ends'], 'search' => ['name', 'email'], 'status' => 'is_active', 'delete' => false, 'fields' => [
                'name' => self::field('Full name', 'text', 'required|string|max:120', 'Account'),
                'email' => self::field('Email address', 'email', 'required|email|max:200', 'Account'),
                'password' => self::field('New password', 'password', 'nullable|string|min:12|max:200', 'Account', [], 'Required for a new member. Leave blank to keep the current password. Minimum 12 characters.'),
                'trial_ends_at' => self::field('Research trial ends', 'datetime-local', 'nullable|date', 'Access'),
                'is_admin' => self::field('Administrator access', 'checkbox', 'boolean', 'Access', [], 'Grants full access to this management workspace.'),
                'is_active' => self::field('Account is active', 'checkbox', 'boolean', 'Access', [], 'Suspended members cannot log in; existing sessions are ended on their next request.'),
            ]],
            'leads' => ['table' => 'leads', 'singular' => 'Enquiry', 'title' => 'Enquiries & signups', 'description' => 'Review submissions, record internal notes and move enquiries through follow-up.', 'columns' => ['email' => 'Contact', 'type' => 'Source', 'name' => 'Name', 'status' => 'Follow-up', 'consent' => 'Consent'], 'search' => ['name', 'email', 'type'], 'status' => 'status', 'create' => false, 'delete' => false, 'fields' => [
                'status' => self::field('Follow-up status', 'select', ['required', Rule::in(['new', 'in_progress', 'resolved', 'unsubscribed'])], 'Follow-up', ['new' => 'New', 'in_progress' => 'In progress', 'resolved' => 'Resolved', 'unsubscribed' => 'Unsubscribed']),
                'admin_notes' => self::field('Internal notes', 'editor', 'nullable|string|max:10000', 'Follow-up', [], 'Only visible in the admin workspace. Original submission and consent remain unchanged.'),
            ]],
            'requests' => ['table' => 'subscription_requests', 'singular' => 'Plan request', 'title' => 'Plan requests', 'description' => 'Follow up on membership interest without changing payment or access entitlements.', 'columns' => ['plan' => 'Requested plan', 'billing' => 'Billing', 'status' => 'Status', 'user_id' => 'Member ID'], 'search' => ['plan', 'status'], 'status' => 'status', 'create' => false, 'delete' => false, 'fields' => [
                'status' => self::field('Request status', 'select', ['required', Rule::in(['pending', 'contacted', 'closed'])], 'Follow-up', ['pending' => 'Pending', 'contacted' => 'Contacted', 'closed' => 'Closed']),
                'admin_notes' => self::field('Internal notes', 'editor', 'nullable|string|max:10000', 'Follow-up', [], 'Closing a request does not charge the member or grant paid access.'),
            ]],
        ];
        abort_unless(isset($definitions[$resource]), 404);

        return $definitions[$resource] + ['key' => $resource, 'create' => true, 'delete' => true, 'status' => null];
    }

    private static function field(string $label, string $type, string|array $rules, string $group, array $options = [], string $hint = ''): array
    {
        return compact('label', 'type', 'rules', 'group', 'options', 'hint');
    }

    public static function options(array $values): array
    {
        return array_combine($values, $values) ?: [];
    }

    private static function stocks(): array
    {
        return DB::table('stocks')->orderBy('symbol')->get()->mapWithKeys(fn ($s) => [$s->id => $s->symbol.' · '.$s->name])->all();
    }

    private static function mediaPaths(): array
    {
        return DB::table('media_assets')->pluck('path')->all();
    }

    private static function mediaOptions(): array
    {
        return DB::table('media_assets')->latest()->pluck('name', 'path')->all();
    }

    public static function label(object $row): string
    {
        return (string) ($row->title ?? $row->name ?? $row->email ?? $row->plan ?? 'Record #'.$row->id);
    }

    public static function publicUrl(string $resource, object $record): ?string
    {
        return match ($resource) {
            'reports' => route('report', $record->slug), 'articles' => route('article', $record->slug),
            'pages' => route('page', $record->slug), 'sections' => route('home'), 'plans' => route('page', 'pricing'),
            'stocks' => route('stock', $record->symbol), default => null,
        };
    }
}
