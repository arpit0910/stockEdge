<?php

namespace App\Support;

use App\Models\Contact;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContactCsvImport
{
    /** @return array{created: int, skipped: int} */
    public function import(UploadedFile $file, int $ownerId): array
    {
        $stream = fopen($file->getRealPath(), 'r');
        if ($stream === false) {
            throw ValidationException::withMessages(['contact_csv' => 'The CSV file could not be read.']);
        }
        $rows = [];
        try {
            $header = fgetcsv($stream, escape: '');
            $columns = ['name', 'email', 'phone', 'company', 'status', 'notes'];
            $header = $header ? array_map(fn (?string $value): string => strtolower(trim(ltrim($value ?? '', "\xEF\xBB\xBF"))), $header) : [];
            if (! in_array('name', $header, true) || count(array_unique($header)) !== count($header) || array_diff($header, $columns)) {
                throw ValidationException::withMessages(['contact_csv' => 'Use the sample CSV headers. The name column is required.']);
            }
            $line = 1;
            while (($values = fgetcsv($stream, escape: '')) !== false) {
                $line++;
                if (count(array_filter($values, fn (?string $value): bool => trim($value ?? '') !== '')) === 0) {
                    continue;
                }
                if (count($rows) >= 1000 || count($values) !== count($header)) {
                    throw ValidationException::withMessages(['contact_csv' => "Row {$line}: check the column count. Upload at most 1,000 contacts per file."]);
                }
                $row = array_combine($header, array_map(fn (?string $value): ?string => trim($value ?? '') ?: null, $values));
                $status = strtolower(str_replace(' ', '_', $row['status'] ?? 'new'));
                $row['status'] = match ($status) {
                    'new_lead' => 'new', 'voice_mail' => 'voicemail', default => $status
                };
                $validator = Validator::make($row, [
                    'name' => 'required|string|max:120',
                    'email' => 'nullable|email|max:200',
                    'phone' => ['nullable', 'string', 'max:30', 'regex:/^\+?[0-9 ()\-.]+$/'],
                    'company' => 'nullable|string|max:150',
                    'status' => ['required', Rule::in(['active', 'blocked', 'dnd', 'archived', ...array_keys(SalesCrm::pipelineOptions())])],
                    'notes' => 'nullable|string|max:5000',
                ]);
                if ($validator->fails()) {
                    throw ValidationException::withMessages(['contact_csv' => "Row {$line}: ".$validator->errors()->first()]);
                }
                $rows[] = $validator->validated();
            }
        } finally {
            fclose($stream);
        }
        if ($rows === []) {
            throw ValidationException::withMessages(['contact_csv' => 'The CSV contains no contacts.']);
        }

        return DB::transaction(function () use ($rows, $ownerId): array {
            $emails = [];
            $phones = [];
            foreach (Contact::select(['id', 'email', 'phone'])->cursor() as $contact) {
                if ($contact->email) {
                    $emails[strtolower($contact->email)] = true;
                }
                if ($contact->phone) {
                    $phones[preg_replace('/\D/', '', $contact->phone)] = true;
                }
            }
            $created = 0;
            $skipped = 0;
            foreach ($rows as $row) {
                $email = strtolower($row['email'] ?? '');
                $phone = preg_replace('/\D/', '', $row['phone'] ?? '');
                if (($email !== '' && isset($emails[$email])) || ($phone !== '' && isset($phones[$phone]))) {
                    $skipped++;

                    continue;
                }
                Contact::create($row + ['owner_id' => $ownerId]);
                if ($email !== '') {
                    $emails[$email] = true;
                }
                if ($phone !== '') {
                    $phones[$phone] = true;
                }
                $created++;
            }

            return compact('created', 'skipped');
        });
    }
}
