<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesCrmTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_sales_users_and_admins_can_access_the_crm(): void
    {
        $this->get('/sales')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/sales')->assertForbidden();
        $this->actingAs(User::factory()->sales()->create())->get('/sales')->assertOk()->assertSee('Lead pipeline');
        $this->actingAs(User::factory()->admin()->create())->get('/sales')->assertOk();
    }

    public function test_sales_login_rejects_members_and_accepts_sales_users(): void
    {
        $member = User::factory()->create(['password' => 'password']);
        $salesUser = User::factory()->sales()->create(['password' => 'password']);

        $this->post('/sales/login', ['email' => $member->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post('/sales/login', ['email' => $salesUser->email, 'password' => 'password'])->assertRedirect('/sales');
        $this->assertAuthenticatedAs($salesUser);
    }

    public function test_admin_can_create_a_lead_that_appears_in_the_sales_pipeline(): void
    {
        $admin = User::factory()->admin()->create();
        $salesUser = User::factory()->sales()->create();

        $this->actingAs($admin)->post('/admin/manage/leads', [
            'name' => 'Admin Created Lead',
            'email' => 'admin-lead@example.test',
            'phone' => '+61 400 111 222',
            'type' => 'manual',
            'message' => 'Requested a call about research membership.',
            'consent' => 1,
            'status' => 'new',
            'admin_notes' => 'Added after an offline event.',
        ])->assertSessionHas('success');

        $this->actingAs($salesUser)->get('/sales')
            ->assertOk()
            ->assertSee('Admin Created Lead')
            ->assertSee('admin-lead@example.test');
    }

    public function test_website_enquiry_appears_in_the_sales_pipeline(): void
    {
        $salesUser = User::factory()->sales()->create();

        $this->post('/enquiries', [
            'name' => 'Website Lead',
            'email' => 'website-lead@example.test',
            'phone' => '0400 333 444',
            'type' => 'contact',
            'message' => 'Please contact me about a research plan.',
            'consent' => 1,
        ])->assertSessionHas('success');

        $this->actingAs($salesUser)->get('/sales')
            ->assertOk()
            ->assertSee('Website Lead')
            ->assertSee('website-lead@example.test');
    }

    public function test_sales_user_can_update_a_lead_and_record_its_journey(): void
    {
        $salesUser = User::factory()->sales()->create();
        $lead = Lead::factory()->create();

        $this->actingAs($salesUser)->put("/sales/leads/{$lead->id}", [
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'status' => 'qualified',
            'assigned_to' => $salesUser->id,
        ])->assertSessionHas('success');

        $this->actingAs($salesUser)->post("/sales/leads/{$lead->id}/journey", [
            'type' => 'response',
            'details' => 'Interested in the annual plan and asked for a follow-up.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('leads', ['id' => $lead->id, 'status' => 'qualified', 'assigned_to' => $salesUser->id]);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'status', 'outcome' => 'qualified']);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'response']);
    }

    public function test_sales_user_can_log_a_call_and_it_advances_a_new_lead(): void
    {
        $salesUser = User::factory()->sales()->create();
        $lead = Lead::factory()->create(['status' => 'new']);

        $this->actingAs($salesUser)->post("/sales/leads/{$lead->id}/calls", [
            'outcome' => 'answered',
            'duration_seconds' => 95,
            'details' => 'Asked to receive the sample report by email.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('leads', ['id' => $lead->id, 'status' => 'contacted']);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'call', 'outcome' => 'answered', 'duration_seconds' => 95]);
    }

    public function test_sales_user_can_convert_a_lead_to_a_customer_contact(): void
    {
        $salesUser = User::factory()->sales()->create();
        $lead = Lead::factory()->create(['assigned_to' => $salesUser->id]);

        $this->actingAs($salesUser)->post("/sales/leads/{$lead->id}/convert")->assertRedirect();

        $lead->refresh();
        $this->assertSame('converted', $lead->status);
        $this->assertNotNull($lead->contact_id);
        $this->assertDatabaseHas('contacts', ['id' => $lead->contact_id, 'email' => $lead->email, 'status' => 'active']);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'converted']);
    }

    public function test_sales_user_can_manage_contact_status_and_remove_a_contact(): void
    {
        $salesUser = User::factory()->sales()->create();
        $contact = Contact::factory()->create();

        $this->actingAs($salesUser)->put("/sales/contacts/{$contact->id}", [
            'name' => $contact->name,
            'email' => $contact->email,
            'phone' => $contact->phone,
            'company' => $contact->company,
            'status' => 'dnd',
            'notes' => 'Customer opted out by phone.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'status' => 'dnd']);
        $this->actingAs($salesUser)->delete("/sales/contacts/{$contact->id}")->assertRedirect('/sales/contacts');
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_dnd_contact_cannot_be_called_from_a_linked_lead(): void
    {
        $salesUser = User::factory()->sales()->create();
        $contact = Contact::factory()->create(['status' => 'dnd']);
        $lead = Lead::factory()->create(['contact_id' => $contact->id]);

        $this->actingAs($salesUser)->post("/sales/leads/{$lead->id}/calls", [
            'outcome' => 'answered',
        ])->assertForbidden();
    }
}
