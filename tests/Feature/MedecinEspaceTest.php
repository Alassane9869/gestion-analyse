<?php

namespace Tests\Feature;

use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedecinEspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_medecin_is_redirected_to_its_dashboard_after_login(): void
    {
        $user = User::factory()->create(['role' => 'medecin']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('medecin.espace.dashboard'));
    }

    public function test_patient_cannot_open_medecin_pages(): void
    {
        $patient = User::factory()->create(['role' => 'patient']);

        $this->actingAs($patient)
            ->get(route('medecin.espace.patients'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_medecin_only_sees_and_updates_its_own_appointments(): void
    {
        $doctorUser = User::factory()->create(['role' => 'medecin']);
        $otherDoctorUser = User::factory()->create(['role' => 'medecin']);
        $doctor = Medecin::create(['nom' => 'Traoré', 'prenom' => 'Aminata', 'user_id' => $doctorUser->id]);
        $otherDoctor = Medecin::create(['nom' => 'Diarra', 'prenom' => 'Moussa', 'user_id' => $otherDoctorUser->id]);
        $patient = Patient::create(['nom' => 'Sarr', 'prenom' => 'Mamadou']);
        $ownAppointment = RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $doctor->id,
            'date_heure' => now()->addDay(),
            'statut' => 'en_attente',
        ]);
        $otherAppointment = RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $otherDoctor->id,
            'date_heure' => now()->addDay(),
            'statut' => 'en_attente',
        ]);

        $this->actingAs($doctorUser)
            ->get(route('medecin.espace.rendez-vous'))
            ->assertOk()
            ->assertSee('Aminata')
            ->assertDontSee('Diarra');

        $this->patch(route('medecin.espace.rendez-vous.update', $otherAppointment), ['statut' => 'accepte'])
            ->assertNotFound();

        $this->assertSame('en_attente', $otherAppointment->fresh()->statut);

        $this->patch(route('medecin.espace.rendez-vous.update', $ownAppointment), ['statut' => 'accepte'])
            ->assertRedirect();

        $this->assertSame('accepte', $ownAppointment->fresh()->statut);
    }

    public function test_medecin_can_create_patient_with_international_phone_and_view_dossier(): void
    {
        $doctorUser = User::factory()->create(['role' => 'medecin']);

        $response = $this->actingAs($doctorUser)->post(route('medecin.espace.patients.store'), [
            'prenom' => 'Fatou',
            'nom' => 'Diallo',
            'email' => 'fatou.diallo@exemple.sn',
            'whatsapp_phone' => '+221 77 123 45 67',
            'groupe_sanguin' => 'O+',
            'date_naissance' => '1995-04-12',
            'sexe' => 'F',
            'adresse' => 'Dakar, Point E',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('medecin.espace.patients'));

        $this->assertDatabaseHas('patients', [
            'email' => 'fatou.diallo@exemple.sn',
            'groupe_sanguin' => 'O+',
            'sexe' => 'F',
        ]);

        $patient = Patient::where('email', 'fatou.diallo@exemple.sn')->first();
        $this->assertNotNull($patient);

        // Médecin can view patient dossier
        $this->actingAs($doctorUser)
            ->get(route('medecin.espace.patients.show', $patient))
            ->assertOk()
            ->assertSee('Fatou Diallo')
            ->assertSee('Dakar, Point E');
    }

    public function test_authorized_users_can_view_official_bulletin(): void
    {
        $doctorUser = User::factory()->create(['role' => 'medecin']);
        $patientUser = User::factory()->create(['role' => 'patient']);
        $patient = Patient::create([
            'nom' => 'Cissé',
            'prenom' => 'Bakary',
            'email' => $patientUser->email,
            'user_id' => $patientUser->id,
        ]);

        $resultat = \App\Models\Resultat::create([
            'patient_id' => $patient->id,
            'valeur' => '5.8',
            'unite' => 'g/L',
            'statut' => 'completee',
            'date_resultat' => today(),
        ]);

        // Médecin can view bulletin
        $this->actingAs($doctorUser)
            ->get(route('resultats.bulletin', $resultat))
            ->assertOk()
            ->assertSee('Laboratoire BioSanté')
            ->assertSee('Bakary');

        // Patient owner can view bulletin
        $this->actingAs($patientUser)
            ->get(route('resultats.bulletin', $resultat))
            ->assertOk()
            ->assertSee('Laboratoire BioSanté')
            ->assertSee('5.8');

        // Backend PDF streaming test for doctor
        $this->actingAs($doctorUser)
            ->get(route('resultats.pdf', $resultat))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        // Backend PDF streaming test for patient owner
        $this->actingAs($patientUser)
            ->get(route('resultats.pdf', $resultat))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        // Unauthorized patient is blocked (403)
        $otherPatient = User::factory()->create(['role' => 'patient']);
        $this->actingAs($otherPatient)
            ->get(route('resultats.pdf', $resultat))
            ->assertForbidden();
    }
}