<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Ressource;
use App\Models\User;
use App\Notifications\ReservationCreated;
use App\Notifications\ReservationStatusUpdated;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class ReservationNotificationTest extends TestCase
{
    use RefreshDatabase ;
    public function notification_envoyee_lors_de_la_creation()
    {
        Notification::fake();
        $user = User::factory()->create();
        $ressource = Ressource::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/reservations', [
                'ressource_id' => $ressource->id,
                'date_debut' => now()->addDay()->toDateTimeString(),
                'date_fin' => now()->addDays(2)->toDateTimeString(),
                'statut' => 'pending'
            ]);

        Notification::assertSentTo($user, ReservationCreated::class);
    }

     public function notification_envoyee_lors_changement_statut()
    {
        Notification::fake();

        $user = User::factory()->create();
        $ressource = Ressource::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'ressource_id' => $ressource->id,
            'date_debut' => now()->addDay(),
            'date_fin' => now()->addDays(2),
            'statut' => 'pending'
        ]);

        $this->actingAs($user, 'sanctum')
            ->putJson('/api/reservations/' . $reservation->id, [
                'statut' => 'approved'
            ]);

        Notification::assertSentTo($user, ReservationStatusUpdated::class);
    }
}
