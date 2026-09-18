<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $contentFields = [
            'site_sections' => ['title', 'description'],
            'site_pages' => ['title', 'eyebrow', 'summary', 'body', 'meta_description'],
            'articles' => ['title', 'summary', 'body', 'seo_title', 'meta_description'],
            'reports' => ['title', 'summary', 'body', 'seo_title', 'meta_description'],
        ];

        foreach ($contentFields as $table => $fields) {
            DB::table($table)->orderBy('id')->each(function (object $record) use ($table, $fields): void {
                $updates = [];
                foreach ($fields as $field) {
                    if ($record->$field === null) {
                        continue;
                    }
                    $value = str_replace(['STOCKEDGE', 'StockEdge', 'SHAERSRISE'], ['SHARESRISE', 'SharesRise', 'SHARESRISE'], $record->$field);
                    if ($value !== $record->$field) {
                        $updates[$field] = $value;
                    }
                }
                if ($updates !== []) {
                    DB::table($table)->where('id', $record->id)->update($updates);
                }
            });
        }

        foreach ([
            ['name' => 'Membership preview', 'layout' => 'pricing', 'eyebrow' => 'MEMBERSHIP OPTIONS', 'title' => 'Choose your research access.', 'description' => 'Compare published SharesRise memberships and find the access level that suits your research journey.', 'button_label' => 'View all plans', 'button_url' => '/pricing', 'item_limit' => 3, 'position' => 65, 'published' => true],
            ['name' => 'Investor stories', 'layout' => 'testimonials', 'eyebrow' => 'INVESTOR EXPERIENCES', 'title' => 'Trusted by thoughtful investors.', 'description' => 'Read experiences shared by the SharesRise community.', 'button_label' => null, 'button_url' => null, 'item_limit' => 3, 'position' => 75, 'published' => true],
        ] as $section) {
            DB::table('site_sections')->updateOrInsert(
                ['name' => $section['name']],
                $section + ['created_at' => now(), 'updated_at' => now()],
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Brand copy is not reverted because editors may have updated it after deployment.
    }
};
