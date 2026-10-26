<dialog id="contact-import-dialog" class="a-dialog" aria-labelledby="contact-import-title" @if($errors->has('contact_csv')) data-auto-open @endif>
    <form method="post" action="{{ route('sales.contacts.import') }}" enctype="multipart/form-data">
        @csrf
        <h2 id="contact-import-title">Import contacts</h2>
        <p>Upload a CSV with up to 1,000 contacts (2 MB). Name is required. Existing email addresses and phone numbers are skipped; saved contacts are kept.</p>
        <p><a href="{{ route('sales.contacts.sample') }}" class="a-button secondary">Download sample CSV</a></p>
        <p>Columns: name, email, phone, company, status, notes. Leave status blank to use New lead. Keep phone numbers as text to preserve leading zeros.</p>
        <div class="a-field">
            <label for="contact-csv">CSV file <span class="a-required">*</span></label>
            <input id="contact-csv" type="file" name="contact_csv" accept=".csv,text/csv" required>
            @error('contact_csv')<p role="alert">{{ $message }}</p>@enderror
        </div>
        <div class="a-dialog-actions">
            <button class="a-button secondary" type="button" data-contact-import-close>Cancel</button>
            <button class="a-button" type="submit">Import contacts</button>
        </div>
    </form>
</dialog>
