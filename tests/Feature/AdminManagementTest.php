<?php

namespace Tests\Feature;

use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Resultat;
use App\Models\TypeAnalyse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard_or_users(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/admin/users')->assertRedirect('/login');
    }

    public function test_patient_and_medecin_cannot_access_admin_portal(): void
    {
        $patient = User::factory()->create(['role' => 'patient']);
        $medecin = User::factory()->create(['role' => 'medecin']);

        $this->actingAs($patient)->get('/admin/dashboard')->assertRedirect(route('dashboard'));
        $this->actingAs($medecin)->get('/admin/dashboard')->assertRedirect(route('dashboard'));

        $this->actingAs($patient)->get('/admin/users')->assertRedirect(route('dashboard'));
        $this->actingAs($medecin)->get('/admin/users')->assertRedirect(route('dashboard'));
    }

    public function test_admin_is_redirected_to_admin_dashboard_on_login(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'Admin123!']);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'Admin123!',
        ]);

        $response->assertRedirect(route('dashboard'));

        // Sur la route dashboard, l'admin est dirigé vers admin.dashboard
        $this->actingAs($admin)->get(route('dashboard'))->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_login_with_admin_role_tab(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'Admin123!']);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'Admin123!',
            'role' => 'admin',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_admin_can_login_even_if_patient_tab_was_selected(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'Admin123!']);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'Admin123!',
            'role' => 'patient',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_admin_can_view_dashboard_and_users_directory(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertSee('Console Centrale de Pilotage');

        $usersResponse = $this->actingAs($admin)->get(route('admin.users.index'));
        $usersResponse->assertOk();
        $usersResponse->assertSee('Répertoire des Utilisateurs');
    }

    public function test_admin_can_access_laboratory_portal_and_supervise_medical_records(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('medecin.espace.dashboard'));
        $response->assertOk();

        $patientsResponse = $this->actingAs($admin)->get(route('medecin.espace.patients'));
        $patientsResponse->assertOk();

        $rdvResponse = $this->actingAs($admin)->get(route('medecin.espace.rendez-vous'));
        $rdvResponse->assertOk();
    }

    public function test_admin_can_create_new_medecin_with_synchronized_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Dr. Marc Leroy',
            'email' => 'marc.leroy@biosante.fr',
            'password' => 'Secret123!',
            'role' => 'medecin',
            'telephone' => '+33 6 11 22 33 44',
            'specialite' => 'Biochimie métabolique',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'marc.leroy@biosante.fr',
            'role' => 'medecin',
        ]);

        $newUser = User::where('email', 'marc.leroy@biosante.fr')->first();
        $this->assertNotNull($newUser);
        $this->assertDatabaseHas('medecins', [
            'user_id' => $newUser->id,
            'specialite' => 'Biochimie métabolique',
            'telephone' => '+33 6 11 22 33 44',
        ]);
    }

    public function test_admin_can_create_new_patient_with_synchronized_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Fatou Fall',
            'email' => 'fatou.fall@example.com',
            'password' => 'Secret123!',
            'role' => 'patient',
            'telephone' => '+221 77 999 88 77',
            'groupe_sanguin' => 'O+',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'fatou.fall@example.com',
            'role' => 'patient',
        ]);

        $newUser = User::where('email', 'fatou.fall@example.com')->first();
        $this->assertNotNull($newUser);
        $this->assertDatabaseHas('patients', [
            'user_id' => $newUser->id,
            'groupe_sanguin' => 'O+',
            'telephone' => '+221 77 999 88 77',
        ]);
    }

    public function test_admin_can_update_user_information(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $patientUser = User::factory()->create(['name' => 'Old Name', 'role' => 'patient']);
        $patient = Patient::create(['user_id' => $patientUser->id, 'nom' => 'Old', 'prenom' => 'Name', 'email' => $patientUser->email]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $patientUser), [
            'name' => 'Alice Martin',
            'email' => 'alice.martin@example.com',
            'role' => 'patient',
            'telephone' => '+33 7 00 11 22 33',
            'groupe_sanguin' => 'AB+',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $patientUser->id,
            'name' => 'Alice Martin',
            'email' => 'alice.martin@example.com',
        ]);
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'groupe_sanguin' => 'AB+',
        ]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $response->assertSessionHasErrors(['user']);
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherUser = User::factory()->create(['role' => 'patient']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $otherUser));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $otherUser->id]);
    }

    public function test_admin_can_access_medical_bulletin_and_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $patient = Patient::create([
            'nom' => 'Konaté',
            'prenom' => 'Fatou',
            'email' => 'fatou@example.com',
        ]);
        $type = TypeAnalyse::create([
            'code' => 'TA-GLUCOSE',
            'nom' => 'Glycémie à jeun',
            'prix' => 2500,
            'unite' => 'g/L',
            'actif' => true,
        ]);
        $resultat = Resultat::create([
            'patient_id' => $patient->id,
            'type_analyse_id' => $type->id,
            'valeur' => 0.95,
            'unite' => 'g/L',
            'statut' => 'completee',
            'date_resultat' => today(),
        ]);

        $this->actingAs($admin)
            ->get(route('resultats.bulletin', $resultat))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('resultats.pdf', $resultat))
            ->assertOk();
    }

    public function test_patient_updating_profile_synchronizes_user_name(): void
    {
        $user = User::factory()->create(['name' => 'Old Name', 'role' => 'patient']);
        $patient = Patient::create([
            'user_id' => $user->id,
            'nom' => 'Old',
            'prenom' => 'Name',
            'email' => $user->email,
        ]);

        $response = $this->actingAs($user)->put(route('patient.profil.update'), [
            'nom' => 'Traoré',
            'prenom' => 'Sekou',
            'groupe_sanguin' => 'O+',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Sekou Traoré',
        ]);
    }

    public function test_rendez_vous_conflict_prevents_double_booking(): void
    {
        $user = User::factory()->create(['role' => 'patient']);
        $patient = Patient::create([
            'user_id' => $user->id,
            'nom' => 'Diallo',
            'prenom' => 'Moussa',
            'email' => $user->email,
        ]);
        $medecin = Medecin::create([
            'nom' => 'Cissé',
            'prenom' => 'Dr',
            'email' => 'dr.cisse@clinique.sn',
        ]);

        $creneau = now()->addDays(2)->setHour(10)->setMinute(0)->setSecond(0);

        // Premier RDV enregistré
        RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'date_heure' => $creneau,
            'statut' => 'en_attente',
        ]);

        // Tentative d'un second patient sur le même créneau (+5 min)
        $user2 = User::factory()->create(['role' => 'patient']);
        Patient::create([
            'user_id' => $user2->id,
            'nom' => 'Ba',
            'prenom' => 'Amadou',
            'email' => $user2->email,
        ]);

        $response = $this->actingAs($user2)->post(route('patient.rendez-vous.store'), [
            'medecin_id' => $medecin->id,
            'date_heure' => $creneau->copy()->addMinutes(5)->toDateTimeString(),
        ]);

        $response->assertSessionHasErrors(['date_heure']);
    }

    public function test_type_analyse_with_results_is_deactivated_instead_of_deleted(): void
    {
        $medecinUser = User::factory()->create(['role' => 'medecin']);
        $medecin = Medecin::create([
            'user_id' => $medecinUser->id,
            'nom' => 'Doctor',
            'prenom' => 'Who',
            'email' => $medecinUser->email,
        ]);
        $patient = Patient::create([
            'nom' => 'Test',
            'prenom' => 'Patient',
            'email' => 'test@example.com',
        ]);
        $type = TypeAnalyse::create([
            'code' => 'TA-NFS',
            'nom' => 'NFS',
            'prix' => 5000,
            'actif' => true,
        ]);
        Resultat::create([
            'patient_id' => $patient->id,
            'type_analyse_id' => $type->id,
            'medecin_id' => $medecin->id,
            'statut' => 'completee',
        ]);

        $response = $this->actingAs($medecinUser)->delete(route('medecin.types.destroy', $type));

        $response->assertRedirect();
        // L'analyse n'est PAS détruite pour préserver l'historique
        $this->assertDatabaseHas('type_analyses', [
            'id' => $type->id,
            'actif' => false,
        ]);
    }
}
